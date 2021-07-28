<?php


namespace Autocall\Bitrix;
require_once __DIR__ . '/../Factory.php';


class Bitrix_adopted
{

    private int $Phone;
    private string $member_id;

    public function __construct(array $REQUEST, $logger)
    {
        $this->Phone = $REQUEST['ani'] * 1;
        $this->member_id = $REQUEST['member_id'];
        $dnis = $REQUEST['dnis'];
        if (strlen($dnis) > 4) {
            Factory::init($this->member_id, $logger);
            $this->core();
        }

    }

    function core()
    {

        Factory::getLiraxCore($this->Phone)->getLiraxCoreStorage()->getExistGeneralFile() ? $this->TRUE() : $this->FALSE();
    }

    private function TRUE()
    {
        $MAX_QUANTITY = Factory::getLiraxAdditionallySettings()->getSettingsStruct()->getNumber_of_call_attempts()['quantity'];
        $new_status = $MAX_QUANTITY * 1;
        Factory::getLiraxCore($this->Phone)->setStatus($new_status);

    }

    private function FALSE()
    {
        Factory::getLiraxCore($this->Phone)->setStatus(0);
        Factory::getLiraxCore($this->Phone)->setMode(false);
    }

}