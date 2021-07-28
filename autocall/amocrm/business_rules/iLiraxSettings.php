<?php


namespace Autocall\Amocrm;

require_once __DIR__ . '/../../pragma/business_rules/settings/iLiraxSettings.php';
interface iLiraxSettings extends \Autocall\Pragma\iLiraxSettings
{
    function setPipelineId(int $pipeline_id): void;

}