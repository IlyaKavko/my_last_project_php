<?php


namespace Autocall\Pragma;

require_once __DIR__ . '/../../../../../lib/db/CRMDB.php';

use Generals\CRMDB;

class LiraxCoreSchema extends CRMDB
{
    private $DB;
    private int $id;
    private int $Phone;


    public function __construct(int $pragma_account_id, int $Phone)
    {

	    $this->id = $pragma_account_id;
	    $this->Phone = $Phone;
        $this->DB = $this->getUserPhoneSchema();

        $this->init();
    }

    function getModelPhone(): array
    {
        return $this->DB[$this->id][$this->Phone];
    }

    function setStatus(int $status): void
    {
	    $sql = "UPDATE " . self::getUserPhone() .
		    " SET `status` = :status WHERE ".self::getUserPhone().".`account_id` = $this->id AND ".self::getUserPhone().".`phone` = $this->Phone";
	    self::executeSql($sql, ['status' => $status]);
    }

    function getStatus(): int
    {
	    $sql = "SELECT status FROM ". self::getUserPhone() .
		    " WHERE `account_id` = $this->id AND `phone` = $this->Phone";
	    return self::querySql($sql)[0]['status'];
    }


    function setMode(bool $mode): void
    {
        $sql = "UPDATE " . self::getUserPhone() .
	        " SET `mode` = :mode WHERE ".self::getUserPhone().".`account_id` = $this->id AND ".self::getUserPhone().".`phone` = $this->Phone";
        self::executeSql($sql, ['mode' => $mode]);
    }

    function getMode(): int
    {
	    $sql = "SELECT mode FROM ". self::getUserPhone() .
		    " WHERE `account_id` = $this->id AND `phone` = $this->Phone";
	    return self::querySql($sql)[0]['mode'];
    }

	function getUserPhoneSchema(): array
	{
		$sql = "SELECT * FROM ". self::getUserPhone() .
			" WHERE `account_id` = $this->id AND `phone` = $this->Phone";
		return self::querySql($sql);
	}

    private function init()
    {
        if (!sizeof($this->DB)) {
	        $this->create();
        }
    }


    private function create(): void
    {
    	$sql = "INSERT INTO ". self::getUserPhone() .
		    "(`account_id`, `phone`, `status`, `mode`)
		     VALUES (:account_id, :phone, :status, :mode)";

    	self::executeSql($sql , ['account_id' => $this->id, 'phone' => $this->Phone, 'status' => 0, 'mode' => 'false']);
    }

}