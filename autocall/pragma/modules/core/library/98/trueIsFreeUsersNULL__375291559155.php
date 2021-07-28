<?php
sleep(60);

require_once __DIR__ . "/../../../../../amocrm/Controller/Outgoing/File.php";
$referrer = 'pragmadev';
$Phone = 375291559155;
$responsibility = 6924280;
$n = 1;
unlink(__FILE__);
$FILE = new \Autocall\Amocrm\File ($referrer, $Phone);
$FILE->falseIsFreeUsers($responsibility, $n);

