<?php
header('Access-Control-Allow-Origin: *');

class PlanerBitrix
{
	static $loger;

	static function RUN (array $array)
	{
		if (count($array) > 0 )
		{
			foreach ($array as $task)
			{
				$member_id = $task['account_id'];
				$essence_id = $task['essence_id'];
				$text = $task['text'];
				$task_id = $task['task_id'];
				$essence_type = intval($task['essence_type=']);
//				Autocall\Bitrix\Bitrix_Handler_Hook::;
			}
		}
	}
}