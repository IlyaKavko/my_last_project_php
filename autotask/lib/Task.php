<?php

require_once __DIR__ . '/Notes/Notes.php';
require_once __DIR__ . '/Planner/Planner.php';
require_once __DIR__ . '/Settings/Settings.php';
require_once __DIR__ . '/TimeCreateLeads.php';
require_once __DIR__ . '/../lib/LogTask.php';


class Task
{
    private Settings $settings;
    private Planner $planner;
    private Notes $notes;
    private string $task_id;

    public function __construct(private string $account_id, private array $data_task)
    {

        $this->task_id = $this->data_task["id"] ?? $this->data_task["ID"];
        $this->settings = new Settings($this->account_id);
        $this->planner = new Planner($this->account_id);
        $this->notes = new Notes($this->account_id);
    }


    function add(int $timestamp = -1)
    {

        $data = match ($timestamp) {
            -1 => array(self::get_time_tomorrow(), $this->task_id, 'amo'),
            default => array($timestamp, $this->task_id, 'b24')
        };

        $this->planner->add($data[0], $data[1], $data[2]);
        $this->notes->add($this->data_task, $this->task_id);
    }


    function delete()
    {
        $this->planner->delete($this->task_id);
        $this->notes->delete($this->task_id);

    }




    private function get_time_tomorrow(): int
    {
        $DataTaskTimestamp = $this->data_task["complete_before"];
        $dd = date('H:i', $DataTaskTimestamp);
        return match ($dd) {
            "23:59" => self::get_the_result_of_two_cases($DataTaskTimestamp),
            default => (int)$DataTaskTimestamp
        };
    }

    private function get_the_result_of_two_cases(int $DataTaskTimestamp): int
    {
        $Setting = $this->settings->get();
        $str_Date = date('Y-m-d', $DataTaskTimestamp);
        return match ($Setting["time_call"]) {
            "-1" => self::get_req($str_Date),
            default => strtotime($str_Date . $Setting["time_call"])
        };

    }

    private function get_req(string $str_Date): int
    {
        $response = TimeCreateLeads::start($this->data_task);
        $str_Time = date('H:i', $response["_embedded"]["leads"][0]["created_at"]);
        return strtotime($str_Date . $str_Time);
    }

}