<?php

require_once __DIR__ . '/PlannerSchema.php';

class Planner
{

    private PlannerSchema $PlannerSchema;

    public function __construct(private string $account_id)
    {
        $this->PlannerSchema = new PlannerSchema($this->account_id);
    }


    public function add(int $time, int $task_id, string $type_CRM)
    {
        $this->PlannerSchema->addPlan($task_id, $time, $type_CRM);
    }

    public function delete(int $task_id): void
    {
        $this->PlannerSchema->deletePlan($task_id);
    }

    static public function getPlanners(): array
    {
        $arr = PlannerSchema::getPlanners();
        foreach ($arr as $item) {
            PlannerSchema::setPlanNull($item['account_id'], $item['task_id']);
        }
        return $arr;
    }

}