<?php

    $config = require_once "config.php";
    require_once "misc/tools.php";

    $url = $_REQUEST["url"];
    $requested_root_domain = get_root_domain($url);

    // Bing rotates thumbnails across several numbered CDN subdomains.
    $allowed_domains = array(
        "th.bing.com",
        "ts1.mm.bing.net", "ts2.mm.bing.net", "ts3.mm.bing.net", "ts4.mm.bing.net",
        "tse1.mm.bing.net", "tse2.mm.bing.net", "tse3.mm.bing.net", "tse4.mm.bing.net",
        "upload.wikimedia.org",
    );

    if (in_array($requested_root_domain, $allowed_domains))
    {
      $image = $url;
      $image_src = request($image, $config->curl_settings);

      header("Content-Type: image/png");
      echo $image_src;
    }
?>
