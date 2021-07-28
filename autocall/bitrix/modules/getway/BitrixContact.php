<?php


namespace Autocall\Bitrix;
require_once __DIR__ . '/../../Controller/BEway.php';
require_once __DIR__ . '/../../business_rules/getway/iBitrixContact.php';

class BitrixContact implements iBitrixContact
{
    public function __construct(private string $member_id, private int $id=0)
    {
    }

    public function getIDContact()
    {
        return BEway::getEway($this->member_id, 'crm.contact.get', array("ID" => $this->id));
    }
}