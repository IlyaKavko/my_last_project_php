<?php


namespace Autocall\Bitrix;
require_once __DIR__ . '/../../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../Factory.php';
require_once __DIR__ . '/Outgoing/Duration.php';


class Bitrix_outgoing
{
    private int $Phone;
    private string $referrer;
    private string $member_id;


    public function __construct(array $REQUEST, $logger)
    {
        $logg = new \LogJSON($REQUEST['subdomain'], \Lirax\WIDGET_NAME, 'CORE');

        $this->Phone = strlen($REQUEST['dnis'] > 5) ? $REQUEST['dnis'] * 1 : $REQUEST['dest'] * 1;
        $this->referrer = $REQUEST['subdomain'];
        $this->member_id = $REQUEST['member_id'];
        $logg->add('Phone', $this->Phone);
        if (strlen($this->Phone) > 10) {
            Factory::init($this->member_id, $logger);
            Factory::getLiraxCore($this->Phone)->setLiraxAdditionallySettings(Factory::getLiraxAdditionallySettings());

//            $PipelineId = Factory::getLirax()->getIdPipelineByPhone($this->Phone);
//            Factory::getLiraxCore($this->Phone)->setMode(true);
            $isTimeWork = Factory::getLiraxCore($this->Phone)->getWorkTime();

            switch ($isTimeWork) {
                case -1:
                    $this->core($REQUEST);
                    break;

                default:
                    $TimeSleep = ($isTimeWork * 60 * 60) + random_int(100, 1000);
                    $DATA = self::DataFile($REQUEST);
                    Factory::getLiraxCore($this->Phone)->getLiraxCoreStorage()->initFileUNLINK('outgoing', $DATA, $TimeSleep);
                    break;
            }
        }
    }



    private function core($REQUEST){

        $duration = intval($REQUEST['duration']);
        $denis = $REQUEST['dnis'];
        switch ($duration) {
            case 0:
                Duration::DurationEqualsZero($denis, $REQUEST); // 0
                break;
            default:
                $phone = intval($denis);
                Duration::DurationMoreZero($phone);  // > 0
                break;
        }
    }


    private function DataFile($serializeData):string
    {
        $serializeData = serialize($serializeData);

        return '
require_once __DIR__ . "/../../../../../amocrm/Controller/Outgoing.php";
require_once __DIR__ . "/../../../../../../../lib/log/LogJSON.php";
require_once __DIR__ . "/../../../../../constants.php";


$referrer = \'' . $this->referrer . '\';
$number_phone = ' . $this->Phone . ';
$serializeData = \'' . $serializeData . '\';

$logger = new LogJSON($referrer . ".amocrm.ru", \Lirax\WIDGET_NAME, "HOOK");
$logger->set_container("");
$unserialezeData = unserialize($serializeData);
unlink(__FILE__);

(new \Autocall\Amocrm\Outgoing($unserialezeData, $logger));
';
    }
}