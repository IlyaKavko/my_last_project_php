<?php


namespace Autocall\Pragma;
require_once __DIR__ . '/../../business_rules/settings/iPip.php';
require_once __DIR__ . '/../settings/LiraxSettings.php';
require_once __DIR__ . '/PipsSchema.php';


class Pip implements iPip
{
    static liraxSettings $PipsSchema;

    public function __construct(private string $account_id)
    {
        self::$PipsSchema = new liraxSettings($this->account_id);
    }


    function savePipe($id): void
    {
        self::$PipsSchema->savePipeline($id);
    }

    function deletePipe($id): void
    {
    	self::$PipsSchema->deletePipeline($id);
    }

    function getPips(): array|null
    {
    	$newArrPipeline = array();
    	foreach (self::$PipsSchema->getPipeline() as $pip)
	    {
	    	$newArrPipeline[] = $pip['pipeline_id'];
	    }
        return ['pipelines' => $newArrPipeline];
    }

    private function create_account()
    {
        if (!self::$PipsSchema->search_key())
            self::$PipsSchema->add_account();
    }
}