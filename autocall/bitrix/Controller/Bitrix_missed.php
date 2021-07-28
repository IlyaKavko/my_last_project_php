<?php


namespace Autocall\Bitrix;

require_once __DIR__ . '/../../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../../constants.php';
require_once __DIR__ . '/../Factory.php';
require_once __DIR__ . '/GetDeal.php';
require_once __DIR__ . '/GetResponsible.php';


class Bitrix_missed
{


    private int $Phone;
    private string $referrer;
    private string $member_id;


    public function __construct(array $REQUEST, $logger)
    {
        $this->Phone = $REQUEST['ani'] * 1;
        if (strlen($this->Phone) > 10) {
            $this->referrer = $REQUEST['subdomain'];
            $this->member_id = $REQUEST['member_id'];
            Factory::init($this->member_id, $logger);
            Factory::getLiraxCore($this->Phone)->setLiraxAdditionallySettings(Factory::getLiraxAdditionallySettings());
            Factory::getLiraxCore($this->Phone)->setMode(true);
            $isTimeWork = Factory::getLiraxCore($this->Phone)->getWorkTime();
            Factory::getLogWriter()->add('$isTimeWork', $isTimeWork);
            switch ($isTimeWork) {
                case -1:
                    $this->core();
                    break;
                default:
                    $TimeSleep = ($isTimeWork * 60 * 60) + random_int(100, 1000);
                    $DATA = self::DataFile($REQUEST);
                    Factory::getLiraxCore($this->Phone)->getLiraxCoreStorage()->initFile('missed', $DATA, $TimeSleep);
                    break;
            }
        }

    }

    function core()
    {

        $PHONE_RESPONSIBLE = GetResponsible::getResponsiblePhoneByUserPhone($this->Phone);

        \Autocall\Pragma\Factory::getLogWriter()->add('$PHONE_RESPONSIBLE', $PHONE_RESPONSIBLE);

        self::isFreeUsersAMOFALSE($this->member_id, $this->referrer, $this->Phone, $PHONE_RESPONSIBLE);

    }


    static function isFreeUsersAMOFALSE(string $member_id, string $referrer, int $Phone, int $PhoneResponsible, int $Count_Call = 1)
    {

        $logger = new \LogJSON($referrer, \Lirax\WIDGET_NAME, 'HOOK_isFreeUsers');
        $logger->set_container('');
        Factory::init($member_id, $logger);
        $callsToTheManager = Factory::getLiraxSettings()->getSettingsStruct()->getQuantityResponsible();
        $res = Factory::getLirax()->IsFreeUsers($PhoneResponsible, '0');
        $mode = Factory::getLiraxCore($Phone)->getMode();
        if (!$mode) {
            return 0;
        }
        switch ($PhoneResponsible){
            case 0:
                self::__call__($Phone, 5000);
            break;
            default:
                switch ($res['result']) {
                    case null:
                        if ($callsToTheManager >= $Count_Call)
                        {
                            $DATA = self::DataIsFreeUsersAMOFALSE($referrer, $Phone, $PhoneResponsible, $member_id , $Count_Call);
                            $logger->add('$DATA', $Count_Call);
                            Factory::getLiraxCore($Phone)->getLiraxCoreStorage()->initFileUNLINK('MissedIFUAMOFALSE', $DATA, 60);
                        } else
                        {
                            self::__call__($Phone, 5000);
                        }
                        break;
                    default:
                        self::__call__($Phone, $PhoneResponsible);
                        break;
                }
            break;
        }

    }
    static function __call__(int $Phone, $PhoneResponsible){
        $NUMBERAPD = self::searchNumberANDUPD($Phone);
        $speech = "АвтоЗвонок набор на номер плюс $NUMBERAPD";
        Factory::getLirax()->getLiraxSettingsStruct()->setSpeech($speech);
        Factory::getLirax()->getLiraxSettingsStruct()->setTargetNumber($Phone);
        Factory::getLirax()->getLiraxSettingsStruct()->setInnerNumber($PhoneResponsible);
        Factory::getLirax()->call();
    }
    static function DataIsFreeUsersAMOFALSE(string $referrer, int $Phone,int $PhoneResponsible,string $member_id, int $Count_Call): string
    {
        $count = $Count_Call + 1;
        return '
require_once __DIR__ . "/../../../../../../../lib/log/LogJSON.php";
require_once __DIR__ . "/../../../../../constants.php";
require_once __DIR__ . "/../../../../../bitrix/Controller/Bitrix_missed.php";
$referrer = \'' . $referrer . '\';
$Phone = ' . $Phone . ';
$PhoneResponsible = ' . $PhoneResponsible . ';
$member_id = \'' . $member_id . '\';
$Count_Call = '.$count.';
unlink(__FILE__);
\Autocall\Bitrix\Bitrix_missed::isFreeUsersAMOFALSE($member_id, $referrer, $Phone, $PhoneResponsible, $Count_Call);
';
    }

    private function DataFile($REQUEST): string
    {
        $serializeData = serialize($REQUEST);
        return '
$referrer = "' . $this->referrer . '";
$number_phone = ' . $this->Phone . ';
$serializeData = \'' . $serializeData . '\';

require_once __DIR__ . "/../../../../../../../lib/log/LogJSON.php";
require_once __DIR__ . "/../../../../../constants.php";
require_once __DIR__ . "/../../../../../bitrix/Controller/Bitrix_missed.php";

$logger = new LogJSON($referrer . ".amocrm.ru", \Lirax\WIDGET_NAME, "HOOK_Sleep");
$logger->set_container("");

$unserialezeData = unserialize($serializeData);
unlink(__FILE__);

(new \Autocall\Bitrix\Bitrix_missed($unserialezeData, $logger));';
    }

    static function searchNumberANDUPD($str): string
    {
        $newStr = '';
        $pieces = explode(" ", $str);
        foreach ($pieces as $item) {
            if (preg_match('([0-9-]+)', $item)) {
                $newStr .= self::PhoneD($item);
            } else {
                $newStr .= $item . ' ';
            }
        }
        return $newStr;
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



}