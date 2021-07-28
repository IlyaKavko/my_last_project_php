<?php


namespace Autocall\Pragma;
use Generals\CRMDB;
require_once __DIR__ . '/../../../../../lib/db/CRMDB.php';

class LiraxAdditionallySettingsSchema extends CRMDB
{
	private static $pragma_account_id;

	public function __construct( int $pragma_account_id )
	{
		self::$pragma_account_id = $pragma_account_id;
	}

	public function getAllAdditionallySettings(): array|null
	{
		$shop = $this->getTable(self::getShopPipeline());
		$number = $this->getTable(self::getNumberPipeline());
		$lead = $this->getTable(self::getLeadPipeline());
		$priority = $this->getTable(self::getUserPriority());
		$quantity = $this->getTable(self::getClientQuantity());
		$work_time = $this->getTable(self::getWorkTime())[0];
		$work_time['data_q'] = $quantity;

		return array(
			'TimeResponsible' => '',
			'WorkTime' => [],
			'QuantityCallClient' => [],
			'NumberOfCallAttempts' => $work_time,
			'ArrayUsePipelineShops' => $shop,
			'ArrayUsePipelineNumbers' => $number,
			'ArrayUsePriority' => $priority,
			'ArrayUseLeadPipeline' => $lead
		);
	}

	protected function getTable( string $table ): array|null
	{
		$sql = "SELECT * FROM " . $table . " WHERE `account_id` = " . self::$pragma_account_id;
		return self::querySql($sql);
	}

	public function setShopPipeline( array $arrShopPipeline ): void
	{
		$shop = $this->getTable(self::getShopPipeline());
		if ( sizeof($shop) ) {
			$this->updateShopPipeline($arrShopPipeline);
		} else {
			$sql = "INSERT INTO " . self::getShopPipeline() .
				"(`account_id`, `name_pipeline`, `id_pipeline`, `id_set_pipeline`)
			  VALUES (:account_id, :name_pipeline, :id_pipeline, :id_set_pipeline)";
			foreach ( $arrShopPipeline as $arr ) {
				self::executeSql($sql,
					[
						'account_id' => self::$pragma_account_id,
						'name_pipeline' => $arr['pip_name'],
						'id_pipeline' => $arr['pip_id'],
						'id_set_pipeline' => $arr['pip_set_id'] !== '' ? $arr['pip_set_id'] : 0
					]);
			}
		}
	}

	protected function updateShopPipeline( array $arrShopPipeline ): void
	{

		foreach ( $arrShopPipeline as $arr ) {
			$sql = "UPDATE " . self::getShopPipeline() .
				"SET `id_set_pipeline` = :id_set_pipeline
			WHERE `account_id` = " . self::$pragma_account_id . " AND `id_pipeline` = " . $arr['pip_id'];
			self::executeSql($sql,
				[
					'id_set_pipeline' => $arr['pip_set_id'] !== '' ? $arr['pip_set_id'] : 0
				]
			);
		}

	}

	public function setNumberPipeline( array $arrNumberPipeline ): void
	{
		$number = $this->getTable(self::getNumberPipeline());
		if ( sizeof($number) ) {
			$this->updateNumberPipeline($arrNumberPipeline);
		} else {
			$sql = "INSERT INTO " . self::getNumberPipeline() .
				"(`account_id`, `name_pipeline`, `number`, `id_pipeline`)
			VALUES (:account_id, :name_pipeline, :number, :id_pipeline)";
			foreach ( $arrNumberPipeline as $arr ) {
				self::executeSql($sql,
					[
						'account_id' => self::$pragma_account_id,
						'name_pipeline' => $arr['name_pipeline'],
						'number' => $arr['number'] !== '' ? $arr['number'] : 0,
						'id_pipeline' => $arr['id_pipeline']
					]);
			}
		}
	}

	protected function updateNumberPipeline( array $arrNumberPipeline ): void
	{

		foreach ( $arrNumberPipeline as $arr ) {
			$sql = "UPDATE " . self::getNumberPipeline() .
				"SET `number` = :number
			WHERE `account_id` = " . self::$pragma_account_id . " AND `id_pipeline` = " . $arr['id_pipeline'];
			self::executeSql($sql,
				[
					'number' => $arr['number'] !== '' ? $arr['number'] : 0,
				]
			);
		}

	}

