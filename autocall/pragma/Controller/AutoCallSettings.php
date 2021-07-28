<?php
require_once __DIR__ . '/../../amocrm/Factory.php';
require_once __DIR__. '/../../bitrix/Factory.php';
require_once __DIR__ . '/../../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../../constants.php';


use Autocall\Amocrm\Factory;
use JetBrains\PhpStorm\NoReturn;

class AutoCallSettings
{
    private $account_id;
    private string $referrer;
    private string $flag;
    private array $REQUEST;

    public function __construct($REQUEST)
    {
        $this->account_id = $REQUEST['ID_ACCOUNT'];

        $typeCRM = $REQUEST['typeCRM'];

        switch ($typeCRM){
            case 'amocrm':
                $this->amocrm($REQUEST);
            break;

            case 'bitrix':
                $this->bitrix($REQUEST);

        }



    }

    private function bitrix(array $REQUEST) {
        $logger = new LogJSON(get_referer(), \Lirax\WIDGET_NAME, 'settings');
        $logger->set_container('');
        Autocall\Bitrix\Factory::init($this->account_id,$logger);
        $this->REQUEST = $REQUEST;
        $this->referrer = Autocall\Bitrix\Factory::getReferer();
        $this->flag = $REQUEST['flag'];
        switch ($this->flag){
            case 'save_settings':
                $this->save_settings();
            break;
            case 'get_settings':
                $this->get_settings();
            break;
        }
    }



    private function amocrm(array $REQUEST)
    {
        $this->REQUEST = $REQUEST;
        Factory::getLogWriter()->add('$REQUEST', $REQUEST);
        $this->referrer = Factory::getAccountsModule()->getAccount()->getAmocrmReferer();
        $this->flag = $REQUEST['FLAG'];
        switch ($this->flag) {
            case "save_settings":
                $this->save_settings();
                break;
            case "get_settings":
                $this->get_settings();
                break;
        }
    }



    private function save_settings()
    {
        $REQUEST = $this->REQUEST;

        $QUANTITY = $REQUEST['QUANTITY'];
        $ARRAY_PIPELINE = $REQUEST['ARRAY_PIPELINE'];
        $ARRAY_NUM_PIP = $REQUEST['ARRAY_NUM_PIP'];
        $ARRAY_PRIORY = $REQUEST['ARRAY_PRIORY'];
        $ARRAY_LEAD_PIPELINE = $REQUEST['ARRAY_LEAD_PIPELINE'] ?? [];
        $TYPE_CRM = $REQUEST['typeCRM'];

        switch ($TYPE_CRM) {
            case 'amocrm':
	            Factory::getLiraxSettings()->saveGeneralSettings($REQUEST);

                Factory::getLiraxAdditionallySettings()->saveNumberOfCallAttempts(self::DECODE_($QUANTITY));
                Factory::getLiraxAdditionallySettings()->saveArrayUsePipelineShops(self::DECODE_($ARRAY_PIPELINE));
                Factory::getLiraxAdditionallySettings()->saveArrayUsePipelineNumbers(self::DECODE_($ARRAY_NUM_PIP));
                Factory::getLiraxAdditionallySettings()->saveArrayUsePriority(self::DECODE_($ARRAY_PRIORY));
            break;
            case 'bitrix':
            	\Autocall\Bitrix\Factory::getLogWriter()->add('$REQUEST', $REQUEST);
                Autocall\Bitrix\Factory::getLiraxSettings()->saveGeneralSettings($REQUEST);

                Autocall\Bitrix\Factory::getLiraxAdditionallySettings()->saveNumberOfCallAttempts(self::DECODE_($QUANTITY));
                Autocall\Bitrix\Factory::getLiraxAdditionallySettings()->saveArrayUsePipelineShops(self::DECODE_($ARRAY_PIPELINE));
                Autocall\Bitrix\Factory::getLiraxAdditionallySettings()->saveArrayUsePipelineNumbers(self::DECODE_($ARRAY_NUM_PIP));
                Autocall\Bitrix\Factory::getLiraxAdditionallySettings()->saveArrayUsePriority(self::DECODE_($ARRAY_PRIORY));
                Autocall\Bitrix\Factory::getLiraxAdditionallySettings()->saveLeadPipeline(self::DECODE_($ARRAY_LEAD_PIPELINE));
            break;
        }



    }

    #[NoReturn] private function get_settings(): void
    {
        $REQUEST = $this->REQUEST;
        $TYPE_CRM = $REQUEST['typeCRM'];

        if ($TYPE_CRM == 'bitrix') {
            $data = Autocall\Bitrix\Factory::getLiraxSettings()->getSettingsStruct();
            $DATA = $data->getArrayToSave();

            $LiraxAdditionallySettings = Autocall\Bitrix\Factory::getLiraxAdditionallySettings()->getSettingsStruct();
            $NUMBERS = $LiraxAdditionallySettings->getArrayUsePipelineNumbers();
            $PIPELINES = $LiraxAdditionallySettings->getArrayUsePipelineShops();
            $QUANTITY = $LiraxAdditionallySettings->getNumber_of_call_attempts();
            $PRIORY = $LiraxAdditionallySettings->getArrayUsePriority();
            $LEAD_PIPELINES = $LiraxAdditionallySettings->getArrayUseLeadPipeline();

            $Settings = array(
                'data' => [$DATA],
                'numbers' => $NUMBERS,
                'pipelines' => $PIPELINES,
                'quantity' => $QUANTITY,
                'priority' => $PRIORY,
                'lead' => $LEAD_PIPELINES
            );
            echo json_encode($Settings);
            die();
        }
        $data = Factory::getLiraxSettings()->getSettingsStruct();
        $DATA = $data->getArrayToSave();
        $DATA['refer'] = Factory::getReferer();
        $DATA['id_account'] = Factory::getAccountsModule()->getAccount()->getAmocrmAccountId();

        $LiraxAdditionallySettings = Factory::getLiraxAdditionallySettings()->getSettingsStruct();


        $NUMBERS = $LiraxAdditionallySettings->getArrayUsePipelineNumbers();
        $PIPELINES = $LiraxAdditionallySettings->getArrayUsePipelineShops();
        $QUANTITY = $LiraxAdditionallySettings->getNumber_of_call_attempts();
        $PRIORY = $LiraxAdditionallySettings->getArrayUsePriority();

        $Settings = array(
            'data' => [$DATA],
            'numbers' => $NUMBERS,
            'pipelines' => $PIPELINES,
            'quantity' => $QUANTITY,
            'priority' => $PRIORY,
        );
        echo json_encode($Settings);
        die();
    }


    static function DECODE_($array)
    {
        $ARR = json_encode($array,JSON_UNESCAPED_UNICODE);
        return json_decode($ARR, true);
    }


}