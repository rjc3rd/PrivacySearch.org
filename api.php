<?php
    if (empty(trim($_REQUEST["q"] ?? ""))) {
        $page_noindex = true;
        require_once "misc/header.php";
?>

    <title>ProxySearch - API</title>
    </head>
    <body class="bg-white text-zinc-900 dark:bg-zinc-900 dark:text-zinc-100">
        <div class="mx-auto max-w-2xl px-4 pb-28 pt-10 sm:pt-16">
            <h1 class="text-3xl font-bold tracking-tight">API</h1>

            <?php if ($opts->disable_api): ?>
            <p class="mt-6 font-medium"><?php printtext("api_unavailable"); ?></p>
            <?php else: ?>

            <p class="mt-4 text-zinc-600 dark:text-zinc-300">ProxySearch has a JSON API behind the same code the website itself uses --
            if you're writing a script or tool that wants search results without parsing
            HTML, this is it.</p>

            <h2 class="mt-10 text-lg font-semibold">Example request</h2>
            <pre class="mt-3 overflow-x-auto rounded-lg bg-zinc-100 p-4 text-sm dark:bg-zinc-800">GET <a class="text-accent-600 hover:underline dark:text-accent-400" href="./api.php?q=gentoo&amp;p=0&amp;t=0">/api.php?q=gentoo&amp;p=0&amp;t=0</a></pre>
            <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">Both GET and POST work identically.</p>

            <h2 class="mt-10 text-lg font-semibold">Parameters</h2>
            <table class="mt-3 w-full border-collapse text-sm">
                <tr class="border-b border-zinc-200 text-left dark:border-zinc-800"><th class="py-2 pr-4">Param</th><th class="py-2 pr-4">Meaning</th><th class="py-2">Default</th></tr>
                <tr class="border-b border-zinc-200 dark:border-zinc-800"><td class="py-2 pr-4"><code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">q</code></td><td class="py-2 pr-4">Your search query.</td><td class="py-2">required</td></tr>
                <tr class="border-b border-zinc-200 dark:border-zinc-800"><td class="py-2 pr-4"><code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">p</code></td><td class="py-2 pr-4">Result page, starting at <code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">0</code> for the first page.</td><td class="py-2"><code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">0</code></td></tr>
                <tr><td class="py-2 pr-4"><code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">t</code></td><td class="py-2 pr-4">Which category to search -- see below.</td><td class="py-2"><code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">0</code></td></tr>
            </table>

            <h2 class="mt-10 text-lg font-semibold">Search categories ("t")</h2>
            <table class="mt-3 w-full border-collapse text-sm">
                <tr class="border-b border-zinc-200 text-left dark:border-zinc-800"><th class="py-2 pr-4">t</th><th class="py-2 pr-4">Category</th><th class="py-2">Response shape</th></tr>
                <tr class="border-b border-zinc-200 dark:border-zinc-800"><td class="py-2 pr-4"><code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">0</code></td><td class="py-2 pr-4">General (web search)</td><td class="py-2">object, keyed by result number</td></tr>
                <tr class="border-b border-zinc-200 dark:border-zinc-800"><td class="py-2 pr-4"><code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">1</code></td><td class="py-2 pr-4">Torrents</td><td class="py-2">array of listings</td></tr>
                <tr class="border-b border-zinc-200 dark:border-zinc-800"><td class="py-2 pr-4"><code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">2</code></td><td class="py-2 pr-4">Tor / hidden-service search</td><td class="py-2">array of results</td></tr>
                <tr><td class="py-2 pr-4"><code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">3</code></td><td class="py-2 pr-4">Maps</td><td class="py-2">array of place results</td></tr>
            </table>

            <h2 class="mt-10 text-lg font-semibold">Response format</h2>
            <p class="mt-3 text-zinc-600 dark:text-zinc-300">Always JSON, always UTF-8. A general search (<code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">t=0</code>) looks like this:</p>
            <pre class="mt-3 overflow-x-auto rounded-lg bg-zinc-100 p-4 text-sm dark:bg-zinc-800">{
  "0": {
    "title": "Gentoo Linux - Wikipedia",
    "url": "https://en.wikipedia.org/wiki/Gentoo_Linux",
    "base_url": "https://en.wikipedia.org/",
    "description": "Gentoo Linux is a source-based Linux distribution..."
  },
  "1": { "title": "...", "url": "...", "description": "..." },
  "results_source": "html.duckduckgo.com"
}</pre>
            <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400"><code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">results_source</code> is whichever engine actually answered this specific
            request -- it's picked automatically and can be different on every request. A
            general search sometimes also includes a <code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">special_response</code> entry (an
            instant answer, like a Wikipedia summary) ahead of the regular results.</p>

            <p class="mt-4 text-sm text-zinc-500 dark:text-zinc-400">Torrents and maps (<code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">t=1</code> and <code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">t=3</code>) come back as a plain
            array instead, since there's no "instant answer" concept for those. A torrent entry
            looks like:</p>
            <pre class="mt-3 overflow-x-auto rounded-lg bg-zinc-100 p-4 text-sm dark:bg-zinc-800">{
  "name": "some-torrent-name",
  "size": "1.69 GB",
  "seeders": 127,
  "leechers": 12,
  "magnet": "magnet:?xt=urn:btih:..."
}</pre>

            <h2 class="mt-10 text-lg font-semibold">Worth knowing</h2>
            <ul class="mt-3 list-disc space-y-2 pl-5 text-sm text-zinc-600 dark:text-zinc-300">
                <li>SafeSearch is always on for every request -- there's no parameter to turn it off.</li>
                <li>No results just comes back empty, not as an error.</li>
                <li>A request that fails outright returns HTTP 500 with an <code class="rounded bg-zinc-100 px-1.5 py-0.5 dark:bg-zinc-800">error</code> key explaining what happened.</li>
            </ul>

            <?php endif; ?>
        </div>

<?php
        require_once "misc/footer.php";
        die();
    }

    require_once "misc/search_engine.php";
    require_once "locale/localization.php";

    $opts = load_opts();
    if ($opts->disable_api) {
        header("Content-Type: application/json");
        http_response_code(403);
        echo json_encode(array("error" => array("message" => TEXTS["api_unavailable"])));
        die();
    }

    require_once "misc/tools.php";

    $results = fetch_search_results($opts, false);
    if (array_key_exists("error", $results)) {
        http_response_code(500);
    }
    header("Content-Type: application/json");
    echo json_encode($results);
?>
