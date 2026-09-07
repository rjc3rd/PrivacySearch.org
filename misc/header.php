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
        <link rel="canonical" href="https://proxysearch.org/"/>
        <meta property="og:type" content="website"/>
        <meta property="og:url" content="https://proxysearch.org/"/>
        <meta property="og:title" content="<?php printtext("meta_title"); ?>"/>
        <meta property="og:description" content="<?php printtext("meta_description"); ?>"/>
        <meta property="og:image" content="https://proxysearch.org/og-image.png"/>
        <meta name="twitter:card" content="summary_large_image"/>
        <meta name="twitter:title" content="<?php printtext("meta_title"); ?>"/>
        <meta name="twitter:description" content="<?php printtext("meta_description"); ?>"/>
        <meta name="twitter:image" content="https://proxysearch.org/og-image.png"/>
        <link rel="icon" type="image/svg+xml" href="favicon.svg">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link rel="stylesheet" type="text/css" href="css/output.css"/>
        <!-- TODO: remove once search.php/settings.php/api.php and the engine
             result-renderers are all converted to Tailwind too. Hardcoded to
             dark.css (rather than the old theme-picker logic, now removed)
             just to keep those still-unconverted pages' CSS variables defined
             during the transition. -->
        <link rel="stylesheet" type="text/css" href="static/css/styles.css"/>
        <link rel="stylesheet" type="text/css" href="static/css/dark.css"/>
        <link title="<?php printtext("page_title"); ?>" type="application/opensearchdescription+xml" href="opensearch.xml?method=POST" rel="search"/>
