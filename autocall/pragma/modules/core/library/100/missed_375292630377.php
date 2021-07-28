<?php
sleep(43840);

$referrer = "pragma.bitrix24.by";

require_once __DIR__ . "/../../../../../../../lib/log/LogJSON.php";
require_once __DIR__ . "/../../../../../constants.php";
require_once __DIR__ . "/../../../../../bitrix/Controller/Bitrix_Handler_Hook.php";

$logger = new LogJSON($referrer, \Lirax\WIDGET_NAME, "HOOK_Sleep");
$logger->set_container("");

$REQUEST = '{"workflow_id":"60ddd75e0b7314.87976688","code":"robot","document_id":["crm","CCrmDocumentLead","LEAD_1505"],"document_type":["crm","CCrmDocumentLead","LEAD"],"event_token":"60ddd75e0b7314.87976688|A18255_82153_44440_67293|86TkryMNLYGczOERE0dRzqfd31b0w6Mt.e86f77e7bf5bf2037a64ac5be14282e8393c4b92603d2b9868e73437a7ea3853","properties":{"string":"\u041d\u0443 \u0438 \u0447\u0435 \u0442\u0435\u0431\u0435 \u043d\u0430\u0434\u043e \u043a\u043e\u0436\u0430\u043d\u044b\u0439 \u0443\u0431\u043b\u044e\u0434\u043e\u043a!"},"timeout_duration":"0","ts":"1625151326","auth":{"access_token":"6ee5dd60005223e2003e2364000000010000008c7605f848219c8a6516786d33601d2f","expires":"1625154926","expires_in":"3600","scope":"task,crm,user,placement,bizproc","domain":"pragma.bitrix24.by","server_endpoint":"https =>\/\/oauth.bitrix.info\/rest\/","status":"F","client_endpoint":"https =>\/\/pragma.bitrix24.by\/rest\/","member_id":"f0d76bbee027d17b3acc9bde751ee14d","user_id":"1","refresh_token":"5e640561005223e2003e236400000001000000e4788fc71a97c2465d13b1656955566e","application_token":"61ec1ae6e56800dac7170702dcdbc350"}}';
unlink(__FILE__);

(new \Autocall\Bitrix\Bitrix_Handler_Hook((array) json_decode($REQUEST, 1)))->run();
unlink(__FILE__);
