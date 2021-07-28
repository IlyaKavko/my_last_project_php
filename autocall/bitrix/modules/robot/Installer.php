<?php

namespace Autocall\Bitrix;

require_once __DIR__ . '/../../Factory.php';

class Installer
{
	public function __construct( string $member_id, $logger )
	{
		Factory::init($member_id, $logger);
	}

	public function createUser(): void
	{
		try {
			Factory::getLiraxSettings()->createNewUser();
			Factory::getLogWriter()->add('newUser', 'success');
		} catch ( \Exception $exception ) {
			Factory::getLogWriter()->send_error($exception);
		}

	}

	public function AddRobot(): void
	{
		$params = array(
			'code' => 'robot',
			'HANDLER' => 'https://smart-dev.pragma.by/api/own/autocall/bitrix/bitrix_hook.php',
			'AUTH_USER_ID' => 1,
			'NAME' => 'Сообщение для менеджера',
			'PROPERTIES' => array(
				'string' => array(
					'Name' => 'Сообщение менеджеру',
					'Type' => 'text',
					'Default' => 'Осуществляем звонок по новой заявке {{Название лида}} на номер {{=substr({{Телефон (текст)}},0,3)}} {{=substr({{Телефон (текст)}},3,2)}} {{=substr({{Телефон (текст)}},5,3)}} {{=substr({{Телефон (текст)}},8,2)}} {{=substr({{Телефон (текст)}},10,2)}}'
				)
			)
		);

		Factory::getWay('bizproc.robot.add', $params);
	}
}