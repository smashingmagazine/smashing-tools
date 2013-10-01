<?php

/**
 * Project wide constants, containing all the
 * necessary data and credentials.
 *
 * This file should be kept a secret!
 *
 * @author     Vitaly's Father <vitalysfather@smashing-media.com>
 * @author     Frédéric Maquin <frederic@smashingmagazine.com>
 */

date_default_timezone_set('Europe/Berlin');

/**
 * Absolute path to the project root.
 * Mostly Used to define the include directory.
 */
define('ROOT_PROJECT_PATH',       realpath(dirname(__FILE__)) . '/');

/**
 * Include directory, pretty straightforward.
 */
define('INCLUDE_DIRECTORY',       ROOT_PROJECT_PATH . 'includes/');

/* Theme directory, once more, pretty straightforward. */
define('THEME_DIRECTORY',         ROOT_PROJECT_PATH . 'theme/');

/* Directory where wallpapers will be stored. */
define('WALLPAPERS_DIRECTORY',    ROOT_PROJECT_PATH . 'wallpapers/');

/* Directory where the SQLite databse is stored */
define('DATABASE_DIRECTORY',      ROOT_PROJECT_PATH . 'database/');

/* Assets directories, pretty straightforward as well. */
define('ASSETS_DIRECTORY',        'assets/');
define('STYLESHEETS_DIRECTORY',   ASSETS_DIRECTORY . 'stylesheets/');
define('JAVASCRIPTS_DIRECTORY',   ASSETS_DIRECTORY . 'javascripts/');
define('IMAGES_DIRECTORY',        ASSETS_DIRECTORY . 'images/');

/*********** EMAIL ACCOUNT ***********/

define('POP_MAILBOX',    '{mailserv.regfish.com:143}');
define('POP_USERNAME',   '43738-0017');
define('POP_PASSWORD',   '678hIgfgztuzi56ffghr');

