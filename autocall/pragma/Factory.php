<?php

namespace Autocall\Pragma;

require_once __DIR__ . '/modules/Lirax.php';
require_once __DIR__ . '/modules/core/LiraxCore.php';
require_once __DIR__ . '/modules/settings/LiraxSettings.php';
require_once __DIR__ . '/modules/settings/LiraxAdditionallySettings.php';
require_once __DIR__ . '/modules/settings/Pip.php';


use Services\General\iNode;

class Factory
{
    static private iNode $module;
    static private iLiraxSettings $lirax_settings;
    static protected \LogWriter $log_writer;
    static private iPip $pip;
    static private iLirax $lirax;
    static private iLiraxCore $liraxCore;
    static private iLiraxAdditionallySettings $liraxAdditionallySettings;

    static function pragmaInit(iNode $module): void
    {
        self::$module = $module;
    }

    static function getLirax(): iLirax
    {
        if (isset(self::$lirax))
            return self::$lirax;
        self::$lirax = new Lirax(static::getLiraxSettingsStruct());
        return self::$lirax;
    }

    static function getLiraxCore(int $Phone): iLiraxCore
    {
        if (isset(self::$liraxCore))
            return self::$liraxCore;
        self::$liraxCore = new LiraxCore(static::getPragmaAccountId(), $Phone);
        return self::$liraxCore;
    }


    static function getLiraxAdditionallySettings(): iLiraxAdditionallySettings
    {

        if (isset(self::$liraxAdditionallySettings))
            return self::$liraxAdditionallySettings;
        self::$liraxAdditionallySettings = new  LiraxAdditionallySettings(static::getPragmaAccountId());
        return self::$liraxAdditionallySettings;

    }

    static function getLiraxSettingsStruct(): iLiraxSettingsStruct
    {
        return self::getLiraxSettings()->getSettingsStruct();
    }

    static function getLiraxSettings(): iLiraxSettings
    {
        if (isset(self::$lirax_settings))
            return self::$lirax_settings;
        self::$lirax_settings = new LiraxSettings(static::getPragmaAccountId());
        return self::$lirax_settings;
    }

    static function getPips(): iPip
    {
        if (isset(self::$pip))
            return self::$pip;
        self::$pip = new Pip(static::getPragmaAccountId());
        return self::$pip;
    }


    static function getAccountsModule(): iNode
    {
        return self::$module;
    }

    static function setLogWriter($log): void
    {
        self::$log_writer = $log;
    }

    static function Log($mes, $params): void
    {
        self::$log_writer->add($mes, $params);
    }


    static function getLogWriter(): \LogWriter
    {
        return self::$log_writer;
    }

    static function getReferer(): string {
        return self::$module->getAccount()->getDomain();
    }

    static function getPragmaAccountId(): int|string {
        return static::$module->getAccount()->getPragmaAccountId();
    }
}