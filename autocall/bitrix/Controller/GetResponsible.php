<?php


namespace Autocall\Bitrix;
require_once __DIR__. '/../business_rules/getway/iBitrixResponsible.php';
require_once __DIR__ . '/../Factory.php';
class GetResponsible implements iBitrixResponsible
{
	static private array $responsible;
	static function getResponsible( int $id, string $member_id): void
	{
		self::$responsible = Factory::getWay('user.get', array('ID' => $id));
	}

	static function getPhoneResponsible(): int
	{
		return self::$responsible['result'][0]['UF_PHONE_INNER'];
	}

	static function getResponsiblePhoneByUserPhone( int $phone ): int
	{
		$user_info = Factory::getWay('crm.contact.list' , array('filter' => array('PHONE' => '+'.$phone), 'select' => array("ASSIGNED_BY_ID")));
		if (!$user_info['result']){
			$user_infoNotPus = Factory::getWay('crm.contact.list' , array('filter' => array('PHONE' => $phone), 'select' => array("ASSIGNED_BY_ID")));
			if (!$user_infoNotPus['result'])
			{
				return 0;
			}
			$ASSIGNED_ID = $user_infoNotPus['result'][0]['ASSIGNED_BY_ID'];

			$assigned_infoNotPlus = Factory::getWay('user.get', array('ID' => $ASSIGNED_ID));
			return (int)$assigned_infoNotPlus['result'][0]['UF_PHONE_INNER'];
		}
		$ASSIGNED_ID = $user_info['result'][0]['ASSIGNED_BY_ID'];

		$assigned_info = Factory::getWay('user.get', array('ID' => $ASSIGNED_ID));
		return (int)$assigned_info['result'][0]['UF_PHONE_INNER'];

	}
}