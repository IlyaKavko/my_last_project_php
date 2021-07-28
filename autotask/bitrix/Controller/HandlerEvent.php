<?php


namespace AutoTask\Bitrix;

use function Calculator\setExpressions;

require_once __DIR__ . '/GetTask.php';
require_once __DIR__ . '/../../lib/Task.php';

class HandlerEvent
{
	static private $REQUEST;
	static private $member_id;
	static private $TYPE_REQUEST;
	static public string $task_id;

	public function __construct( $REQUEST )
	{
		self::$REQUEST = $REQUEST;
		self::$member_id = $REQUEST['auth']['member_id'];
		self::$TYPE_REQUEST = $REQUEST['event'];
	}

	static function setTask(int $id ): void
	{
		self::$task_id = $id;
	}

	static function task_model( array $get_task ): GetTask
	{
		return new GetTask($get_task);
	}

	static function task( $member_id, $get_task ): \Task
	{
		return new \Task($member_id, $get_task);
	}

	function run(): void
	{
		try {

			switch ( self::$TYPE_REQUEST ) {
				case 'ONCRMACTIVITYADD':
				case 'ONCRMACTIVITYUPDATE':
				$get_task = Factory::get_way('crm.activity.get', array( 'id' => self::$REQUEST['data']['FIELDS']['ID'] ));
				$taskModel = self::task_model($get_task['result']);
				$Task = self::task(self::$member_id, $get_task['result']);
					if ( !$taskModel->getCompleted() ) {
						$Task->add($taskModel->getDeadline());
					} else {
						$Task->delete();
					}
					break;
				case 'ONCRMACTIVITYDELETE':
					$Task = self::task(self::$member_id, ["ID" => self::$task_id]);
					$Task->delete();
					break;
			}

		} catch ( \Exception $exception ) {
			Factory::LogWriter()->send_error($exception);
		}
	}
}