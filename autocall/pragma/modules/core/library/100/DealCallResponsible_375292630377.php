<?php
sleep(60);

require_once __DIR__ . "/../../../../../../../lib/log/LogJSON.php";
require_once __DIR__ . "/../../../../../constants.php";
require_once __DIR__ . "/../../../../../bitrix/Controller/Bitrix_Handler_Hook.php";
$Count_Call = 4;
$REQUEST = '{"document_id":["crm","CCrmDocumentDeal","DEAL_4325"],"auth":{"domain":"pragma.bitrix24.by","client_endpoint":"https:\/\/pragma.bitrix24.by\/rest\/","server_endpoint":"https:\/\/oauth.bitrix.info\/rest\/","member_id":"f0d76bbee027d17b3acc9bde751ee14d"}}';
unlink(__FILE__);
(new \Autocall\Bitrix\Bitrix_Handler_Hook((array) json_decode($REQUEST, 1)))->run($Count_Call);

