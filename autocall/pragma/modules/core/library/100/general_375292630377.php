<?php
sleep(60);

use Autocall\Bitrix\File;
require_once __DIR__ . "/../../../../../bitrix/Controller/Outgoing/File.php";
$referrer = "pragma.bitrix24.by";
$member_id = "f0d76bbee027d17b3acc9bde751ee14d";
$Phone = 375292630377;
(new File ($referrer, $Phone, $member_id));

unlink(__FILE__);
