<?php require_once "locale/localization.php";
      $GLOBALS["opts"] = require_once "config.php";

      // Each page sets $page_noindex = true before including this file if it
      // shouldn't be indexed at all (Settings, API docs) -- everything else
      // (homepage) stays indexable. search.php is excluded via robots.txt
      // instead, since its URL space is unbounded (every query x page x
      // category) -- noindex would mean crawling all of that pointlessly.
      $canonical_url = "https://proxysearch.org" . strtok($_SERVER["REQUEST_URI"], "?");
 ?>
<!DOCTYPE html >
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta charset="UTF-8"/>
        <meta name="description" content="<?php printtext("meta_description"); ?>"/>
        <meta name="referrer" content="no-referrer"/>
        <?php if (!empty($page_noindex)): ?>
        <meta name="robots" content="noindex, follow"/>
        <?php endif; ?>
        <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>"/>
        <meta property="og:type" content="website"/>
        <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url); ?>"/>
        <meta property="og:title" content="<?php printtext("meta_title"); ?>"/>
        <meta property="og:description" content="<?php printtext("meta_description"); ?>"/>
        <meta property="og:image" content="https://proxysearch.org/og-image.png"/>
        <meta name="twitter:card" content="summary_large_image"/>
        <meta name="twitter:title" content="<?php printtext("meta_title"); ?>"/>
        <meta name="twitter:description" content="<?php printtext("meta_description"); ?>"/>
        <meta name="twitter:image" content="https://proxysearch.org/og-image.png"/>
        <link rel="icon" type="image/svg+xml" href="favicon.svg">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link rel="manifest" href="site.webmanifest">
        <link rel="stylesheet" type="text/css" href="css/output.css"/>
        <link title="<?php printtext("page_title"); ?>" type="application/opensearchdescription+xml" href="opensearch.xml?method=POST" rel="search"/>
