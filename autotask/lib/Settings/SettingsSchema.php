<?php

require_once __DIR__ . '/../../../../lib/generals/CONSTANTS.php';
require_once __DIR__ . '/../../../../lib/db/CRMDB.php';
require_once __DIR__ . '/../../lib/LogTask.php';


use Generals\CRMDB;

class SettingsSchema extends CRMDB
{

    private string $settings_schema;

    function __construct(private string $account_id)
    {
        parent::__construct();
        $this->settings_schema = self::getAutotaskSettingsSchema();
    }

    function save(string $subdomain, string $token, string $time_call): void
    {

        $params = array(
            'account_id' => $this->account_id,
            'subdomain' => $subdomain,
            'token' => $token,
            'time_call' => $time_call
        );

        $_subdomain = '"' . $subdomain . '"';
        $_token = '"' . $token . '"';
        $_time_call = '"' . $time_call . '"';

        $sql = "INSERT INTO $this->settings_schema (`account_id`,`subdomain`, `token`, `time_call`)
                VALUES (:account_id, :subdomain, :token, :time_call)
                ON DUPLICATE KEY UPDATE `token` = $_token, `time_call` = $_time_call";
        LogTask::LOG('addNote', $sql);
        self::execute($sql, $params);

    }

    public function delete()
    {
        $sql = "DELETE FROM $this->settings_schema WHERE $this->settings_schema . `account_id` ='" . $this->account_id . "'";
        LogTask::LOG('deleteSet', $sql);
        self::query($sql);
    }


    public function get(): array
    {
        $sql = "SELECT * FROM $this->settings_schema WHERE `account_id` ='" . $this->account_id . "'";
        return self::query($sql)[0];
    }


}