<?php
    require_once "misc/header.php";

    require_once "misc/tools.php";
    require_once "misc/search_engine.php";

    $opts = load_opts();

    function print_page_buttons($type, $query, $page) {
        if ($type > 1)
            return;
        echo "<div class=\"mb-24 flex flex-wrap items-center gap-1\">";

            if ($page != 0)
            {
                print_next_page_button("&laquo;", 0, $query, $type);
                print_next_page_button("&lsaquo;", $page - 10, $query, $type);
            }

            for ($i=$page / 10; $page / 10 + 10 > $i; $i++)
                print_next_page_button($i + 1, $i * 10, $query, $type);

            print_next_page_button("&rsaquo;", $page + 10, $query, $type);

        echo "</div>";
    }
?>

<title>
<?php
    echo $opts->query;
    ?> - <?php printtext("page_title");?></title>
</head>
    <body class="bg-white text-zinc-900 dark:bg-zinc-900 dark:text-zinc-100">
        <form class="sticky top-0 z-10 flex flex-col gap-3 border-b border-zinc-200 bg-white/95 px-4 py-3 backdrop-blur-sm sm:flex-row sm:items-center sm:gap-4 dark:border-zinc-800 dark:bg-zinc-900/95" method="get" autocomplete="off">
            <a class="shrink-0" href="./">
                <img class="w-8 invert dark:invert-0" src="anonymous.svg" alt="ProxySearch"/>
            </a>
            <input class="w-full min-w-0 rounded-full border border-zinc-300 bg-white px-4 py-2 text-sm outline-none focus:border-accent-500 focus:ring-2 focus:ring-accent-500/40 sm:max-w-md dark:border-zinc-700 dark:bg-zinc-800"
                type="text" name="q"
                <?php
                    if (1 > strlen($opts->query) || strlen($opts->query) > 256)
                    {
                        header("Location: ./");
                        die();
                    }

                    echo "value=\"" . htmlspecialchars($opts->query) . "\"";
                ?>
            >
            <?php
                echo "<input type=\"hidden\" name=\"t\" value=\"$opts->type\"/>";
            ?>
            <button type="submit" class="hidden"></button>
            <input type="hidden" name="p" value="0">
            <div class="flex flex-wrap gap-1 text-sm">
                <?php
                    $categories = array("general", "torrents", "tor", "maps");

                    foreach ($categories as $category)
                    {
                        $category_index = array_search($category, $categories);

                        if (($opts->disable_bittorrent_search && $category_index == 1) ||
                            ($opts->disable_hidden_service_search && $category_index == 2))
                        {
                            continue;
                        }

                        $is_active = $category_index == $opts->type;
                        $classes = $is_active
                            ? "rounded-full bg-accent-600 px-3 py-1.5 font-medium text-white"
                            : "rounded-full px-3 py-1.5 text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800";

                        echo "<a class=\"$classes\" href=\"./search.php?q=" . urlencode($opts->query) . "&p=0&t=" . $category_index . "\">" . TEXTS["category_$category"]  . "</a>";
                    }
                ?>
            </div>
        </form>

        <div class="px-4 py-6 sm:px-8">
        <?php
            fetch_search_results($opts, true);
            print_page_buttons($opts->type, $opts->query, $opts->page);
        ?>
        </div>

<?php require_once "misc/footer.php"; ?>
