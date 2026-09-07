<?php require_once "misc/header.php"; ?>

    <title>ProxySearch</title>
    </head>
    <body>
        <form class="search-container" action="search.php" method="get" autocomplete="off">
                <img src="anonymous.svg" width="200px" />
                <h1>ProxySearch</h1>
                <p class="engine-list">Bing, DuckDuckGo, Yahoo</p>
                <input type="text" name="q" autofocus/>
                <input type="hidden" name="p" value="0"/>
                <input type="hidden" name="t" value="0"/>
                <input type="submit" class="hide"/>
                <div class="search-button-wrapper">
                <button name="t" value="0" type="submit"><?php printtext("search_button"); ?></button>
                <?php if (!$opts->disable_bittorrent_search) {
                    echo '<button name="t" value="1" type="submit">', printtext("torrent_search_button"), '</button>';
                } ?>
                </div>
                <h3><?php printtext("site_description"); ?></h3>
                <?php
                    // Stamped by deploy-site on every deploy; won't exist in local dev.
                    $last_update = @trim(file_get_contents(".deploy-version"));
                    if (!empty($last_update)) {
                        echo '<p class="dev-status">' . sprintf(TEXTS["active_development_notice"], htmlspecialchars($last_update)) . '</p>';
                    }
                ?>
        </form>

<?php require_once "misc/footer.php"; ?>
