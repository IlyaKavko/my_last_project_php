<?php


namespace Autocall\Bitrix;


interface iBitrixResponsible
{
	static function getResponsible(int $id, string $member_id): void;
	static function getPhoneResponsible(): int;
	static function getResponsiblePhoneByUserPhone(int $phone): int;
}