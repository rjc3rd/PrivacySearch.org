<?php
return (object) array(

    // Default language profile to be used on the website
    "language" => "en",

    // The default theme css to use
    "default_theme" => "dark",

    "preferred_engines" => array(
        "text" => "auto" // auto will automatically balance between all scrapers
        // "text" => "auto"  // auto is recommended
        // "text" => "duckduckgo"
        // "text" => "bing"
        // "text" => "yahoo"
    ),

    // Number of results to be shown on results page
    "number_of_results" => "20",

    "disable_bittorrent_search" => false,
    "bittorrent_trackers" => "&tr=http://nyaa.tracker.wf:7777/announce&tr=udp://open.stealth.si:80/announce&tr=udp://tracker.opentrackr.org:1337/announce&tr=udp://exodus.desync.com:6969/announce&tr=udp://tracker.torrent.eu.org:451/announce",

    "disable_hidden_service_search" => false,

    // how long in minutes to put google/other instances on cooldown if they aren't responding
    "request_cooldown" => "30",

    // how long in minutes to store results for in the cache
    "cache_time" => "20",

    // Disable requests to /api.php - HIGHLY recommended to keep this at false, this is what allows LibreY to still show results when Google/DuckDuckGo blocks the requests.
    "disable_api" => false,

    // whether to show where the result is from on the results page
    "show_result_source" => true,

    /*
            Preset privacy friendly frontends for users, these can be overwritten by users in the settings
            e.g.: Preset the invidious instance URL: "instance_url" => "https://inv.tux.pizza",
    */

    "frontends" => array(
        "invidious" => array(
            "instance_url" => "",
            "project_url" => "https://docs.invidious.io/instances",
            "original_name" => "YouTube",
            "original_url" => "youtube.com"
        ),
        "rimgo" => array(
            "instance_url" => "",
            "project_url" => "https://codeberg.org/video-prize-ranch/rimgo#instances",
            "original_name" => "Imgur",
            "original_url" => "imgur.com"
        ),
        "scribe" => array(
            "instance_url" => "",
            "project_url" => "https://git.sr.ht/~edwardloveall/scribe/tree/main/docs/instances.md",
            "original_name" => "Medium",
            "original_url" => "medium.com"
        ),
        "gothub" => array(
            "instance_url" => "",
            "project_url" => "https://codeberg.org/gothub/gothub#instances",
            "original_name" => "GitHub",
            "original_url" => "github.com"
        ),
        "nitter" => array(
            "instance_url" => "",
            "project_url" => "https://github.com/zedeus/nitter/wiki/Instances",
            "original_name" => "Twitter",
            "original_url" => "twitter.com"
        ),
        "redlib" => array(
            "instance_url" => "",
            "project_url" => "https://github.com/redlib-org/redlib-instances/blob/main/instances.md",
            "original_name" => "Reddit",
            "original_url" => "reddit.com"
        ),
        "proxitok" => array(
            "instance_url" => "",
            "project_url" => "https://github.com/pablouser1/ProxiTok/wiki/Public-instances",
            "original_name" => "TikTok",
            "original_url" => "tiktok.com"
        ),
        "wikiless" => array(
            "instance_url" => "",
            "project_url" => "https://github.com/Nangjing/wikiless",
            "original_name" => "Wikipedia",
            "original_url" => "wikipedia.org"
        ),
        "quetre" => array(
            "instance_url" => "",
            "project_url" => "https://github.com/zyachel/quetre#instances",
            "original_name" => "Quora",
            "original_url" => "quora.com"
        ),
        "libremdb" => array(
            "instance_url" => "",
            "project_url" => "https://github.com/zyachel/libremdb#instances",
            "original_name" => "IMDb",
            "original_url" => "imdb.com"
        ),
        "breezewiki" => array(
            "instance_url" => "",
            "project_url" => "https://docs.breezewiki.com/Links.html",
            "original_name" => "Fandom",
            "original_url" => "fandom.com"
        ),
        "anonymousoverflow" => array(
            "instance_url" => "",
            "project_url" => "https://github.com/httpjamesm/AnonymousOverflow#clearnet-instances",
            "original_name" => "StackOverflow",
            "original_url" => "stackoverflow.com"
        ),
        "suds" => array(
            "instance_url" => "",
            "project_url" => "https://git.vern.cc/cobra/Suds/src/branch/main/instances.json",
            "original_name" => "Snopes",
            "original_url" => "snopes.com"
        ),
        "biblioreads" => array(
            "instance_url" => "",
            "project_url" => "https://github.com/nesaku/BiblioReads#instances",
            "original_name" => "Goodreads",
            "original_url" => "goodreads.com"
        )
    ),

    // To send search requests through a proxy uncomment CURLOPT_PROXY and CURLOPT_PROXYTYPE:
    // CURLOPT_PROXYTYPE options:
    //     CURLPROXY_HTTP
    //     CURLPROXY_SOCKS4
    //     CURLPROXY_SOCKS4A
    //     CURLPROXY_SOCKS5
    //     CURLPROXY_SOCKS5_HOSTNAME

    "curl_settings" => array(
        // CURLOPT_PROXY => "ip:port",
        // CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:125.0) Gecko/20100101 Firefox/125.0", // For a normal Windows 10 PC running Firefox x64
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_WHATEVER,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP,
        CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTPS | CURLPROTO_HTTP,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_TIMEOUT => 3,
        CURLOPT_VERBOSE => false,
        CURLOPT_FOLLOWLOCATION => true
    )
);
