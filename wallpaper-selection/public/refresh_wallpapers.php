<?php

set_time_limit(0);

require_once('config.php');

require_once(INCLUDE_DIRECTORY . 'functions.php');
require_once(INCLUDE_DIRECTORY . 'database.php');

// require_once(INCLUDE_DIRECTORY . 'email_reader.php');
// retrieve_wallpapers_from_mailbox();

require_once(INCLUDE_DIRECTORY . 'WallpapersFetcher.class.php');

$wallpaper_fetcher = new WallpapersFetcher(ROOT_PROJECT_PATH . 'source_wallpapers');
$wallpaper_fetcher->fetch();

if ($wallpaper_fetcher->areThereErrors()) {
    $wallpaper_fetcher->printErrors();
} else {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}