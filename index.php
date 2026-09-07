<?php require_once "misc/header.php"; ?>
<title><?php printbrand("site_title") ?></title>
</head>

<body>

        <form class="search-container" action="search.php" method="get" autocomplete="off">
                <img src="anonymous.svg" width="200px" />
                <h1><?php printbrand("site_title"); ?></h1>
                <input type="text" name="q" autofocus />
                <input type="hidden" name="p" value="0" />
                <input type="hidden" name="t" value="0" />
                <input type="submit" class="hide" />
                <div class="search-button-wrapper">
                </div>
                <h3><?php printtext("site_description"); ?></h3>
        </form>

        <?php require_once "misc/footer.php"; ?>