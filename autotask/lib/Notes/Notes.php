<?php

require_once __DIR__ . '/NotesSchema.php';

class Notes
{
    private NotesSchema $NotesSchema;

    public function __construct(private string $account_id)
    {
        $this->NotesSchema = new NotesSchema($this->account_id);
    }

    public function add(array $data_task, int $task_id)
    {
        $essence_id = $data_task['element_id'] ?? $data_task['OWNER_ID'];
        $essence_type = $data_task['element_type'] ?? $data_task['OWNER_TYPE_ID'];
        $responsible_id = $data_task['responsible_user_id'] ?? $data_task['RESPONSIBLE_ID'];
        $text = $data_task['text'] ?? $data_task['SUBJECT'];
        $this->NotesSchema->addNote($task_id, $responsible_id, $essence_id, $essence_type, $text);
    }

    public function get(int $task_id): array
    {
        return $this->NotesSchema->getNotes($task_id) ?? [];
    }

    public function delete(int $task_id): void
    {
        $this->NotesSchema->deleteNote($task_id);
    }


}