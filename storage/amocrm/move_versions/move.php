<?php

namespace Storage\Move;

require_once __DIR__ . '/../lib/Factory.php';
require_once __DIR__ . '/ImportsUpdater.php';
require_once __DIR__ . '/ProductsExportsUpdater.php';
require_once __DIR__ . '/ProductsImportsUpdater.php';
require_once __DIR__ . '/StoresUpdater.php';

$logger = new \LogJSON('MOVE', 'MOVE', '');
$logger->set_container('move');

try {
    ProductsImportsUpdater::update();
    ImportsUpdater::update();
    ProductsExportsUpdater::update();
    StoresUpdater::update();
} catch(\Exception $exception) {
    $logger->send_error($exception);
    echo 'ERROR: ' . $exception->getMessage();
}