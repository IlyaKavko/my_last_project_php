<?php


namespace Autocall\Amocrm;
require_once __DIR__ . '/../../pragma/business_rules/iLirax.php';

interface iLirax extends \Autocall\Pragma\iLirax
{
    function getIdPipelineByPhone(int $Phone) : int;
    function getIdResponsibleLead(int $Phone) : int;


}