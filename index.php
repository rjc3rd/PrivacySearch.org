<?php require_once "misc/header.php"; ?>

    <title><?php printtext("meta_title"); ?></title>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "ProxySearch",
        "url": "https://proxysearch.org/",
        "potentialAction": {
            "@type": "SearchAction",
            "target": {
                "@type": "EntryPoint",
                "urlTemplate": "https://proxysearch.org/search.php?q={search_term_string}"
            },
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    </head>
    <body class="bg-white text-zinc-900 dark:bg-zinc-900 dark:text-zinc-100">
        <form class="flex min-h-screen flex-col items-center justify-center gap-4 px-4 pb-24 text-center" action="search.php" method="get" autocomplete="off">
                <img class="w-28 invert sm:w-36 dark:invert-0" src="anonymous.svg" alt="ProxySearch"/>
                <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">ProxySearch</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Bing, DuckDuckGo, Yahoo</p>

                <input class="w-full max-w-md rounded-full border border-zinc-300 bg-white px-5 py-3 text-base shadow-sm outline-none placeholder:text-zinc-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-500/40 dark:border-zinc-700 dark:bg-zinc-800 dark:placeholder:text-zinc-500"
                       type="text" name="q" autofocus/>
                <input type="hidden" name="p" value="0"/>
                <input type="hidden" name="t" value="0"/>
                <input type="submit" class="hidden"/>

                <div class="flex flex-wrap items-center justify-center gap-3">
                <button class="rounded-full bg-accent-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-accent-500/40"
                        name="t" value="0" type="submit"><?php printtext("search_button"); ?></button>
                <?php if (!$opts->disable_bittorrent_search) {
                    echo '<button class="rounded-full border border-zinc-300 px-5 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:bg-zinc-100 focus:outline-none focus:ring-2 focus:ring-accent-500/40 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800" name="t" value="1" type="submit">', printtext("torrent_search_button"), '</button>';
                } ?>
                </div>

                <h3 class="mt-2 text-base font-medium text-zinc-600 dark:text-zinc-300"><?php printtext("site_description"); ?></h3>
                <?php
                    // Stamped by deploy-site on every deploy; won't exist in local dev.
                    $last_update = @trim(file_get_contents(".deploy-version"));
                    if (!empty($last_update)) {
                        echo '<p class="text-xs text-zinc-400 dark:text-zinc-500">' . sprintf(TEXTS["active_development_notice"], htmlspecialchars($last_update)) . '</p>';
                    }
                ?>
        </form>

<?php require_once "misc/footer.php"; ?>
