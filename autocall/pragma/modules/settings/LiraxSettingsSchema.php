<?php


namespace Autocall\Pragma;
use Generals\CRMDB;
require_once __DIR__ . '/../../../../../lib/db/CRMDB.php';

class LiraxSettingsSchema extends CRMDB
{
	private static $pragma_account_id;

	public function __construct(int $pragma_account_id)
	{
		self::$pragma_account_id = $pragma_account_id;
	}

	static function check_user(): array
	{
		$sql = "SELECT * FROM ". self::getAutocallAccountSettingsSchema() .
			" WHERE `account_id` = ". self::$pragma_account_id;
		 var_dump(self::querySql($sql));
	}

	public function createNewUser(): bool
	{
		$sql = "INSERT INTO ". self::getAutocallAccountSettingsSchema() .
			" (`account_id`) VALUES (". self::$pragma_account_id .")";
		self::executeSql($sql);
		return true;
	}

	protected function getMaxPipelene(): int
	{
		$sql = "SELECT pipelines FROM " . self::getAutocallAccountSettingsSchema() .
			" WHERE `account_id` = ". self::$pragma_account_id;
		return self::querySql($sql)[0]['pipelines'];
	}

	public function deletePipeline($id): void
	{
		$sql = "DELETE FROM ". self::getAutocallAvailablePipelinesSchema().
			" WHERE ". self::getAutocallAvailablePipelinesSchema().".`account_id` = ". self::$pragma_account_id .
			" AND ". self::getAutocallAvailablePipelinesSchema().".`pipeline_id` = $id";
		self::executeSql($sql);
	}

	public function getPipelines(): array
	{
		$sql = "SELECT pipeline_id FROM " . self::getAutocallAvailablePipelinesSchema() .
			" WHERE `account_id` = ". self::$pragma_account_id;
		return self::querySql($sql);
	}

	public function setPipelines(int $pipelines ): void
	{

		$countPipeline = count($this->getPipelines());
		$maxPipeline = $this->getMaxPipelene();

		if ( $countPipeline < $maxPipeline ) {
			$sql = "INSERT INTO " . self::getAutocallAvailablePipelinesSchema() .
				"(`account_id`, `pipeline_id`) VALUES (" . self::$pragma_account_id . ", $pipelines)";
			self::executeSql($sql);
		}
	}

	public function getAccountAutocallSettings(): array
	{
		$sql = "SELECT * FROM ". self::getAutocallSettingsSchema() .
			" WHERE `account_id` = ". self::$pragma_account_id;
		return self::querySql($sql);
	}

	public function setAutcallSettings(array $arrSetting = []): bool
	{
		if ($this->getAccountAutocallSettings() !== []) {
			$this->updateAutocallSettings($arrSetting);
			return true;
		}

		if ($arrSetting === [])
		{
			$arrSetting = array(
				'USE_LEAD' => 'false',
				'USE_STORE' => 'false',
				'USE_PRIORY' => 'false',
				'USE_NUMBER' => 'false',
				'USE_RESPONSIBLE' => 'false',
				'TOKEN' => '',
				'REFERRER' => '',
				'APPLICATION' => 0
			);
		}
		$account_id = self::$pragma_account_id;
		$sql = "INSERT INTO ". self::getAutocallSettingsSchema() .
			" (account_id, use_lead, use_store, use_priory, use_number, use_responsible, token, refer, application) 
			VALUE (:account_id, :use_lead, :use_store, :use_priory, :use_number, :use_responsible, :token, :refer, :application)";
		self::executeSql($sql, ["account_id" => $account_id, "use_lead" => $arrSetting['USE_LEAD'] !== '' ? $arrSetting['USE_LEAD'] : 'false', "use_store" => $arrSetting['USE_STORE'], "use_priory" => $arrSetting['USE_PRIORY'], "use_number" => $arrSetting['USE_NUMBER'], "use_responsible" => $arrSetting['USE_RESPONSIBLE'], "token" => $arrSetting['TOKEN'], "refer" => $arrSetting['REFERRER'], "application" => $arrSetting['APPLICATION']]);
		return true;
	}

	public function updateAutocallSettings($arrSetting): bool
	{
		$account_id = self::$pragma_account_id;

		$sql = "UPDATE ". self::getAutocallSettingsSchema() .
			"SET `use_lead` = :use_lead, `use_store` = :use_store, `use_priory` = :use_priory, `use_number` = :use_number, `use_responsible` = :use_responsible, `token` = :token, `refer` = :refer, `application` = :application 
			WHERE `account_id` = $account_id";
		self:: executeSql($sql, ['use_lead' => $arrSetting['USE_LEAD'], 'use_store' => $arrSetting['USE_STORE'], 'use_priory' => $arrSetting['USE_PRIORY'], 'use_number' => $arrSetting['USE_NUMBER'], 'use_responsible' => $arrSetting['USE_RESPONSIBLE'], 'token' => $arrSetting['TOKEN'], 'refer' => $arrSetting['REFERRER'], 'application' => $arrSetting['APPLICATION'] ]);
		return true;
	}


}