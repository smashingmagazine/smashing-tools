<?php

require_once('config.php');

require_once(INCLUDE_DIRECTORY . 'functions.php');
require_once(INCLUDE_DIRECTORY . 'database.php');

if (isset($_POST['wallpapers'])) {
    $wallpapers = json_decode($_POST['wallpapers']);
    
    foreach($wallpapers as $wallpaper) {
        $query = "UPDATE wallpapers SET ";
        
        // TODO: Metaprog       
        if (property_exists($wallpaper, 'wordpress_description')) {
            $query .= "wordpress_description = '" . SQLite3::escapeString($wallpaper->wordpress_description);
        }
        
        if (property_exists($wallpaper, 'list_index')) {
            $query .= "', list_index = '" . SQLite3::escapeString($wallpaper->list_index);
        }
        
        if (property_exists($wallpaper, 'rejected')) {
            $query .= "', rejected = '" . SQLite3::escapeString($wallpaper->rejected);
        }
        
        if (property_exists($wallpaper, 'wallpaper_id')) {
            $query .= "' WHERE id = '" . SQLite3::escapeString($wallpaper->wallpaper_id) . "';";
        }
        
        $db->query($query);

    }
    echo '{code: "OK"}';
} else {
    echo '{code: "BAD REQUEST"}';
}