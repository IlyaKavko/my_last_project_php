<?php
header('Access-Control-Allow-Origin: *');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . '/lib/LogTask.php';
require_once __DIR__ . '/lib/Planner/Planner.php';
require_once __DIR__ . '/lib/Settings/Settings.php';
require_once __DIR__ . '/lib/Notes/Notes.php';
require_once __DIR__ . '/../autocall/bitrix/Controller/Bitrix_Handler_Hook.php';
require_once __DIR__ . '/../autocall/amocrm/Controller/Hook.php';

$logger = LogTask::create_log("AutoTask", "Plainer");
//$logger->add('Time', time());
//die();


Plainer::setLog($logger);
//require_once __DIR__ . '/../autocall/amocrm/Controller/Hook.php';


try {
    $notes = Planner::getPlanners();
    $logger->add('$notes', $notes);

    $task = array();
    if (count($notes)) {
        foreach ($notes as $item) {
            $_Notes = new Notes($item['account_id']);
            $data_notes = $_Notes->get($item['task_id']);
	        $data_notes['type_CRM'] = $item['type_CRM'];
            array_push($task, $data_notes);

        }
    }

    Plainer::RUN($task);
    $logger->add('$task', $task);
} catch (\Exception $exception) {
    $logger->send_error($exception);
}


class Plainer
{
    static $logger;

    static function RUN(array $array)
    {

        if (count($array) > 0) {
            foreach ($array as $task) {
                $member_id = $task['account_id'];
                $element_id = $task['essence_id'];
                $responsible_id = $task['responsible_id'];
                $text = $task['text'];
                $task_id = $task['task_id'];
                $element_type = intval(($task['essence_type']));
                LogTask::LOG('$task', $task);
                switch ($task['type_CRM'])
                {
	                case 'amo':
                        $_settings = new Settings($member_id);
                        $SET = $_settings->get();
                        $subdomain = $SET['subdomain'];
                        $element_id == 0 ? \Autocall\Amocrm\Hook::AutoTask_ZERO_init($subdomain, $text, $task_id, self::getLog()) :
			                \Autocall\Amocrm\Hook::AutoTaskInit($subdomain, $element_type, $element_id, $task_id, self::getLog());
		            break;
	                case 'b24':
						\Autocall\Bitrix\Bitrix_Handler_Hook::AutoTaskInit($member_id,$element_type,$element_id,$responsible_id,$text,self::getLog());
	                break;
                }

            };
        }
    }

    static function setLog($log)
    {
        self::$logger = $log;
    }

    static function getLog()
    {
        return self::$logger;
    }
}

