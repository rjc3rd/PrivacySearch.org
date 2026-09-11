<div class="fixed bottom-0 left-0 flex w-full flex-wrap justify-center gap-x-6 gap-y-1 border-t border-zinc-200 bg-zinc-50/90 px-4 py-3 text-sm text-zinc-500 backdrop-blur-sm dark:border-zinc-800 dark:bg-zinc-900/90 dark:text-zinc-400">
    <a class="hover:text-accent-600 dark:hover:text-accent-400" href="https://proxysearch.org">ProxySearch</a>
    <a class="hover:text-accent-600 dark:hover:text-accent-400" href="https://github.com/rjc3rd/PrivacySearch.org" target="_blank"><?php printtext("source_code_link");?></a>
    <a class="hover:text-accent-600 dark:hover:text-accent-400" href="/settings.php"><?php printtext("settings_link");?></a>
    <?php if(!$opts->disable_api) {
        echo '<a class="hover:text-accent-600 dark:hover:text-accent-400" href="/api.php" target="_blank">', printtext("api_link"), '</a>';
    } ?>
</div>
</body>
</html>
