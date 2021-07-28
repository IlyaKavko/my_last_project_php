<?php

require_once __DIR__ . '/../../../../lib/generals/CONSTANTS.php';
require_once __DIR__ . '/../../../../lib/db/CRMDB.php';
require_once __DIR__ . '/../../lib/LogTask.php';


use Generals\CRMDB;

class NotesSchema extends CRMDB
{
    private string $settings_schema;

    function __construct(private string $account_id)
    {
        parent::__construct();
        $this->settings_schema = self::getAutotaskNotesSchema();

    }


    public function addNote(int $task_id, int $responsible_id, int $essence_id, int $essence_type, string $_text)
    {
        $text = (bool)$_text ? $_text : "0";
        LogTask::LOG('$text', $text);

        $sql = "INSERT INTO $this->settings_schema (`account_id`,`task_id`, `responsible_id`, `essence_id`, `essence_type`, `text`)
                VALUES (:account_id, :task_id, :responsible_id, :essence_id, :essence_type, :text)
                ON DUPLICATE KEY UPDATE `text` =  '" . $text . "',
                                        `responsible_id` = $responsible_id";
        LogTask::LOG('addNote', $sql);
        self::execute($sql, ['account_id' => $this->account_id, 'task_id' => $task_id, 'responsible_id' => $responsible_id, 'essence_id' => $essence_id, 'essence_type' => $essence_type, 'text' => $text]);
    }

    public function deleteNote(int $task_id)
    {
        $sql = "DELETE FROM $this->settings_schema WHERE $this->settings_schema .`task_id` = $task_id AND $this->settings_schema . `account_id` ='" . $this->account_id . "'";
        LogTask::LOG('deleteNote', $sql);
        self::executeSql($sql);
    }


    function getNotes(int $task_id)
    {
        $sql = "SELECT * FROM $this->settings_schema WHERE `account_id` ='" . $this->account_id . "' AND `task_id` = $task_id";
        return self::query($sql)[0];

    }


}