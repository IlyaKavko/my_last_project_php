<?php

require_once __DIR__ . "/../../../../../amocrm/Controller/Missed.php";
require_once __DIR__ . "/../../../../../../../lib/log/LogJSON.php";
require_once __DIR__ . "/../../../../../constants.php";

$referrer = 'pragmadev';
$Phone = 375292630377;
$N = 8;
$logger = new LogJSON($referrer . ".amocrm.ru", \Lirax\WIDGET_NAME, "HOOK");
$logger->set_container("");

Autocall\Amocrm\Missed::initMissing($referrer, $logger);
Autocall\Amocrm\Missed::isFreeUsersAMOTRUE($referrer, $Phone, $N);

