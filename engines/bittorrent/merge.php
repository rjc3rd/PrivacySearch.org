<?php
    class TorrentSearch extends EngineRequest {
        protected $requests;
        public function __construct($opts, $mh) {
            parent::__construct($opts, $mh);

            require_once "engines/bittorrent/thepiratebay.php";
            require_once "engines/bittorrent/rutor.php";
            require_once "engines/bittorrent/yts.php";
            require_once "engines/bittorrent/torrentgalaxy.php";
            require_once "engines/bittorrent/1337x.php";
            require_once "engines/bittorrent/sukebei.php";

            $this->requests = array(
                new PirateBayRequest($opts, $mh),
                new _1337xRequest($opts, $mh),
                new NyaaRequest($opts, $mh),
                new RutorRequest($opts, $mh),
                new SukebeiRequest($opts, $mh),
                new TorrentGalaxyRequest($opts, $mh),
                new YTSRequest($opts, $mh),
            );
        }

        public function parse_results($response) {
            $results = array();
            foreach ($this->requests as $request) {
                if ($request->successful())
                    $results = array_merge($results, $request->get_results());
            }

            $seeders = array_column($results, "seeders");
            array_multisort($seeders, SORT_DESC, $results);

            return $results; 
        }

        public static function print_results($results, $opts) {
            echo "<div class=\"flex max-w-2xl flex-col gap-6\">";

            if (empty($results)) {
                echo "<p class=\"text-zinc-500 dark:text-zinc-400\">" . TEXTS["failure_empty"] . "</p>";
                return;
            }

            foreach($results as $result) {
                $source = $result["source"];
                $name = $result["name"];
                $magnet = $result["magnet"];
                $seeders = $result["seeders"];
                $leechers = $result["leechers"];
                $size = $result["size"];

                echo "<div class=\"break-words\">";
                echo "<a class=\"group\" href=\"$magnet\">";
                echo "<span class=\"text-sm text-zinc-500 dark:text-zinc-400\">$source</span>";
                echo "<h2 class=\"text-lg text-accent-700 group-hover:underline dark:text-accent-400\">$name</h2>";
                echo "</a>";
                echo "<span class=\"text-sm text-zinc-600 dark:text-zinc-300\">SE: <span class=\"font-medium text-green-600 dark:text-green-400\">$seeders</span> - ";
                echo "LE: <span class=\"font-medium text-pink-600 dark:text-pink-400\">$leechers</span> - ";
                echo "$size</span>";
                echo "</div>";
            }

            echo "</div>";
        }
    }

?>
