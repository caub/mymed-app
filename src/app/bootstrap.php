<?php


error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', TRUE);

define('APP_NAME', "myMap");
define('BACKEND_URL', 'http://138.96.242.20/mymed_backend');
//maybe put them in a config file

require('PhpConsole.php');
PhpConsole::start();
function debug_r($obj) {
	debug(print_r($obj, true));
}
debug('e');

use app\routing\Router;
use \lib\jsonloader\JsonToArray;

require 'autoload.php';
require __DIR__.'/../lib/autoload.php';


//require 'routing/Router.php';

session_start();

$uri =  isset( $_SERVER['SCRIPT_URL'] ) ? $_SERVER['SCRIPT_URL'] : '/'.APP_NAME;

if (isset($_GET['registration']) && $_GET['registration'] == "ok" ) { //nasty redirection... for registration mails
	header('Location:/'.APP_NAME.'/registerValidate?accessToken='.$_GET['accessToken']);
	exit();
}

$config = JsonToArray::fetchConfig(  __DIR__ .'/config/routes.json' );

$router = new Router( $config );

$rbac = JsonToArray::fetchConfig(  __DIR__ .'/config/rbac.json' ); /*loads role based access control*/
$userRole = isset($_SESSION['user']) ? (isset($_SESSION['user']->role[APP_NAME])?$_SESSION['user']->role[APP_NAME]:2) : 0;
// the 2 above is for demo, after $_SESSION['user']->role[APP_NAME] will be always set in profiles

$router->route($uri, $rbac[$userRole]);


?>