<?php

/**
 * Environment file.
 * 
 * Authors are reported in case you need help or 
 * someone to blame. Please remember to include yourself!
 * 
 * @author     Frédéric Maquin <frederic.maquin@smashingmagazine.com>
 * @copyright  2013 - Smashing Magazine
 * @version    0.1
 */

// Very simple. You call the script from localhost or *.dev,
// we are in dev mode. Otherwise, we are in production mode.

$extension = substr($_SERVER['HTTP_HOST'], strrpos($_SERVER['HTTP_HOST'], '.') + 1);

if ($_SERVER['HTTP_HOST'] == 'localhost' || $extension == 'dev') {
    define('ENVIRONMENT', 'DEVELOPMENT');
} else {
    define('ENVIRONMENT', 'PRODUCTION');
}

?>