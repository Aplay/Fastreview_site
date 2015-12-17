<?php
date_default_timezone_set('UTC');
define('SETTINGS_ADMINPATH','admin_fastreview');
//error_reporting(0);
$subdomain = array_pop((explode(".",$_SERVER['HTTP_HOST'])));
if ($subdomain == 'lab') {
	// change the following paths if necessary
	
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	// remove the following lines when in production mode
	defined('YII_DEBUG') or define('YII_DEBUG',true);
	// specify how many levels of call stack should be shown in each log message
	defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
    define('SETTINGS_SERVERNAME','fastreview.lab');
    $yii=dirname(__FILE__).'/../../../framework/yii-1.1.16/yii.php';
    $config=dirname(__FILE__).'/../protected/config/dev.php';
} else {
    define('SETTINGS_SERVERNAME','fastreview.ru');
    $yii = dirname(__FILE__) . '/../../framework/yii-1.1.16/yii.php';
    $config=dirname(__FILE__).'/../protected/config/main.php';
    
}
if (!defined('YII_DEBUG') || !YII_DEBUG) {

    error_reporting(0);
    ini_set('display_errors', false);

    ini_set("xdebug.default_enable", 0);
    ini_set("xdebug.profiler_enable", 0);
    ini_set("xdebug.remote_autostart", 0);
    ini_set("xdebug.remote_enable", 0);

    if (function_exists('xdebug_disable'))
        xdebug_disable();
}
require_once($yii);

Yii::createWebApplication($config)->run();
// Yii::app()->cache->flush();