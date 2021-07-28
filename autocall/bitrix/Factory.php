<?php


namespace Autocall\Bitrix;

use Autocall\Pragma\iLirax;
use Autocall\Pragma\iLiraxCore;
use Autocall\Pragma\iLiraxSettings;

use Autocall\Pragma\iLiraxSettingsStruct;
use Autocall\Pragma\Lirax;
use Autocall\Pragma\LiraxCore;
use Autocall\Pragma\liraxSettings;
use Generals\Bitrix24\iAccountsModule;

require_once __DIR__ . '/../constants.php';
require_once __DIR__ . '/modules/settings/BitrixSetings.php';
require_once __DIR__ . '/modules/Bitrix.php';
require_once __DIR__ . '/../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../../../lib/generals/amocrm/Factory.php';
require_once __DIR__ . '/../pragma/Factory.php';
require_once __DIR__ . '/../pragma/modules/core/LiraxCore.php';
require_once __DIR__ . '/../pragma/business_rules/iLirax.php';
require_once __DIR__ . '/../pragma/business_rules/settings/iLiraxSettingsStruct.php';

class Factory extends \Autocall\Pragma\Factory
{
    static private iLiraxSettings $bitrix_settings;
    static private iLiraxCore $liraxCore;
    static private iAccountsModule $accountsModule;
    static private iLirax $lirax;

    static function init($member_id, \LogWriter $logWriter) : void {
        try {
            self::$log_writer = $logWriter;
            self::$accountsModule = \Generals\Amocrm\Factory::getAccountsModules()::bitrix24AccountsModules()::getByMemberId(\Lirax\WIDGET_NAME, $member_id);
        } catch (\Exception $exception) {
            $logWriter->send_error($exception);
        }
    }

	static function getWay($method, $params=[]): array
	{
		$get_way = new \RestApi\Bitrix24\Bitrix24Gateway(self::$accountsModule);
		return $get_way->query($method, $params);
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

    static function getLiraxCore(int $Phone): iLiraxCore
    {
        if (isset(self::$liraxCore))
            return self::$liraxCore;
        self::$liraxCore = new LiraxCore(static::getPragmaAccountId(), $Phone);

        return self::$liraxCore;
    }

    static function getReferer(): string {
        return self::$accountsModule->getBitrix24Referer();
    }

    static function getPragmaAccountId(): string {
        return self::$accountsModule->getAccount()->getPragmaAccountId();
    }

    static function getBitrix24MemberId(): string
    {
        return self::$accountsModule->getBitrix24MemberId();
    }

    static function getLiraxSettings(): iLiraxSettings
    {
        if (isset(self::$bitrix_settings))
            return self::$bitrix_settings;
        self::$bitrix_settings = new BitrixSetings(static::getPragmaAccountId());
        return self::$bitrix_settings;
    }
}