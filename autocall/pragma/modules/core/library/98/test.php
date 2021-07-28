<?php

require_once __DIR__ . "/../../../../../amocrm/Controller/Outgoing/File.php";
$referrer = 'pragmadev';
$Phone = 375291559155;
$responsibility = 6924280;
$n = 0;

$FILE = new \Autocall\Amocrm\File ($referrer, $Phone);
$FILE->falseIsFreeUsers($responsibility, $n);
