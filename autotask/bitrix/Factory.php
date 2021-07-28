<?php


namespace AutoTask\Bitrix;

use Generals\Bitrix24\iAccountsModule;

require_once __DIR__ . '/../constants.php';
require_once __DIR__ . '/../../../lib/generals/pragma/PragmaFactory.php';
require_once __DIR__ . '/../../../lib/rest_api/bitrix24/Bitrix24Gateway.php';

class Factory
{
	static private iAccountsModule $accountsModule;
	static protected \LogWriter $log_writer;

	static function init($member_id, \LogWriter $logWriter): void
	{
		try {
			self::$log_writer = $logWriter;
			$f = \Generals\Pragma\PragmaFactory::getAccountsModules()::bitrix24AccountsModules()::getByMemberId(\AutoTask\WIDGET_NAME,$member_id);
			self::$accountsModule = \Generals\Pragma\PragmaFactory::getAccountsModules()::bitrix24AccountsModules()::getByMemberId(\AutoTask\WIDGET_NAME,$member_id);
		} catch (\Exception $exception)
		{
			$logWriter->send_error($exception);
		}
	}

	static function get_way($metod, $params = array()): array|null
	{
		$get_way = new \RestApi\Bitrix24\Bitrix24Gateway(self::$accountsModule);
		return $get_way->query($metod, $params);
	}

	static function LogWriter(){
		return self::$log_writer;
	}

	static function getPragmaAccountId(): int
	{
		return self::$accountsModule->getAccount()->getPragmaAccountId();
	}
}