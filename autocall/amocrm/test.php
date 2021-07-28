<?php

namespace Autocall\Amocrm;


ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . '/../../../lib/log/LogJSON.php';
require_once __DIR__ . '/Factory.php';

$log_writer = new \LogJSON('pragmadev.amocrm.ru', 'pmLirax', 'test');

Factory::amocrmInit('pragmadev.amocrm.ru', $log_writer);
$node = \Services\Factory::getNodesService()->findAmocrmNodeCode('pmliraxdev',"pragmadev");
$chek = $node->checkActive();

//$re = array(
//	"event" => array(
//		"type" => "14",
//		"type_code" => "lead_status_changed",
//		"data" => array(
//			"id" => "13778223",
//			"element_type" => "2",
//			"status_id" => "35361670",
//			"pipeline_id" => "3418789"
//		),
//		"time" => "1624608115"
//	),
//	"action" => array(
//		"code" => "devpmlirax",
//		"settings" => array(
//			"widget_info" => array(
//				"id" => "727507",
//				"code" => "devpmlirax",
//				"name" => "devPM_Lirax"
//			),
//			"optional_conditions" => array(
//				"main_event" => "14"
//			),
//			"row" => "1",
//			"created_by" => "6200425"
//		)
//	),
//	"subdomain" => "pragmadev",
//	"account_id" => "28967662"
//);
//use LogJSON;
//
//header('Access-Control-Allow-Origin: *');
//
//require_once __DIR__ . '/../../../lib/log/LogJSON.php';
//require_once __DIR__ . '/../constants.php';
//$logger = new LogJSON($re['subdomain'] . '.amocrm.ru', \Lirax\WIDGET_NAME, 'AMO_HOOK');
//$logger->set_container('');
//$logger->add('time', date('l jS \of F Y h:i:s A'));
//$logger->add('$_REQUEST', $re);
//require_once __DIR__ . '/Controller/Hook.php';
//$REQUEST = $re;
//$subdomain = $re['subdomain'];
//try {
//	if ($REQUEST) {
//		if (isset($REQUEST['account_id'])) {
//
//			(new Hook($REQUEST, $subdomain, $logger))->run();
//		};
//	}
//} catch (\Exception $exception) {
//	$logger->send_error($exception);
//}

