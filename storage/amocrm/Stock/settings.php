<?php

use Storage\Factory;

header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../../CONSTANTS.php';
require_once __DIR__ . '/lib/Stock.php';
require_once __DIR__ . '/lib/Catalog.php';
require_once __DIR__ . '/../lib/Factory.php';


try{
    if ($_POST) {
        $POST = $_POST;
        $FLAG = $POST['flag'];
        $ref = $POST['referrer'] . '.amocrm.ru';
        $log_writer = new LogJSON($ref, \Storage\WIDGET_NAME, 'Settings');
        Factory::init(\Storage\WIDGET_NAME, $ref, $log_writer);
        $log_writer->add('$POST', $POST);

        $POST_s = json_encode($POST);
        $POST_d = json_decode($POST_s, true);
        switch ($FLAG) {
            case 'get_pipelines':
                $data = Factory::getAmocrmStorage()->getPipelinesOfAccount(); //получить все воронки
                echo json_encode($data);
                break;

            case 'get_statuses':
                $data['statuses'] = Factory::getAmocrmStorage()->getExportStatuses(); // получить статусы
                $data['fractional'] = Factory::getSettings()->getFractional();

                echo json_encode($data['statuses']);
                break;

            case 'save_settings':
                $links = $POST_d['data'];
                $fractional = $POST_d['fractional'];
                Factory::getAmocrmStorage()->setStatusLinks($links);
                Factory::getSettings()->setFractional($fractional);
                break;

            case 'get_settings':
                $data = Factory::getAmocrmStorage()->getStatusLinks();
                echo json_encode($data);
                break;

            case 'change_active_stock':
                $stock_id = $POST_d['stock_id'];
                Factory::getSettings()->setStock($stock_id);
                break;


            case 'get_all_settings':
            {
                $data['links'] = Factory::getAmocrmStorage()->getStatusLinks();
                $data['fractional'] = Factory::getSettings()->getFractional();
                $data['pipelines'] = Factory::getAmocrmStorage()->getPipelinesOfAccount();
                $data['statuses'] = Factory::getAmocrmStorage()->getExportStatuses();
                $data['active_stock'] = Factory::getSettings()->getStock();
                echo json_encode($data);
                die();
            }
        }
    }
} catch(\Exception $exception) {
    Factory::getLogWriter()->send_error($exception);
}