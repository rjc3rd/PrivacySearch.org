<?php

    $config = require_once "config.php";
    require_once "misc/tools.php";

    $url = $_REQUEST["url"];
    $requested_root_domain = get_root_domain($url);

    // Only the Wikipedia instant-answer thumbnail (see engines/special/wikipedia.php)
    // uses this proxy now -- image and video search were both removed. Wikipedia's
    // pageimages API has been seen serving thumbnails from both upload.wikimedia.org
    // and thumb.wikimedia.org (the latter broke this proxy entirely until now, since
    // it wasn't in this list) -- allow the whole wikimedia.org family rather than
    // chase whichever subdomain their API uses next.
    $allowed = ($requested_root_domain === "wikimedia.org")
        || (substr($requested_root_domain, -strlen(".wikimedia.org")) === ".wikimedia.org");

    if ($allowed)
    {
      $image = $url;
      $image_src = request($image, $config->curl_settings);

      // Declare the real content type instead of assuming PNG -- Wikipedia
      // thumbnails are frequently JPEG, and a wrong declared type is a real
      // bug regardless of how forgiving any given browser is about it.
      $image_info = @getimagesizefromstring($image_src);
      header("Content-Type: " . ($image_info["mime"] ?? "image/jpeg"));
      echo $image_src;
    }
?>
