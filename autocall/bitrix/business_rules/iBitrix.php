<?php


namespace Autocall\Bitrix;
require_once __DIR__ . '/../../pragma/business_rules/iLirax.php';

interface iBitrix extends \Autocall\Pragma\iLirax
{
    function getIdPipelineByPhone(int $Phone) : int;
    function getIdResponsibleLead(int $Phone) : int;
}