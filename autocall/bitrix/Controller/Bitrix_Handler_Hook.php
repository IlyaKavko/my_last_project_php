<?php
namespace Autocall\Bitrix;



require_once __DIR__ . '/../../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../../constants.php';
require_once __DIR__ . '/../../../../lib/generals/bitrix24/Factory.php';
require_once __DIR__ . '/GetDeal.php';
require_once __DIR__ . '/GetLead.php';
require_once  __DIR__ . '/../Factory.php';
require_once __DIR__ . '/../../../autotask/bitrix/Factory.php';
require_once __DIR__ . '/GetResponsible.php';

class Bitrix_Handler_Hook
{
    static $REQUEST;
    static $ID;
    static $NameDeal;
    static $Phone;
    static $PipelineID;
    static $PhoneResponsible;
    static $referer;
    static $status;
    static $speach;
	static $LEAD;

    public function __construct($REQUEST)
    {
        self::$REQUEST = $REQUEST;
        self::$referer = $REQUEST['auth']['domain'];
        self::$speach = $REQUEST['properties']['string'];
        self::Handler_Request($REQUEST);
    }

    static function Handler_Request(array $REQUEST) : void
    {
        try {
            $member_id = $REQUEST['auth']['member_id'];
            $status_id = $REQUEST['document_id'][2];
            $status_id = preg_replace('/[^0-9]/', '', $status_id);
            self::$status = preg_replace('/[^A-Z]/', '', $REQUEST['document_id'][2]);

            switch (self::$status)
            {
                case 'DEAL':
                    $result = Factory::getWay('crm.deal.get', array('ID' => $status_id));
                    $INFO_STATUS = self::getDeal($result['result'], $member_id);
                break;
                case 'LEAD':
                    $result = Factory::getWay('crm.lead.get', array('ID'=>$status_id));
                    $INFO_STATUS = self::getLead($result['result'], $member_id);
                break;
            }



            self::NameDeal($INFO_STATUS->getName());
            self::$Phone = $INFO_STATUS->getPhone();
            self::PipelineId($INFO_STATUS->getPipelineID());
            self::$PhoneResponsible = GetResponsible::getResponsiblePhoneByUserPhone(self::$Phone);

        } catch (\Exception $exception) {
            Factory::getLogWriter()->send_error($exception);
        }
    }

    static function getDeal($result, $member_id): iBitrixGetDeal
    {
        return new \Autocall\Bitrix\GetDeal($result['TITLE'], $result['CONTACT_ID'], $result['CATEGORY_ID']);
    }

    static function getLead($result, $member_id): iBitrixGetLead
    {
        return new \Autocall\Bitrix\GetLead($result['TITLE'], $result['PHONE'][0]['VALUE'], $result['STATUS_ID']);
    }

    function run(int $calc_call = 1): void
    {
        Factory::getLiraxSettings()->setPipelineId(self::$PipelineID);
        $Phone = self::$Phone;
        if ($Phone < 8) {
            Factory::getLogWriter()->add('pone', 0);
            die();
        }
        $callsToTheManager = Factory::getLiraxSettings()->getSettingsStruct()->getQuantityResponsible();
        $res = Factory::getLirax()->IsFreeUsers(self::$PhoneResponsible, '0');

        Factory::getLiraxCore(self::$Phone)->setLiraxAdditionallySettings(Factory::getLiraxAdditionallySettings());
        $useLead = Factory::getLiraxSettings()->getSettingsStruct()->getArrayToSave();

        $isTimeWork = Factory::getLiraxCore(self::$Phone)->getWorkTime();

        switch (self::$status){
            case 'DEAL':
                switch ($isTimeWork)
                {
                    case -1:
                        switch ($res['result'])
                        {
                            case null:
                                if ($callsToTheManager >= $calc_call)
                                {
                                    $DATA = self::DataIsFreeUsersAMOFALSE(self::$REQUEST, $calc_call);
                                    Factory::getLiraxCore($Phone)->getLiraxCoreStorage()->initFileUNLINK('DealCallResponsible', $DATA, 60);
                                }
                                else {
                                    $speech = self::$speach;
                                    Bitrix_Handler_Hook::_call_($Phone, (string)$speech);
                                }
                                break;
                            default:
                                $speech = self::$speach;
                                Bitrix_Handler_Hook::_call_($Phone, (string)$speech, self::$PhoneResponsible);
                                break;
                        }
                        break;
                    default:
                        $TimeSleep = ($isTimeWork * 60 * 60) + random_int(100, 1000);
                        $DATA = self::DataFile(self::$REQUEST);
                        Factory::getLiraxCore(self::$Phone)->getLiraxCoreStorage()->initFile('missed', $DATA, $TimeSleep);
                        break;
                }
            break;
            case 'LEAD':
                switch ($isTimeWork)
                {
                    case -1:
                        switch ($useLead["use_lead"])
                        {
                            case 'true':
                                $LiraxAdditionallySettings = Factory::getLiraxAdditionallySettings()->getSettingsStruct();
                                $LEAD_PIPELINES = $LiraxAdditionallySettings->getArrayUseLeadPipeline();
                                foreach ($LEAD_PIPELINES as $arLead)
                                {
                                    if ($arLead['lead_id'] === self::$PipelineID){

                                        $speech = self::$speach;
                                        Bitrix_Handler_Hook::_call_($Phone, $speech, $arLead['lead_set_number']);
                                    }
                                }
                            break;
                            default:
                                $speech = self::$speach;
                                Bitrix_Handler_Hook::_call_($Phone, $speech, 5000);
                            break;
                        }
                    break;
	                default:
		                $TimeSleep = ($isTimeWork * 60 * 60) + random_int(100, 1000);
		                $DATA = self::DataFile(self::$REQUEST);
		                Factory::getLiraxCore(self::$Phone)->getLiraxCoreStorage()->initFile('missed', $DATA, $TimeSleep);
		                break;
                }

            break;
        }

    }

