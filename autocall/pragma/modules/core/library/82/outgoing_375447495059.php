<?php
sleep(33107);

require_once __DIR__ . "/../../../../../amocrm/Controller/Outgoing.php";
require_once __DIR__ . "/../../../../../../../lib/log/LogJSON.php";
require_once __DIR__ . "/../../../../../constants.php";


$referrer = 'obivaem';
$number_phone = 375447495059;
$serializeData = 'a:9:{s:7:"id_hook";s:8:"outgoing";s:9:"subdomain";s:7:"obivaem";s:3:"ani";s:7:"1122673";s:3:"ext";s:3:"221";s:4:"dnis";s:12:"375447495059";s:4:"date";s:19:"2021-06-21 20:44:28";s:3:"out";s:1:"1";s:8:"duration";s:2:"52";s:4:"name";s:14:"Надежда";}';

$logger = new LogJSON($referrer . ".amocrm.ru", \Lirax\WIDGET_NAME, "HOOK");
$logger->set_container("");
$unserialezeData = unserialize($serializeData);
unlink(__FILE__);

(new \Autocall\Amocrm\Outgoing($unserialezeData, $logger));

