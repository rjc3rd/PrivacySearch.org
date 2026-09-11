<?php
// Apache serves this directly for the matching HTTP status (see
// .htaccess) -- it can be reached for a request at any depth/path, so
// chdir to the site root first so these relative includes resolve the
// same way they already do from index.php et al.
chdir(__DIR__ . '/..');
require_once "locale/localization.php";
$GLOBALS["opts"] = require_once "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="robots" content="noindex, nofollow"/>
    <title>503 Service Unavailable — ProxySearch</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="stylesheet" type="text/css" href="/css/output.css"/>
</head>
<body class="bg-white text-zinc-900 dark:bg-zinc-900 dark:text-zinc-100">
    <div class="flex min-h-screen flex-col items-center justify-center gap-4 px-4 pb-24 text-center">
        <img class="w-20 invert sm:w-24 dark:invert-0" src="/anonymous.svg" alt="ProxySearch"/>
        <p class="font-mono text-sm text-accent-600 dark:text-accent-400">Error 503</p>
        <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Service unavailable</h1>
        <p class="max-w-md text-sm text-zinc-500 dark:text-zinc-400">ProxySearch is temporarily unavailable, likely for maintenance. Try again shortly.</p>
        <a class="mt-2 rounded-full bg-accent-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-accent-700" href="/">Back to search</a>
    </div>

<?php require_once "misc/footer.php"; ?>
