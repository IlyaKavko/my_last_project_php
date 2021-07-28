<?php
header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/lib/LogTask.php';
require_once __DIR__ . '/lib/Settings/Settings.php';

$logger = LogTask::create_log(get_referer($_POST), "settings");

$POST = $_POST;

$account_id = get_account_id($POST);
$subdomain = get_referer($POST);

$_settings = new Settings($account_id);


echo json_encode(match ($POST['type']) {
    'save' => $_settings->save($POST),
    'get' => $_settings->get(),
});
die();


function get_referer($POST)
{
    return $POST['referrer'] ?? 'undefined';
}

function get_account_id($POST): int
{
    return (int)$POST['account_id'] ?? 'undefined';
}