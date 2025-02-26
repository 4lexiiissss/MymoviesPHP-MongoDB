<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

require_once(dirname(__FILE__) . '/class/myAuthClass.php');
require_once(dirname(__FILE__) . '/class/myDbClass.php');
require_once(dirname(__FILE__) . '/lib/mymoviesphp.lib.php');

$authorized = myAuthClass::is_auth();

if ($authorized == true) {
    include 'main.inc.php';
} else {
    include 'login.php';
}
