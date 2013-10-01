<?php

/**
 * The App's entry point.
 * 
 * Load everythings, setup few things,
 * and we're on the road baby!
 *
 * @author     Vitaly's Father <vitalysfather@smashing-media.com>
 * @author     Frédéric Maquin <frederic@smashingmagazine.com>
 */

// Getting the project-wide config constants
require_once('config.php');
	
// Getting the utility functions
require_once(INCLUDE_DIRECTORY . 'functions.php');

// Database handler
require_once(INCLUDE_DIRECTORY . 'database.php');

include(THEME_DIRECTORY . 'header.html.php');

$first_day_of_current_month = get_ISO_date_of_first_day_of_the_current_month();

$query_valid = "SELECT * FROM wallpapers " .
               "WHERE friendly_month >= '$first_day_of_current_month' " .
               "AND rejected = '0'" .
               "ORDER BY list_index ASC;";

$query_rejected = "SELECT * FROM wallpapers " .
                  "WHERE friendly_month >= '$first_day_of_current_month' " .
                  "AND rejected = '1'" .
                  "ORDER BY list_index ASC;";

$valid_wallpapers = get_nice_array_from_sqlite_set($db->query($query_valid));
$rejected_wallpapers = get_nice_array_from_sqlite_set($db->query($query_rejected));

include(THEME_DIRECTORY . 'index.html.php');

include(THEME_DIRECTORY . 'footer.html.php');

// require_once(INCLUDE_DIRECTORY . 'email_reader.php');
// retrieve_wallpapers_from_mailbox();
