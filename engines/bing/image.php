<?php
    class BingImageSearch extends EngineRequest {
        public function get_request_url() {
            $query_encoded = str_replace("%22", "\"", urlencode($this->query));
            $first = ($this->page * 35) + 1;

            $url = "https://www.bing.com/images/search?q=$query_encoded&form=HDRSC2&first=$first";

            // SafeSearch is mandatory here, not a visitor toggle.
            $url .= "&adlt=strict";

            return $url;
        }

        public function parse_results($response) {
            $results = array();
            $xpath = get_xpath($response);

            if (!$xpath)
                return $results;

            foreach ($xpath->query("//a[@class='iusc']") as $result) {
                $meta = json_decode($result->getAttribute("m"), true);

                if (!$meta || empty($meta["murl"]))
                    continue;

                $url = $meta["purl"] ?? $meta["murl"];

                if (!empty($results) && end($results)["url"] == $url)
                    continue;

                array_push($results,
                    array (
                        "thumbnail" => htmlspecialchars($meta["turl"] ?? $meta["murl"]),
                        "alt" => htmlspecialchars($meta["t"] ?? ""),
                        "url" => htmlspecialchars($url)
                    )
                );
            }

            return $results;
        }

        public static function print_results($results, $opts) {
            echo "<div class=\"image-result-container\">";

            foreach ($results as $result) {
                if (!$result
                    || !array_key_exists("url", $result)
                    || !array_key_exists("alt", $result))
                    continue;

                $thumbnail = urlencode($result["thumbnail"]);
                $alt = $result["alt"];
                $url = $result["url"];
                $url = check_for_privacy_frontend($url, $opts);

                echo "<a title=\"$alt\" href=\"$url\" rel=\"noreferer noopener\" target=\"_blank\">";
                echo "<img src=\"image_proxy.php?url=$thumbnail\">";
                echo "</a>";
            }

            echo "</div>";
        }
    }
?>
