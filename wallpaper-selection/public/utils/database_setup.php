<?php

// Getting the project-wide config constants
require(dirname(__FILE__) . '/../config.php');

$sqlite_error = "";

try {
    
    if (file_exists(DATABASE_DIRECTORY . 'wallpaper_selection.db')) {
        unlink(DATABASE_DIRECTORY . 'wallpaper_selection.db');
    }
    
    if (!file_exists(DATABASE_DIRECTORY . 'wallpaper_selection.db')) {
    
        // Database Handler
        $db = new SQLite3(DATABASE_DIRECTORY . 'wallpaper_selection.db');

        $create_database_request = "CREATE TABLE wallpapers (".
                                       "id                    INTEGER PRIMARY KEY AUTOINCREMENT," .
                                       "friendly_month        TEXT,".
                                       "month                 TEXT,".
                                       "theme                 TEXT,".
                                       "parsed_theme          TEXT,".
                                       "shortcut              TEXT,".
                                       "designer              TEXT,".
                                       "email                 TEXT,".
                                       "url                   TEXT,".
                                       "country               TEXT,".
                                       "resolution            TEXT,".
                                       "calendar              TEXT,".
                                       "type                  TEXT,".
                                       "description           TEXT,".
                                       "wordpress_description TEXT,".
                                       "rejected              INTEGER,".
                                       "list_index            INTEGER,".
                                       "mail_id               TEXT".
                                   ");";
    
        $results = $db->query($create_database_request);
    }

} catch (Exception $e) {
    echo $e->getMessage();
}