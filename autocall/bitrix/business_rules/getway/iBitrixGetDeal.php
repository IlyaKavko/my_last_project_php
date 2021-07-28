<?php


namespace Autocall\Bitrix;


interface iBitrixGetDeal
{
	function getName(): string;

	static function getContact( int $id ): array;

	function getPhone(): int;

	function getPipelineID(): int;
}