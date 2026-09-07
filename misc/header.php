<?php require_once "locale/localization.php";
      $GLOBALS["opts"] = require_once "config.php";
 ?>
<!DOCTYPE html >
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta charset="UTF-8"/>
        <meta name="description" content="<?php printtext("meta_description"); ?>"/>
        <meta name="referrer" content="no-referrer"/>
        <link rel="icon" type="image/x-icon" href="favicon.svg">
        <link rel="stylesheet" type="text/css" href="static/css/styles.css"/>
        <link title="<?php printtext("page_title"); ?>" type="application/opensearchdescription+xml" href="opensearch.xml?method=POST" rel="search"/>
        <link rel="stylesheet" type="text/css" href="<?php
$theme = $_REQUEST["theme"] ?? trim(htmlspecialchars($_COOKIE["theme"] ?? $GLOBALS["opts"]->default_theme ?? "dark"));
                echo "static/css/" . $theme . ".css";
        ?>"/>
