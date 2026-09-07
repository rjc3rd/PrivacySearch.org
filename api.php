<?php
    if (empty(trim($_REQUEST["q"] ?? ""))) {
        require_once "misc/header.php";
?>

    <title>ProxySearch - API</title>
    </head>
    <body>
        <div class="misc-container api-docs">
            <h1>API</h1>

            <?php if ($opts->disable_api): ?>
            <p><strong><?php printtext("api_unavailable"); ?></strong></p>
            <?php else: ?>

            <p>ProxySearch has a JSON API behind the same code the website itself uses --
            if you're writing a script or tool that wants search results without parsing
            HTML, this is it.</p>

            <h2>Example request</h2>
            <pre>GET <a href="./api.php?q=gentoo&amp;p=0&amp;t=0">/api.php?q=gentoo&amp;p=0&amp;t=0</a></pre>
            <p>Both GET and POST work identically.</p>

            <h2>Parameters</h2>
            <table>
                <tr><th>Param</th><th>Meaning</th><th>Default</th></tr>
                <tr><td><code>q</code></td><td>Your search query.</td><td>required</td></tr>
                <tr><td><code>p</code></td><td>Result page, starting at <code>0</code> for the first page.</td><td><code>0</code></td></tr>
                <tr><td><code>t</code></td><td>Which category to search -- see below.</td><td><code>0</code></td></tr>
            </table>

            <h2>Search categories ("t")</h2>
            <table>
                <tr><th>t</th><th>Category</th><th>Response shape</th></tr>
                <tr><td><code>0</code></td><td>General (web search)</td><td>object, keyed by result number</td></tr>
                <tr><td><code>1</code></td><td>Torrents</td><td>array of listings</td></tr>
                <tr><td><code>2</code></td><td>Tor / hidden-service search</td><td>array of results</td></tr>
                <tr><td><code>3</code></td><td>Maps</td><td>array of place results</td></tr>
            </table>

            <h2>Response format</h2>
            <p>Always JSON, always UTF-8. A general search (<code>t=0</code>) looks like this:</p>
            <pre>{
  "0": {
    "title": "Gentoo Linux - Wikipedia",
    "url": "https://en.wikipedia.org/wiki/Gentoo_Linux",
    "base_url": "https://en.wikipedia.org/",
    "description": "Gentoo Linux is a source-based Linux distribution..."
  },
  "1": { "title": "...", "url": "...", "description": "..." },
  "results_source": "html.duckduckgo.com"
}</pre>
            <p><code>results_source</code> is whichever engine actually answered this specific
            request -- it's picked automatically and can be different on every request. A
            general search sometimes also includes a <code>special_response</code> entry (an
            instant answer, like a Wikipedia summary) ahead of the regular results.</p>

            <p>Torrents and maps (<code>t=1</code> and <code>t=3</code>) come back as a plain
            array instead, since there's no "instant answer" concept for those. A torrent entry
            looks like:</p>
            <pre>{
  "name": "some-torrent-name",
  "size": "1.69 GB",
  "seeders": 127,
  "leechers": 12,
  "magnet": "magnet:?xt=urn:btih:..."
}</pre>

            <h2>Worth knowing</h2>
            <ul>
                <li>SafeSearch is always on for every request -- there's no parameter to turn it off.</li>
                <li>No results just comes back empty, not as an error.</li>
                <li>A request that fails outright returns HTTP 500 with an <code>error</code> key explaining what happened.</li>
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
