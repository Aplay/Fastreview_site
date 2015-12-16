<?php
error_reporting(E_ALL);

ini_set('display_errors', true);

setlocale(LC_ALL,'ru_RU.UTF-8');
setlocale(LC_NUMERIC, "C");
// change the following paths if necessary
$yiic = dirname(__FILE__) . '/../../framework/yii-1.1.16/yiic.php';
$config = dirname(__FILE__) . '/config/console.php';

require_once($yiic);
