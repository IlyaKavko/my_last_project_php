<?php


namespace Autocall\Bitrix;




require_once __DIR__ . '/../business_rules/iBitrix.php';
require_once __DIR__ . '/../../pragma/modules/Lirax.php';


class Bitrix extends \Autocall\Pragma\Lirax implements iBitrix
{
    function getIdPipelineByPhone(int $Phone) : int{
        $CONTACT = Factory::getGateway()->getContactByPhone($Phone);
        $LEADS_ID = $CONTACT->getLeadByContactId();
        $LEAD = Factory::getGateway()->getLeads($LEADS_ID);
        return $LEAD->getIdPipeline();
    }
    function getIdResponsibleLead(int $Phone) : int{
        $CONTACT = Factory::getGateway()->getContactByPhone($Phone);
        $LEADS_ID = $CONTACT->getLeadByContactId();
        $LEAD = Factory::getGateway()->getLeads($LEADS_ID);
        return $LEAD->getIdResponsible();
    }

}