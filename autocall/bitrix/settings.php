<?php
header('Access-Control-Allow-Origin => *');
//
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

$re = array(
	'flag' => 'get_settings',
	'typeCRM' => 'bitrix',
	'ID_ACCOUNT' => 'f0d76bbee027d17b3acc9bde751ee14d',
	'referrer' => 'pragma.bitrix24.by',
);

require_once __DIR__ . '/../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../constants.php';
$logger = new LogJSON(get_referer(), \Lirax\WIDGET_NAME, 'settings');
$logger->set_container('');


try{
	require_once __DIR__ . '/../pragma/Controller/AutoCallSettings.php';
	require_once __DIR__ . '/../LogAutoCall.php';
    (new AutoCallSettings($re));
} catch (\Exception $exception) {
    $logger->send_error($exception);
}

function get_referer (){
    return 'twat';
}