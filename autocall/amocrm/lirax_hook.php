<?php

namespace Autocall\Amocrm;

use LogJSON;

header('Access-Control-Allow-Origin: *');
//$re = array(
//	"event" => array(
//		"type" => "14",
//		"type_code" => "lead_status_changed",
//		"data" => array(
//			"id" => "14073841",
//			"element_type" => "2",
//			"status_id" => "35597569",
//			"pipeline_id" => "3418789"
//		),
//		"time" => "1626250536"
//	),
//	"action" => array(
//		"code" => "pmliraxdev",
//		"settings" => array(
//			"widget_info" => array(
//				"id" => "771646",
//				"code" => "pmliraxdev",
//				"name" => "pmLiraxDev"
//			),
//			"optional_conditions" => array(
//				"main_event" => "14"
//			),
//			"row" => "1",
//			"created_by" => "6924280"
//		)
//	),
//	"subdomain" => "pragmadev",
//	"account_id" => "28967662"
//);
require_once __DIR__ . '/../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../constants.php';
$logger = new LogJSON($_REQUEST['subdomain'] . '.amocrm.ru', \Lirax\WIDGET_NAME, 'AMO_HOOK');
$logger->set_container('');
$logger->add('time', date('l jS \of F Y h:i:s A'));
$logger->add('$_REQUEST', $_REQUEST);
require_once __DIR__ . '/Controller/Hook.php';
$REQUEST = $_REQUEST;
$subdomain = $REQUEST['subdomain'];
try {
    if ($REQUEST) {
        if (isset($REQUEST['account_id'])) {

            (new Hook($REQUEST, $subdomain, $logger))->run();
        };
    }
} catch (\Exception $exception) {
    $logger->send_error($exception);
}

