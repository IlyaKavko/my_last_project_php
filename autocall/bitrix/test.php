<?php


require_once __DIR__ . '/../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../constants.php';
require_once __DIR__ . '/Factory.php';
require_once __DIR__ . '/Controller/GetResponsible.php';
require_once __DIR__ . '/../pragma/modules/settings/LiraxAdditionallySettingsSchema.php';

$logger = new LogJSON('test', \Lirax\WIDGET_NAME, 'HOOK');
$logger->set_container('');

$id = 98;
try {
	$test = new \Autocall\Pragma\liraxAdditionallySettingsSchema($id);
	var_dump($test->getAllAdditionallySettings());
}catch (\Exception $exception)
{
	$logger->send_error($exception);
}
