<?php
header('Access-Control-Allow-Origin => *');

use \AutoTask\Bitrix\Factory;
use \AutoTask\Bitrix\HandlerEvent;

require_once __DIR__ . '/../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../lib/LogTask.php';
require_once __DIR__ . '/../constants.php';
require_once __DIR__ . '/Factory.php';
require_once __DIR__ . '/Controller/HandlerEvent.php';

$logger = LogTask::create_log(get_referer(), "hook");
$logger->set_container('');
$logger->add('$REQUEST', $_REQUEST);

if ( $_REQUEST ) {
	HandlerEvent::setTask($_REQUEST['data']['FIELDS']['ID']);
	try {
		Factory::init($_REQUEST['auth']['member_id'], $logger);
		( new HandlerEvent($_REQUEST) )->run();

	} catch ( \Exception $exception ) {
		$logger->send_error($exception);
	}
}
function get_referer()
{
	return $_REQUEST['auth']['domain'];
}
