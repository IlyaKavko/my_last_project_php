<?php
header('Access-Control-Allow-Origin: *');


use Autocall\Bitrix\Bitrix_Handler_Hook;
use Autocall\Bitrix\Factory;

require_once __DIR__ . '/../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../constants.php';
require_once __DIR__ . '/Controller/Bitrix_Handler_Hook.php';

//$re = array(
//	"workflow_id" => "60d59296d07174.44889651",
//	"code" => "robot",
//	"document_id" => array(
//		"crm",
//		"CCrmDocumentLead",
//		"LEAD_1505"
//	),
//	"document_type" => array(
//		"crm",
//		"CCrmDocumentLead",
//		"LEAD"
//	),
//	"event_token" => "60d59296d07174.44889651|A18255_82153_44440_67293|an9v1UouHrgBvzfl7Yuh5ELuJX24EWG0.1cf49bb47bb22b075e38ac5f1ba4ed11a9fba29d60b3593b018ff49f05f5c6c9",
//	"properties" => array(
//		"string" => "Осуществляем звонок по новой заявке Леша лижет жаб! на номер 375 29 263 03 77"
//	),
//	"use_subscription" => "Y",
//	"timeout_duration" => "0",
//	"ts" => "1624609430",
//	"auth" => array(
//		"access_token" => "a6a0d560005223e2003e2364000000010000003f367e83b52a02856380c1b0e3d5ecc8",
//		"expires" => "1624613030",
//		"expires_in" => "3600",
//		"scope" => "task,crm,user,placement,bizproc",
//		"domain" => "pragma.bitrix24.by",
//		"server_endpoint" => "https://oauth.bitrix.info/rest/",
//		"status" => "F",
//		"client_endpoint" => "https://pragma.bitrix24.by/rest/",
//		"member_id" => "f0d76bbee027d17b3acc9bde751ee14d",
//		"user_id" => "1",
//		"refresh_token" => "961ffd60005223e2003e2364000000010000006d9513604ac0c8e266b8fe67d8f8a34a",
//		"application_token" => "61ec1ae6e56800dac7170702dcdbc350"
//	)
//);

$logger = new LogJSON($_REQUEST['auth']['domain'], \Lirax\WIDGET_NAME, 'HOOK');
$logger->set_container('');

$logger->add('Robot', $_REQUEST);

try {
    if ($_REQUEST)
    {
	    Factory::init($_REQUEST['auth']['member_id'], $logger);
        (new Bitrix_Handler_Hook($_REQUEST))->run();
    }
}catch (\Exception $exception){
    $logger->send_error($exception);
}

