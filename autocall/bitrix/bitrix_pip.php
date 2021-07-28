<?php
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../constants.php';
//
$logger = new LogJSON(get_referer(), \Lirax\WIDGET_NAME, 'pip');
$logger->set_container('');

try
{
	require_once __DIR__ . '/../pragma/Factory.php';
	require_once __DIR__ . '/Factory.php';
}catch (\Exception $exception)
{
	$logger->send_error($exception);
}

$POST = $_POST;

if (!$POST)
    die();

$flag = $POST['flag'];
$account_id = $POST['account_id'];
$widget_code = $POST['widget_code'];

try {
    \AutoCall\Bitrix\Factory::init($account_id, $logger);

    $PIP = \Autocall\Bitrix\Factory::getPips();
    switch ($flag) {
        case 'save':
            $ID = $POST['id'];
            $CHECK = $POST['CHECK'];
            $IS_ALL = $POST['is_all'];
            switch ($IS_ALL){
                case 'true':
                    switch ($CHECK) {
                        case 'true':
                            foreach ($ID as $ID_ITEM)
                            {
                                $PIP->savePipe($ID_ITEM);
                            }
                            break;
                        case 'false' :
                            foreach ($ID as $ID_ITEM)
                            {
                                $PIP->deletePipe($ID_ITEM);
                            }
                            break;
                    }
                    break;
                case 'false':
                    switch ($CHECK) {
                        case 'true':
                            $PIP->savePipe($ID);
                            break;
                        case 'false' :
                            $PIP->deletePipe($ID);
                            break;
                    }
                    break;
            }
            break;
        case 'get':
            $data = $PIP->getPips();
            echo json_encode($data);
            die();
    }
} catch (\Exception $exception) {
    $logger->send_error($exception);
}

function get_referer(): string {
	$request = parse_url($_SERVER['HTTP_REFERER']);
	return $request['host'] || $request['DOMAIN'] ?? 'undefined';
}