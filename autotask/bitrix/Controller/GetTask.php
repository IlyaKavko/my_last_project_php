<?php


namespace AutoTask\Bitrix;
require_once __DIR__ . '/../business_rules/iGetTask.php';

class GetTask implements iGetTask
{
	static private $SUBJECT;
	static private $DEADLINE;
	static private $COMPLETED;
	static private $TYPE;
	static private $ID_TYPE;
	static private $DIRECTION;
	static private $PROVIDER_ID;

	public function __construct(array $task)
	{
		self::$SUBJECT = $task['SUBJECT'];
		self::$DEADLINE = $task['DEADLINE'];
		self::$COMPLETED = $task['COMPLETED'];
		self::$TYPE = $task['OWNER_TYPE_ID'];
		self::$ID_TYPE = $task['OWNER_ID'];
		self::$DIRECTION = $task['DIRECTION'];
		self::$PROVIDER_ID = $task['PROVIDER_ID'];
	}

	function getSubject(): string
	{
		return self::$SUBJECT;
	}

	function getDeadline(): int
	{
		$date = self::$DEADLINE;
		$date_sec = strtotime($date);
		return (int)$date_sec;
	}

	function getCompleted(): bool
	{
		if (self::$COMPLETED === 'Y')
		{
			return true;
		}
		return false;
	}

	function getType(): int
	{
		return self::$TYPE;
	}

	function getIdType(): int
	{
		return (int)self::$ID_TYPE;
	}

	function getDirection(): int
	{
		return (int)self::$DIRECTION;
	}

	function getProviderId(): string
	{
		return self::$PROVIDER_ID;
	}
}