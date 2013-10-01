<?php

set_time_limit(0);

require_once('config.php');

require_once(INCLUDE_DIRECTORY . 'functions.php');
require_once(INCLUDE_DIRECTORY . 'database.php');

empty_database();

header('Location: ' . $_SERVER['HTTP_REFERER']);