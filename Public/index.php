<?php

session_start();

date_default_timezone_set('America/Detroit');

define('MAVERICK_VERSION', 130);

define('DS',               DIRECTORY_SEPARATOR);

define('ROOT_PATH',        dirname(__DIR__) . DS);
define('MAVERICK_PATH',    $_SERVER['MAVERICK_ROOT'] . DS . MAVERICK_VERSION . DS);
define('APPLICATION_PATH', ROOT_PATH        . 'Application' . DS);
define('PUBLIC_PATH',      ROOT_PATH        . 'Public'      . DS);

require_once MAVERICK_PATH . 'Launch.php';

$maverick = \Maverick\Maverick::launch();
