<?php


namespace Storage;

use LogJSON;
use Stock;

require_once __DIR__ . '/../lib/Factory.php';
require_once __DIR__ . '/../../../../lib/log/LogJSON.php';
require_once __DIR__ . '/lib/Stock.php';


$log_writer = new LogJSON('pragmadev.amocrm.ru', WIDGET_NAME, 'test');


$referrer = 'pragmadev.amocrm.ru';

//$re = array(
//	'flag' => 'section_update',
//	'title' => 'Аня где мои капкейки123',
//	'coming_id' => 255,
//	'specs' => [ [ 'id' => 88, 'title' => 'Они55' ], [ 'id' => 89, 'title' => 'Должны' ], [ 'id' => 90, 'title' => 'Быть' ], [ 'id' => 91, 'title' => 'Очень' ], [ 'id' => 92, 'title' => 'Вкусные' ] ],
//	'specs_new' => ['не вкусно'],
//	'stores' => [ 42 ],
//	'account_id' => 28967662,
//	'referrer' => 'pragmadev'
//);

$re = array(
	'flag' => 'specifications_get',
	'category_id' => 255,
	'account_id' => 28967662,
	'referrer' => 'pragmadev'
);

Factory::init(WIDGET_NAME, $referrer, $log_writer);

$Stock = new Stock($referrer, $log_writer);

$data = $Stock->specifications_get($re);

var_dump($data);