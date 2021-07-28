<?php

namespace Autocall\Amocrm;
header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../constants.php';

$re = array(
	"subdomain" => "pragmadev",
	"id_hook" => "missed",
	"ani" => "375292630377",
	"dnis" => "375291434080",
	"date" => "2021-07-21 12:38:19",
	"duration" => "0",
	"name" => ""
);

$logger = new \LogJSON($re['subdomain'] . '.amocrm.ru', \Lirax\WIDGET_NAME, 'HOOK');
$logger->set_container('');
//if ($_REQUEST['subdomain'] == "pragmaintegrations")
//    die();


$logger->add('$_REQUEST', $re);
require_once __DIR__ . '/Controller/Missed.php';
require_once __DIR__ . '/Controller/Outgoing.php';
require_once __DIR__ . '/Controller/Adopted.php';


if (isset($re['subdomain'])) {
    switch ($re['id_hook']) {
        case 'missed':
            new Missed($re, $logger);
            break;
        case 'adopted':
            new Adopted($_REQUEST, $logger);
            break;
        case 'outgoing':
            new Outgoing($_REQUEST, $logger);
            break;
    }
}

