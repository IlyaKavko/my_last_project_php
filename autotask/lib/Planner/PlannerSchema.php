<?php


require_once __DIR__ . '/../../../../lib/generals/CONSTANTS.php';
require_once __DIR__ . '/../../../../lib/db/CRMDB.php';
require_once __DIR__ . '/../../lib/LogTask.php';

use Generals\CRMDB;


class PlannerSchema extends CRMDB
{
    private string $settings_schema;

    function __construct(private string $account_id)
    {
        parent::__construct();
        $this->settings_schema = self::getAutotaskPlannerSchema();

    }


    public function addPlan(int $task_id, int $tstamp, string $type_CRM)
    {
        $sql = "INSERT INTO $this->settings_schema (`account_id`, `task_id`, `tstamp`, `type_CRM`)
                VALUES (:account_id, :task_id, :tstamp, :type_CRM)
                ON DUPLICATE KEY UPDATE `tstamp` = $tstamp";
        LogTask::LOG('$sql', $sql);
        self::execute($sql, ['account_id' => $this->account_id, 'task_id' => $task_id, 'tstamp' => $tstamp, 'type_CRM' => $type_CRM]);
    }

    public function deletePlan(int $task_id)
    {

        $sql = "DELETE FROM $this->settings_schema WHERE $this->settings_schema.`task_id` = $task_id AND $this->settings_schema.`account_id` ='" . $this->account_id . "'";
        LogTask::LOG('$sql', $sql);
        self::executeSql($sql);
    }

    static function setPlanNull(string $account_id, int $task_id){
        $settings_schema = self::getAutotaskPlannerSchema();

        $sql = "INSERT INTO $settings_schema (`account_id`, `task_id`, `tstamp`)
                VALUES (:account_id, :task_id, :tstamp)
                ON DUPLICATE KEY UPDATE `tstamp` = 0";
        LogTask::LOG('$sql', $sql);
        self::execute($sql, ['account_id' => $account_id, 'task_id' => $task_id, 'tstamp' => 0]);

    }

    static function getPlanners(): array
    {
        $_time = time();
        $settings_schema = self::getAutotaskPlannerSchema();
        $sql = "SELECT $settings_schema . `account_id`, 
        $settings_schema . `task_id`,
        $settings_schema . `type_CRM`
        FROM $settings_schema WHERE $settings_schema . `tstamp` < $_time AND $settings_schema . `tstamp` != 0 ";

        return self::query($sql);
    }


}