	public function setLeadPipeline( array $arrLeadPipeline ): void
	{
		$lead = $this->getTable(self::getLeadPipeline());
		if ( sizeof($lead) ) {
			$this->updateLeadPipeline($arrLeadPipeline);
		} else {
			$sql = "INSERT INTO " . self::getLeadPipeline() .
				"(`account_id`, `lead_id`, `lead_name`, `lead_set_number`)
			VALUES (:account_id, :lead_id, :lead_name, :lead_set_number)";
			foreach ( $arrLeadPipeline as $arr ) {

				self::executeSql($sql, [
					'account_id' => self::$pragma_account_id,
					'lead_id' => $arr['lead_id'],
					'lead_name' => $arr['lead_name'],
					'lead_set_number' => $arr['lead_set_number'] !== '' ? $arr['lead_set_number'] : 0
				]);
			}
		}
	}

	protected function updateLeadPipeline( array $arrLeadPipeline ): void
	{

		foreach ( $arrLeadPipeline as $arr ) {
			$sql = "UPDATE " . self::getLeadPipeline() .
				"SET `lead_set_number` = :lead_set_number
			WHERE `account_id` = " . self::$pragma_account_id . " AND `lead_id` = " . $arr['lead_id'];
			self::executeSql($sql,
				[
					'lead_set_number' => $arr['lead_set_number'] !== '' ? $arr['lead_set_number'] : 0
				]
			);
		}

	}

	public function setPriority( array $arrPriority ): void
	{
		$priority = $this->getTable(self::getUserPriority());
		if ( sizeof($priority) ) {
			$this->updatePriority($arrPriority);
		} else {
			$sql = "INSERT INTO " . self::getUserPriority() .
				"(`account_id`, `id`, `priory`, `name`)
			VALUE (:account_id, :id, :priory, :name)";
			foreach ( $arrPriority as $arr ) {
				self::executeSql($sql,
					[
						'account_id' => self::$pragma_account_id,
						'id' => $arr['id'],
						'priory' => $arr['priory'] !== '' ? $arr['priory'] : 0,
						'name' => $arr['name']
					]
				);
			}
		}
	}

	protected function updatePriority( array $arrPriority ): void
	{

		foreach ( $arrPriority as $arr ) {
			$sql = "UPDATE " . self::getUserPriority() .
				"SET `priory` = :priory
			WHERE `account_id` = " . self::$pragma_account_id . " AND `id` = " . $arr['id'];
			self::executeSql($sql,
				[
					'priory' => $arr['priory'] !== '' ? $arr['priory'] : 0,
				]
			);
		}

	}

	public function setWorkTime( array $workTime ): void
	{
		$WorkTime = $this->getTable(self::getWorkTime());
		if ( sizeof($WorkTime) ) {
			$this->updateWorkTime($workTime);
		} else {
			$sql = "INSERT INTO " . self::getWorkTime() .
				"(`account_id`, `work_start`, `work_finish`, `quantity`)
			VALUE (:account_id, :work_start, :work_finish, :quantity)";
			self::executeSql($sql, [
				'account_id' => self::$pragma_account_id,
				'work_start' => $workTime['work_start'],
				'work_finish' => $workTime['work_finish'],
				'quantity' => $workTime['quantity'] !== '' ? $workTime['quantity'] : 1
			]);
		}
	}

	protected function updateWorkTime( array $workTime ): void
	{
		$sql = "UPDATE " . self::getWorkTime() .
			"SET `work_start` = :work_start, `work_finish` = :work_finish, `quantity` = :quantity
			WHERE `account_id` = " . self::$pragma_account_id;
		self::executeSql($sql,
			[
				'work_start' => $workTime['work_start'],
				'work_finish' => $workTime['work_finish'],
				'quantity' => $workTime['quantity'] !== '' ? $workTime['quantity'] : 1
			]
		);
	}

	public function setQuantity( array $arrQuantity ): void
	{
		$quantity = $this->getTable(self::getClientQuantity());
		if ( sizeof($quantity) )
			$this->deleteWorkTime();

		$sql = "INSERT INTO " . self::getClientQuantity() .
			"(`account_id`, `q`, `time`)
			VALUE (:account_id, :q, :time)";
		foreach ( $arrQuantity as $arr ) {
			self::executeSql($sql,
				[
					'account_id' => self::$pragma_account_id,
					'q' => $arr['q'],
					'time' => $arr['time']
				]
			);
		}
	}

	protected function deleteWorkTime(): void
	{
		$quantity_schema = self::getClientQuantity();
		$sql = "DELETE FROM $quantity_schema WHERE $quantity_schema.`account_id` = " . self::$pragma_account_id;
		self::executeSql($sql);
	}
}