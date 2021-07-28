<?php
namespace Autocall\Bitrix;

require_once __DIR__ . '/../../pragma/business_rules/settings/iLiraxSettings.php';

interface iBitrixSettings extends \Autocall\Pragma\iLiraxSettings
{
    function setPipelineId(int $pipeline_id): void;
}