<?php

require_once __DIR__ . '/SettingsSchema.php';


class Settings
{
    private SettingsSchema $SettingsSchema;

    public function __construct(private string $account_id)
    {
        $this->SettingsSchema = new SettingsSchema($this->account_id);

    }

    public function save(array $data): bool
    {
        $this->SettingsSchema->save($data["subdomain"], $data["key"], $data["time_call"]);
        return true;
    }

    public function delete()
    {
        $this->SettingsSchema->delete();
    }

    public function get(): array
    {
        return $this->SettingsSchema->get();
    }


}