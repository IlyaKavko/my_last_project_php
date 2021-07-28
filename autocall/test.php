<?php

require_once __DIR__ . '/../../lib/log/LogJSON.php';
require_once __DIR__ . '/amocrm/lib/Factory.php';

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$log_writer = new \LogJSON('pragmadev.amocrm.ru', 'PragmaStorage', 'test');

Factory::initById('pmLirax', 28967662, $log_writer);
$node = \Services\Factory::getNodesService()->findAmocrmNodeCode('pmLirax',"pragmadev");
$node->setShutdownTime(1692195091);