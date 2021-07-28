<?php


namespace Storage;


trait SpecificationsBuffer
{
	private array $specifications = [];

	private function deleteFromBuffer(int $specification_id): void
	{
		unset($this->specifications[$specification_id]);
	}

	private function findInBuffer(int $specification_id): iSpecificationsStruct|null
	{
		return $this->specifications[$specification_id] ?? null;
	}

	private function addBuffer(iSpecificationsStruct $specifications): void
	{
		$this->specifications[$specifications->getIdSpecifications()] = $specifications;
	}
}