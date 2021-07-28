<?php


namespace Autocall\Pragma;
require_once __DIR__ . '/../../business_rules/settings/iLiraxAdditionallySettings.php';
require_once __DIR__ . '/LiraxAdditionallySettingsStruct.php';
require_once __DIR__ . '/LiraxAdditionallySettingsSchema.php';


class LiraxAdditionallySettings implements iLiraxAdditionallySettings
{
    protected liraxAdditionallySettingsSchema $GENERAL;

    public function __construct(string $pragma_account_id)
    {
        $this->GENERAL = new liraxAdditionallySettingsSchema($pragma_account_id);
    }

    function getSettingsStruct(): iLiraxAdditionallySettingsStruct
    {
        $LiraxAdditionallySettingsStructModel = $this->GENERAL->getAllAdditionallySettings();
        return new LiraxAdditionallySettingsStruct(
            $LiraxAdditionallySettingsStructModel['TimeResponsible'],
            $LiraxAdditionallySettingsStructModel['WorkTime'],
            $LiraxAdditionallySettingsStructModel['QuantityCallClient'],
            $LiraxAdditionallySettingsStructModel['NumberOfCallAttempts'],
            $LiraxAdditionallySettingsStructModel['ArrayUsePipelineShops'],
            $LiraxAdditionallySettingsStructModel['ArrayUsePipelineNumbers'],
            $LiraxAdditionallySettingsStructModel['ArrayUsePriority'],
            $LiraxAdditionallySettingsStructModel['ArrayUseLeadPipeline'],
        );
    }

//    function saveTimeResponsible(array $quantity): void
//    {
////        $this->GENERAL->setQuantity($quantity);
//    }

    function saveWorkTime(array $quantity): void
    {
        $this->GENERAL->setWorkTime($quantity);

    }

    function saveQuantityCallClient(array $quantity): void
    {
	    $this->GENERAL->setQuantity($quantity);
    }


    function saveNumberOfCallAttempts(array $array_calls): void
    {
        $this->saveWorkTime($array_calls);
        $this->saveQuantityCallClient($array_calls['data_q'] ?? []);
    }

    function saveLeadPipeline(array $array_pipeline): void
    {
        $this->GENERAL->setLeadPipeline($array_pipeline);
    }

    function saveArrayUsePipelineShops(array $array_pipeline): void
    {
        $this->GENERAL->setShopPipeline($array_pipeline);
    }

    function saveArrayUsePipelineNumbers(array $array_pipeline): void
    {
        $this->GENERAL->setNumberPipeline($array_pipeline);
    }

    function saveArrayUsePriority(array $array_pipeline): void
    {
        $this->GENERAL->setPriority($array_pipeline);
    }
}