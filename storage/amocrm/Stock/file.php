<?php
header('Access-Control-Allow-Origin: *');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once __DIR__ . '/../../../../lib/log/LogJSON.php';
require_once __DIR__ . '/lib/Stock.php';
require_once __DIR__ . '/../lib/Factory.php';
$log = new LogJSON('OOO', 'files');

use Storage\Factory;


require_once __DIR__ . '/lib/Stock.php';


$FILES = $_FILES;

if ($FILES) {
    $data_file = $FILES['files']['name'];
    $array_file_data = preg_split('[&]', $data_file);
    $it_array = array();
    foreach ($array_file_data as $el) {
        $str_val = explode('=', $el);
        $it_array[$str_val[0]] = str_replace('|', '/', $str_val[1]);
    }
    $log->add('$it_array', $it_array);
    $log->add('SEVER', $_SERVER);

    $account_id = $it_array['account_id'];
    $name_file = $it_array['name_file'];
    $product_id = $it_array['product_id'];
    $tmp_name = $FILES['files']['tmp_name'];
    \Storage\Factory::initById(\Storage\WIDGET_NAME, $account_id, $log);
    echo Factory::getFiles()->saveProductsFile($name_file, $product_id, $tmp_name);

}