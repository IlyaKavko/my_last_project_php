<?php
namespace AutoTask\Bitrix;

interface iGetTask
{
	function getSubject(): string;

	function getDeadline(): int;

	function getCompleted(): bool;

	function getType(): int;

	function getIdType(): int;

	function getDirection(): int;

	function getProviderId(): string;

}