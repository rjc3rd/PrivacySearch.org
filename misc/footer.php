<div class="footer-container">
    <a href="https://proxysearch.org">ProxySearch</a>
    <a href="https://github.com/Ahwxorg/LibreY" target="_blank"><?php printtext("source_code_link");?></a>
    <a href="./instances.php" target="_blank"><?php printtext("instances_link");?></a>
    <a href="./settings.php"><?php printtext("settings_link");?></a>
    <?php if(!$opts->disable_api) {
        echo '<a href="./api.php" target="_blank">', printtext("api_link"), '</a>';
    } ?>
</div>
</body>
</html>
