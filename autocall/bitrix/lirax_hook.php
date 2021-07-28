<?php

namespace Autocall\Bitrix;

use LogJSON;

header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../constants.php';

//$re = array(
//    "subdomain" => "pragma.bitrix24.by",
//      "id_hook" => "missed",
//      "member_id" => "f0d76bbee027d17b3acc9bde751ee14d",
//      "ani" => "375292630377",
//      "dnis" => "375291434080",
//      "date" => "2021-05-24 18:03:36",
//      "duration" => "0",
//      "name" => "Алексей_не_нюхай_бебру"
//);

$logger = new LogJSON($_REQUEST['subdomain'], \Lirax\WIDGET_NAME, 'HOOK');
$logger->set_container('');
//if ($_REQUEST['subdomain'] == "pragmaintegrations")
//    die();


$logger->add('$_REQUEST', $_REQUEST);
require_once __DIR__ . '/Controller/Bitrix_missed.php';
require_once __DIR__ . '/Controller/Bitrix_adopted.php';
require_once __DIR__ . '/Controller/Bitrix_outgoing.php';



//new Bitrix_missed($re, $logger);

try {
    if (isset($_REQUEST['subdomain'])) {
        switch ($_REQUEST['id_hook']) {
            case 'missed':
                new Bitrix_missed($_REQUEST, $logger);
                break;
             case 'adopted':
                new Bitrix_adopted($_REQUEST, $logger);
                break;
            case 'outgoing':
                new Bitrix_outgoing($_REQUEST, $logger);
                break;

        }
    }
} catch (\Exception $exception)
{
    $logger->send_error($exception);
}


