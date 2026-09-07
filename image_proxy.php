<?php

    $config = require_once "config.php";
    require_once "misc/tools.php";

    $url = $_REQUEST["url"];
    $requested_root_domain = get_root_domain($url);

    // Only the Wikipedia instant-answer thumbnail (see engines/special/wikipedia.php)
    // uses this proxy now -- image and video search were both removed.
    $allowed_domains = array("upload.wikimedia.org");

    if (in_array($requested_root_domain, $allowed_domains))
    {
      $image = $url;
      $image_src = request($image, $config->curl_settings);

      header("Content-Type: image/png");
      echo $image_src;
    }
?>
