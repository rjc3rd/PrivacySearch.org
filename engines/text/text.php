<?php
    function get_engines() {
        return array("duckduckgo", "bing", "yahoo");
    }

    class TextSearch extends EngineRequest {
        protected $cache_key, $engine, $engines, $engine_request, $special_request;
        public function __construct($opts, $mh) {
            $this->engines = get_engines();
            shuffle($this->engines);

            $this->query = $opts->query;
            $this->cache_key = "text:" . $this->query . "p" . $opts->page . "l" . $opts->language;

            $this->page = $opts->page;
            $this->opts = $opts;

            $this->engine = $opts->engine;

            $query_parts = explode(" ", $this->query);
            $last_word_query = end($query_parts);
            if (substr($this->query, 0, 1) == "!" || substr($last_word_query, 0, 1) == "!")
                check_ddg_bang($this->query, $opts);

            if (has_cached_results($this->cache_key))
                return;

            if ($this->engine == "auto")
                $this->engine = $this->select_engine();

            // no engine was selected
            if (is_null($this->engine))
                return;

            // this only happens if a specific engine was selected, not if auto is used
            if (has_cooldown($this->engine, $this->opts->cooldowns))
                return;

            $this->engine_request = $this->get_engine_request($this->engine, $opts, $mh);

            if (is_null($this->engine_request))
                return;

            require_once "engines/special/special.php";
            $this->special_request = get_special_search_request($opts, $mh);
        }
        private function select_engine() {
            if (sizeof($this->engines) == 0)
                return null;

            $engine = array_pop($this->engines);

            // if this engine is on cooldown, try again
            if (!has_cooldown($engine, $this->opts->cooldowns))
                return $engine;

            return $this->select_engine();
        }

        private function get_engine_request($engine, $opts, $mh) {
            if ($engine == "duckduckgo") {
                require_once "engines/text/duckduckgo.php";
                return new DuckDuckGoRequest($opts, $mh);
            }

            if ($engine == "bing") {
                require_once "engines/text/bing.php";
                return new BingSearchRequest($opts, $mh);
            }

            if ($engine == "yahoo") {
                require_once "engines/text/yahoo.php";
                return new YahooSearchRequest($opts, $mh);
            }

            // if an invalid engine is selected, don't give any results
            return null;
        }

        public function parse_results($response) {
            if (has_cached_results($this->cache_key))
                return fetch_cached_results($this->cache_key);

            if (!isset($this->engine_request))
                return array();

            $results = $this->engine_request->get_results();

            if (empty($results)) {
                set_cooldown($this->engine, ($this->opts->request_cooldown ?? "1") * 60, $this->opts->cooldowns);
            } else {
                if ($this->special_request) {
                    $special_result = $this->special_request->get_results();

                    if ($special_result)
                        $results = array_merge(array($special_result), $results);
                }
            }

            if (!empty($results)) {
                $results["results_source"] = parse_url($this->engine_request->url)["host"];
                store_cached_results($this->cache_key, $results, $this->opts->cache_time * 60);
            }

            return $results;
        }

        public static function print_results($results, $opts)  {

            if (empty($results)) {
                echo "<div class=\"max-w-2xl\"><p class=\"text-zinc-500 dark:text-zinc-400\">An error occured fetching results</p></div>";
                return;
            }

            if (array_key_exists("error", $results)) {
                echo "<div class=\"max-w-2xl\"><p class=\"text-zinc-500 dark:text-zinc-400\">" . $results["error"]["message"] . "</p></div>";
                return;
            }

            $special = $results[0];

            if (array_key_exists("did_you_mean", $special)) {
                $didyoumean = $special["did_you_mean"];
                $new_url = "/search.php?q="  . urlencode($didyoumean);
                echo "<p class=\"mb-4 text-sm text-zinc-500 dark:text-zinc-400\">Did you mean ";
                echo "<a class=\"text-accent-600 hover:underline dark:text-accent-400\" href=\"$new_url\">$didyoumean</a>";
                echo "?</p>";
            }

            if (array_key_exists("special_response", $special)) {
                $response = $special["special_response"]["response"];
                $source = $special["special_response"]["source"];

                echo "<div class=\"mb-6 max-w-md rounded-xl border border-zinc-200 bg-zinc-50 p-4 text-sm dark:border-zinc-800 dark:bg-zinc-800/50\">";
                if (array_key_exists("image", $special["special_response"])) {
                    $image_url = $special["special_response"]["image"];
                    echo "<img class=\"mx-auto mb-3 max-h-48 max-w-full rounded-lg\" src=\"image_proxy.php?url=$image_url\">";
                }
                echo "<p class=\"text-zinc-700 dark:text-zinc-300\">$response</p>";
                if ($source) {
                    $source = check_for_privacy_frontend($source, $opts);
                    echo "<a class=\"mt-2 block truncate text-accent-600 hover:underline dark:text-accent-400\" href=\"$source\" rel=\"noreferer noopener\" target=\"_blank\">$source</a>";
                }
                echo "</div>";
            }

            echo "<div class=\"flex max-w-2xl flex-col gap-6\">";

            foreach($results as $result) {
                if (!is_array($result))
                    continue;
                if (!array_key_exists("title", $result))
                    continue;

                $title = $result["title"];
                $url = $result["url"];
                $url = check_for_privacy_frontend($url, $opts);

                $base_url = get_base_url($url);
                $description = $result["description"];

                echo "<div class=\"break-words\">";
                echo "<a class=\"group\" rel=\"noreferer noopener\" href=\"$url\">";
                echo "<span class=\"text-sm text-zinc-500 dark:text-zinc-400\">$base_url</span>";
                echo "<h2 class=\"text-lg text-accent-700 group-hover:underline dark:text-accent-400\">$title</h2>";
                echo "</a>";
                echo "<span class=\"text-sm text-zinc-600 dark:text-zinc-300\">$description</span>";
                echo "</div>";
            }

            echo "</div>";
        }
    }

    function check_ddg_bang($query, $opts) {

        $bangs_json = file_get_contents("static/misc/ddg_bang.json");
        $bangs = json_decode($bangs_json, true);

        if (substr($query, 0, 1) == "!")
            $search_word = substr(explode(" ", $query)[0], 1);
        else
            $search_word = substr(end(explode(" ", $query)), 1);

        $bang_url = null;

        foreach($bangs as $bang) {
            if ($bang["t"] == $search_word) {
                $bang_url = $bang["u"];
                break;
            }
        }

        if ($bang_url) {
            $bang_query_array = explode("!" . $search_word, $query);
            $bang_query = trim(implode("", $bang_query_array));

            $request_url = str_replace("{{{s}}}", str_replace('%26quot%3B','%22', urlencode($bang_query)), $bang_url);

            header("Location: " . $request_url);
            die();
        }
    }

?>
