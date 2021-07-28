<?php

namespace Autocall\Bitrix;

require_once __DIR__ . '/../../business_rules/iBitrixSettings.php';
require_once __DIR__ . '/../../../pragma/modules/settings/LiraxSettings.php';
require_once __DIR__ . '/BitrixShop.php';
require_once __DIR__ . '/BitrixFrom.php';

use Autocall\Pragma\iLiraxSettingsStruct;
class BitrixSetings extends \Autocall\Pragma\LiraxSettings implements iBitrixSettings
{
    private int $pipeline_id = 1;

    function getSettingsStruct(): iLiraxSettingsStruct
    {
        $struct = parent::getSettingsStruct();
        $struct->useShops() && $struct->setIdShop($this->getIdShop());
        $struct->usePipelineNumber() && $struct->setInnerNumber($this->getIdFromNumber());
        return $struct;
    }

    private function getIdShop(): int
    {
        $SHOP = new BitrixShop($this->pragma_account_id);
        return $SHOP->findIdShop($this->pipeline_id);
    }

    private function getIdFromNumber(): int
    {
        $FROM = new BitrixFrom($this->pragma_account_id);
        return (int)$FROM->findIdFrom($this->pipeline_id);
    }

    function setPipelineId(int $pipeline_id): void
    {
        $this->pipeline_id = $pipeline_id;
    }


}