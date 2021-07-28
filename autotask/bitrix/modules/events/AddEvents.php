<?php
namespace AutoTask\Bitrix;
use \AutoTask\Bitrix\Factory;

require_once __DIR__ . '/../../Factory.php';

class AddEvents
{
	static function EventsAdd(string $member_id, $logger): void
	{
		$params = array(
			'event' => 'onCrmActivityAdd',
			'handler' => 'https://smart.pragma.by/api/own/autotask/bitrix/event_hook.php'
		);

		Factory::init($member_id, $logger);
		$add_event = Factory::get_way('event.bind', $params);

		Factory::LogWriter()->add('event_add', $add_event);
	}

	static function EventsUpdate(string $member_id, $logger): void
	{
		$params = array(
			'event' => 'onCrmActivityUpdate',
			'handler' => 'https://smart.pragma.by/api/own/autotask/bitrix/event_hook.php'
		);

		Factory::init($member_id, $logger);
		$add_event = Factory::get_way('event.bind', $params);

		Factory::LogWriter()->add('event_update', $add_event);
	}

	static function EventsDelete(string $member_id, $logger): void
	{
		$params = array(
			'event' => 'onCrmActivityDelete',
			'handler' => 'https://smart.pragma.by/api/own/autotask/bitrix/event_hook.php'
		);

		Factory::init($member_id, $logger);
		$add_event = Factory::get_way('event.bind', $params);

		Factory::LogWriter()->add('event_delete', $add_event);
	}
}