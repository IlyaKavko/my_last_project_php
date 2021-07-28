<?php

namespace Autocall\Amocrm;

use Autocall\Pragma\iLiraxAdditionallySettingsStruct;
use Autocall\Pragma\LiraxAdditionallySettingsSchema;
use Autocall\Pragma\LiraxAdditionallySettingsStruct;

require_once __DIR__ . '/../../../pragma/modules/settings/LiraxAdditionallySettingsSchema.php';

//extends   \Generals\CRMDB

class LiraxShop
{
    private LiraxAdditionallySettingsSchema $GENERAL;

    public function __construct(int $pragma_account_id)
    {
        $this->GENERAL = new LiraxAdditionallySettingsSchema($pragma_account_id);
    }

    function findIdShop(int $pipeline_id): int
    {
        $IdShop = 1;

        $ArrayUsePipelineShops = $this->getLiraxAdditionallySettingsStructModel()->getArrayUsePipelineShops();

        foreach ($ArrayUsePipelineShops as $item) {
            if ($item['id_pipeline'] == $pipeline_id) {
                $IdShop = $item['id_set_pipeline'];
            }
        }
        return $IdShop;
    }

    private function getLiraxAdditionallySettingsStructModel(): iLiraxAdditionallySettingsStruct
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
	        $LiraxAdditionallySettingsStructModel['ArrayUseLeadPipeline']
        );
    }



}