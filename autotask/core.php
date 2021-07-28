<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/lib/LogTask.php';
$logger = LogTask::create_log(get_referer($_POST), "hook");

require_once __DIR__ . '/lib/Task.php';

$POST = $_POST;
$logger->add('$POST', $POST);


$task = $POST['task'];
$Method = key($task);

$subject = $task[$Method][0];
$subject['subdomain'] = get_referer($POST);
$Task = new Task(get_account_id($POST), $subject);


switch ($Method) {
    case 'add':
        $Task->add();
        break;
    case 'update':
        if (!(bool)($subject['status'])) {
            $Task->add();
        }
        break;
    case 'delete':
        $Task->delete();
        break;

}


function get_referer($POST)
{
    return $POST['account']['subdomain'] ?? 'undefined';
}

function get_account_id($POST): int
{
    return (int)$POST['account']['id']  ?? 'undefined';
}