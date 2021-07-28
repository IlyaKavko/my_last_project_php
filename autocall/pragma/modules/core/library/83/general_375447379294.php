<?php
sleep(300);

use Autocall\Amocrm\File;
require_once __DIR__ . "/../../../../../amocrm/Controller/Outgoing/File.php";
$referrer = "burs";
$Phone = 375447379294;
(new File ($referrer, $Phone));

unlink(__FILE__);
