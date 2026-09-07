<?php
    class YahooSearchRequest extends EngineRequest {
        public function get_request_url() {
            $query_encoded = str_replace("%22", "\"", urlencode($this->query));
            $results_language = $this->opts->language;

            // Yahoo paginates with "b" = starting result index (1-based), 10 per page.
            $start = ($this->page * 10) + 1;

            $url = "https://search.yahoo.com/search?p=$query_encoded&b=$start";

            if (3 > strlen($results_language) && 0 < strlen($results_language))
                $url .= "&vl=lang_$results_language";

            // SafeSearch is mandatory here, not a visitor toggle. "r" is
            // Yahoo's strict value.
            $url .= "&vm=r";

            return $url;
        }

        // Yahoo wraps every result link in a r.search.yahoo.com redirect;
        // the real destination is URL-encoded in the "RU" query param.
        private function unwrap_redirect($href) {
            $query = parse_url($href, PHP_URL_QUERY);
            if ($query === null)
                return $href;

            parse_str($query, $params);
            return isset($params["RU"]) ? urldecode($params["RU"]) : $href;
        }

        public function parse_results($response) {
            $results = array();
            $xpath = get_xpath($response);

            if (!$xpath)
                return $results;

            foreach ($xpath->query("//div[contains(concat(' ', normalize-space(@class), ' '), ' algo ')]") as $result) {
                $link = $xpath->evaluate(".//div[contains(@class, 'compTitle')]//a[@href]", $result)[0];

                if ($link == null)
                    continue;

                $url = $this->unwrap_redirect($link->getAttribute("href"));

                if (!empty($results) && end($results)["url"] == $url)
                    continue;

                $title = $xpath->evaluate(".//h3", $result)[0];
                $description = $xpath->evaluate(".//div[contains(@class, 'compText')]", $result)[0];

                array_push($results,
                    array (
                        "title" => htmlspecialchars(trim($title->textContent)),
                        "url" => htmlspecialchars($url),
                        // base_url is to be removed in the future, see #47
                        "base_url" => htmlspecialchars(get_base_url($url)),
                        "description" => $description == null ?
                                          TEXTS["result_no_description"] :
                                          htmlspecialchars(trim($description->textContent))
                    )
                );
            }

            return $results;
        }
    }
?>
