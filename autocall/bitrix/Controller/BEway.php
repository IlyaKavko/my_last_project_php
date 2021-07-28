<?php

namespace Autocall\Bitrix;

require_once __DIR__ . '/../../../../lib/generals/bitrix24/Factory.php';

class BEway
{
    static function getEway($member_id, $metod, $params = [])
    {
        $accounts_module = \Generals\Bitrix24\Factory::getAccountsModules()::bitrix24AccountsModules()::getByMemberId('pmLirax', $member_id);
        $gateway = new \RestApi\Bitrix24\Bitrix24Gateway($accounts_module);

        return $gateway->query($metod, $params);
    }
}

?>