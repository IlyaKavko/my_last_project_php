<?php
namespace Autocall\Amocrm;
require_once __DIR__ . '/../../Factory.php';
require_once __DIR__ . '/../../../../../lib/log/LogJSON.php';

class Install
{
	static function setInstall(string $referer): void
	{
		$logger = new \LogJSON('TEST_PMLIRAX', 'pmLirax', 'install');
		try {
			\Autocall\Amocrm\Factory::amocrmInit($referer, $logger);
			\Autocall\Amocrm\Factory::getLiraxSettings()->createNewUser();
			\Autocall\Amocrm\Factory::getLogWriter()->add('success', 'done');
		}catch (\Exception $exception)
		{
			\Autocall\Amocrm\Factory::getLogWriter()->send_error($exception);
		}
	}
}