    static function AutoTaskInit (string $member_id, int $element_type, int $element_id, int $responsible_id, string $speach, $logger): void
    {
    	Factory::init($member_id,$logger);
    	switch ($element_type)
	    {
		    case 1:

				GetResponsible::getResponsible($responsible_id, $member_id);
				self::$PhoneResponsible = GetResponsible::getPhoneResponsible();
				self::$speach = $speach;
				self::AutoTask_Lead($element_id);

		    break;
	    }

	    Bitrix_Handler_Hook::_call_(self::$Phone, self::$speach, self::$PhoneResponsible);

    }

    static function AutoTask_Lead(int $element_id): void
    {
    	self::$LEAD = Factory::getWay('crm.lead.get', array('ID' => $element_id));
        self::$NameDeal = self::$LEAD['result']['NAME'];
        self::$Phone = self::$LEAD['result']['PHONE'][0]['VALUE'];
    }

    static function NameDeal($name): string
    {
        return self::$NameDeal = $name;
    }

    static function Phone($phone): string
    {
        $newStr = '';
        $pieces = explode(" ", $phone);
        foreach ($pieces as $item) {
            if (preg_match('([0-9-]+)', $item)) {
                $newStr .= self::PhoneD($item);
            } else {
                $newStr .= $item . ' ';
            }
        }
        return self::$Phone = $newStr;
    }

    static function PhoneD(string $str): string
    {
        $newStr = '';
        for ($i = 0; $i < strlen($str); $i++) {
            $dit = $str[$i];
            switch ($i) {
                case 2:
                case 4:
                case 7:
                case 9:
                    $newStr .= $dit . " ";
                    break;
                default:
                    $newStr .= $dit;
                    break;
            }
        }
        return $newStr;
    }

    static function PipelineId($pipelineId): string
    {
        return self::$PipelineID = $pipelineId;
    }

    static function _call_ ($Phone, $speech, $PhoneResponsible=null)
    {
        Factory::getLiraxCore($Phone)->setMode(true);
        Factory::getLirax()->getLiraxSettingsStruct()->setSpeech($speech);
        Factory::getLirax()->getLiraxSettingsStruct()->setTargetNumber($Phone);
        if ($PhoneResponsible !== null)
        {
            Factory::getLirax()->getLiraxSettingsStruct()->setInnerNumber($PhoneResponsible);
        }
        Factory::getLirax()->call();
    }

    static function DataIsFreeUsersAMOFALSE(array $REQUEST, int $Count_Call): string
    {
        $count = $Count_Call + 1;
        return '
require_once __DIR__ . "/../../../../../../../lib/log/LogJSON.php";
require_once __DIR__ . "/../../../../../constants.php";
require_once __DIR__ . "/../../../../../bitrix/Controller/Bitrix_Handler_Hook.php";
$Count_Call = '.$count.';
$REQUEST = \''.json_encode($REQUEST).'\';
unlink(__FILE__);
(new \Autocall\Bitrix\Bitrix_Handler_Hook((array) json_decode($REQUEST, 1)))->run($Count_Call);
';
    }

    private function DataFile($REQUEST): string
    {
        return '
$referrer = "' . self::$referer . '";

require_once __DIR__ . "/../../../../../../../lib/log/LogJSON.php";
require_once __DIR__ . "/../../../../../constants.php";
require_once __DIR__ . "/../../../../../bitrix/Controller/Bitrix_Handler_Hook.php";

$logger = new LogJSON($referrer, \Lirax\WIDGET_NAME, "HOOK_Sleep");
$logger->set_container("");

$REQUEST = \''.json_encode($REQUEST).'\';
unlink(__FILE__);

(new \Autocall\Bitrix\Bitrix_Handler_Hook((array) json_decode($REQUEST, 1)))->run();';
    }
}