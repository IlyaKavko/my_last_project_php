<?php

namespace Autocall\Amocrm;


use Autocall\Pragma\iLirax;
use Autocall\Pragma\iLiraxSettingsStruct;
use Autocall\Pragma\Lirax;
use Autocall\Pragma\LiraxSettings;
use Services\General\iAccount;

require_once __DIR__ . '/../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../../../lib/generals/amocrm/Factory.php';
require_once __DIR__ . '/../pragma/Factory.php';
require_once __DIR__ . '/../pragma/modules/Lirax.php';
require_once __DIR__ . '/../pragma/modules/core/LiraxCore.php';
require_once __DIR__ . '/modules/getway/Gateway.php';
require_once __DIR__ . '/modules/settings/LiraxSettings.php';


class Factory extends \Autocall\Pragma\Factory
{
    static private string $widget_code = 'pmLirax';
    static private iLiraxSettings $lirax_settings;
    static private iGateway $gateway;
	static private iLirax $lirax;
    static private null|iAccount $Account;
    static private int $Account_id;

	static function getWidgetName():string {
		return self::$widget_code;
	}

	static function amocrmInit(string $referer, \LogWriter $logger): void {
		self::setLogWriter($logger);
		\Services\Factory::init(self::$widget_code, $referer, $logger);
		$node = \Services\Factory::getNodesService()->findAmocrmNodeCode(self::$widget_code, explode('.', $referer)[0]);
		self::$Account = $node->getAccount();
        self::$Account_id = $node->getAccount()->getPragmaAccountId();
		parent::pragmaInit($node);

		self::$gateway = new Gateway($node, Factory::getLogWriter());
	}

    static function getGateway(): iGateway
    {
        return self::$gateway;
    }

	static function getLirax(): iLirax
	{
		if (isset(self::$lirax))
			return self::$lirax;
		self::$lirax = new Lirax(static::getLiraxSettingsStruct());
		return self::$lirax;
	}

	static function getLiraxSettingsStruct(): iLiraxSettingsStruct
	{
		return self::getLiraxSettings()->getSettingsStruct();
	}


    static function getLiraxSettings(): iLiraxSettings
    {
        if (isset(self::$lirax_settings))
            return self::$lirax_settings;

        self::$lirax_settings = new \Autocall\Amocrm\LiraxSettings(self::$Account_id);
        return self::$lirax_settings;
    }

}