<?php
        require_once "misc/search_engine.php";

        // Reset all cookies when resetting, or before saving new cookies
	if (isset($_REQUEST["reset"])) {
            if (isset($_SERVER["HTTP_COOKIE"])) {
                $cookies = explode(";", $_SERVER["HTTP_COOKIE"]);
                foreach($cookies as $cookie) {
                    $parts = explode("=", $cookie);
                    $name = trim($parts[0]);

                    $domain = parse_url($_SERVER['SERVER_NAME']);

                    setcookie($name, "", time() - 1000);
                }
            }
        }

        if (isset($_REQUEST["save"])) {
            foreach($_POST as $key=>$value) {
                if (!empty($value)) {
                    setcookie($key, $value, [
                        "expires" => time() + (86400 * 90), // Sets cookie to expire in 90 days
                        "path" => "/",
                        "domain" => "$domain",
                        "secure" => true,       // Ensure cookies are only sent over HTTPS
                        "httponly" => true,     // Prevent client-side JavaScript access to cookies
                        "samesite" => "Strict"  // Strict SameSite policy for better protection against CSRF attacks
                    ]);
                } else {
                    setcookie($key, "", time() - 1000);
                }
            }
        }

        if (isset($_REQUEST["save"]) || isset($_REQUEST["reset"])) {
            header("Location: ./");
            die();
        }


        require_once "misc/header.php";
        $opts = load_opts();
?>

    <title>ProxySearch - <?php printtext("settings_title");?></title>
    </head>
    <body class="bg-white text-zinc-900 dark:bg-zinc-900 dark:text-zinc-100">
        <div class="mx-auto max-w-2xl px-4 pb-28 pt-10 sm:pt-16">
            <h1 class="text-3xl font-bold tracking-tight"><?php printtext("settings_title");?></h1>

            <form method="post" enctype="multipart/form-data" autocomplete="off" class="mt-8 flex flex-col gap-10">

                <section class="flex flex-col gap-4">
                    <h2 class="text-lg font-semibold"><?php printtext("settings_search_settings");?></h2>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="flex flex-col gap-1 text-sm">
                            <span class="font-medium text-zinc-600 dark:text-zinc-300"><?php printtext("settings_preferred_engine");?></span>
                            <select name="engine" class="rounded-lg border border-zinc-300 bg-white px-3 py-2 outline-none focus:border-accent-500 focus:ring-2 focus:ring-accent-500/40 dark:border-zinc-700 dark:bg-zinc-800">
                            <?php
                               require_once "engines/text/text.php";
                               $engines = get_engines();
                               $options = "<option value=\"\" " . (!isset($opts->engine) ? "selected" : "") . ">auto</option>";
                               foreach ($engines as $engine) {
                                   $selected = $opts->engine == $engine ? "selected" : "";
                                   $options .= "<option value=\"$engine\" $selected>$engine</option>";
                               }
                               echo $options;
                            ?>
                            </select>
                        </label>

                        <label class="flex flex-col gap-1 text-sm">
                            <span class="font-medium text-zinc-600 dark:text-zinc-300"><?php printtext("settings_number_of_results");?></span>
                            <input type="number" name="number_of_results" value="<?php echo htmlspecialchars($opts->number_of_results ?? "10") ?>"
                                   class="rounded-lg border border-zinc-300 bg-white px-3 py-2 outline-none focus:border-accent-500 focus:ring-2 focus:ring-accent-500/40 dark:border-zinc-700 dark:bg-zinc-800">
                        </label>

                        <label class="flex flex-col gap-1 text-sm sm:col-span-2">
                            <span class="font-medium text-zinc-600 dark:text-zinc-300"><?php printtext("settings_language");?></span>
                            <select name="language" class="rounded-lg border border-zinc-300 bg-white px-3 py-2 outline-none focus:border-accent-500 focus:ring-2 focus:ring-accent-500/40 dark:border-zinc-700 dark:bg-zinc-800">
                            <?php
                               $languages = json_decode(file_get_contents("static/misc/languages.json"), true);
                               $options = "<option value=\"\" " . (!isset($opts->language) ? "selected" : "") . ">Any</option>";
                               foreach ($languages as $lang_code => $language) {
                                   $name = $language["name"];
                                   $selected = $opts->language == $lang_code ? "selected" : "";
                                   $options .= "<option value=\"$lang_code\" $selected>$name</option>";
                               }
                               echo $options;
                            ?>
                            </select>
                        </label>
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="disable_special" <?php echo $opts->disable_special ? "checked" : ""; ?>
                               class="h-4 w-4 accent-accent-600">
                        <?php printtext("settings_special_disabled");?>
                    </label>
                    <p class="-mt-2 text-xs text-zinc-500 dark:text-zinc-400"><?php printtext("settings_special_warning");?></p>
                </section>

                <section class="flex flex-col gap-4 border-t border-zinc-200 pt-8 dark:border-zinc-800">
                    <div>
                        <h2 class="text-lg font-semibold"><?php printtext("settings_frontends");?></h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400"><?php printtext("settings_frontends_description");?></p>
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="disable_frontends" <?php echo $opts->disable_frontends ? "checked" : ""; ?>
                               class="h-4 w-4 accent-accent-600">
                        <?php printtext("settings_frontends_disable");?>
                    </label>

                    <div class="grid gap-3 sm:grid-cols-2">
                          <?php
                               foreach($opts->frontends as $frontend => $data)
                               {
                                    echo '<div class="flex items-center gap-2">';
                                    echo '<a class="w-40 shrink-0 text-sm font-medium text-accent-600 hover:underline dark:text-accent-400" href="' . $data["project_url"] . '" target="_blank">' . ucfirst($frontend) . '</a>';
                                    echo '<input type="text" name="' . $frontend . '" placeholder="Replace ' . $data["original_name"] . '" value="' . htmlspecialchars($opts->frontends["$frontend"]["instance_url"] ?? "") . '"';
                                    echo ' class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-1.5 text-sm outline-none placeholder:text-zinc-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-500/40 dark:border-zinc-700 dark:bg-zinc-800">';
                                    echo '</div>';
                               }
                          ?>
                    </div>
                </section>

                <div class="flex gap-3">
                  <button type="submit" name="save" value="1"
                          class="rounded-full bg-accent-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-accent-700 focus:outline-none focus:ring-2 focus:ring-accent-500/40"><?php printtext("settings_save");?></button>
                  <button type="submit" name="reset" value="1"
                          class="rounded-full border border-zinc-300 px-5 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:bg-zinc-100 focus:outline-none focus:ring-2 focus:ring-accent-500/40 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"><?php printtext("settings_reset");?></button>
                </div>
            </form>
        </div>

<?php require_once "misc/footer.php"; ?>
