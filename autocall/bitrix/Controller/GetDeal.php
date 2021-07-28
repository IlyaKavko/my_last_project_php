<?php

namespace Autocall\Bitrix;


require_once __DIR__ . '/../modules/getway/BitrixContact.php';
require_once __DIR__ . '/../business_rules/getway/iBitrixGetDeal.php';

class GetDeal implements iBitrixGetDeal
{
    public function __construct(
        private string $leadsName,
        private int $id,
        private int $PipelineId,
    )
    {

    }

    function getName(): string
    {
        return $this->leadsName;
    }

    static function getContact(int $id): array
    {
        return Factory::getWay('crm.contact.get', array("ID" => $id));
    }

    function getPhone(): int
    {
	    $user_phone = self::getContact($this->id);
        $phone = preg_replace('/[^0-9]/', '', $user_phone['result']['PHONE'][0]['VALUE']);
        return $phone;
    }

    function getPipelineID(): int
    {
        return $this->PipelineId;
    }
}