<?php


namespace Autocall\Bitrix;
require_once __DIR__ . '/../business_rules/getway/iBitrixGetLead.php';

class GetLead implements iBitrixGetLead
{

	public function __construct(
		private string $leadsName,
		private int $phone,
		private int $PipelineId,
	)
	{
	}

	function getName(): string
	{
		return $this->leadsName;
	}


	function getPhone(): int
	{
		return $this->phone;
	}

	function getPipelineID(): int
	{
		$getLeadPipeline = Factory::getWay('crm.status.list', array( "filter" => array( "STATUS_ID" => $this->PipelineId ) ));
		return $getLeadPipeline['result'][0]['ID'];
	}
}