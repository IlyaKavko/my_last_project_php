<?php

namespace Autocall\Bitrix;

use LogJSON;

require_once __DIR__ . '/../../Factory.php';
require_once __DIR__ . '/../../../constants.php';
require_once __DIR__ . '/../GetResponsible.php';

class File
{
	private int $MAX_QUANTITY;

	public function __construct( private string $referrer, private int $Phone, string $member_id )
	{
		$logger = new LogJSON($referrer, \Lirax\WIDGET_NAME, 'File');
		$logger->set_container('');
		Factory::init($member_id, $logger);
		$QUANTITY = Factory::getLiraxAdditionallySettings()->getSettingsStruct()->getNumber_of_call_attempts();
		$this->MAX_QUANTITY = $QUANTITY['quantity'] * 1;
		$Status = Factory::getLiraxCore($this->Phone)->getStatus();
		$mode = Factory::getLiraxCore($this->Phone)->getMode();
		if ( !$mode ) {
			return 0;
		}


		switch ( $Status ) {
			case  $this->MAX_QUANTITY:
				Factory::getLiraxCore($this->Phone)->setStatus(0);
				Factory::getLiraxCore($this->Phone)->setMode(false);
				break;
			default:
				$this->_DEFAULT($member_id, $this->Phone);
				break;
		}
	}

	private function _DEFAULT( string $member_id, int $phone )
	{

		$res = GetResponsible::getResponsiblePhoneByUserPhone($phone);
		Factory::getLogWriter()->add('$res', $res);

		if ( $res == null ) {
			$NUMBERAPD = self::searchNumberANDUPD($this->Phone);
			$speech = "Авто звоНок набор на номер плюс $NUMBERAPD";
			Factory::getLirax()->getLiraxSettingsStruct()->setSpeech($speech);
			Factory::getLirax()->getLiraxSettingsStruct()->setTargetNumber($this->Phone);
			Factory::getLirax()->getLiraxSettingsStruct()->setInnerNumber(5000);
			Factory::getLirax()->call();
			Factory::log($this->Phone . " NO RESPONSIBLE res = 0 call5000", true);
		} else {

			$this->falseIsFreeUsers($res, 0, $member_id);

		}

	}

	function trueIsFreeUsers(): void
	{
		$arrInnerNumber = Factory::getLirax()->getUserSIPs();
		$data_res = Factory::getLirax()->IsFreeUsers($arrInnerNumber, 0);
		switch ( $data_res ) {
			case 'null':
			case null:
				$data = $this->Render_DATA_NULL_();
				Factory::getLiraxCore($this->Phone)->getLiraxCoreStorage()->initFile('trueIsFreeUsersNULL_', $data, 60);
				break;
			default :
				$this->trueIsFreeUsersRESULT($data_res['ext']);
				break;
		}
	}


	function trueIsFreeUsersRESULT( string $ext ): void
	{
		$status = Factory::getLiraxCore($this->Phone)->getStatus();
		$MStatus = $this->MAX_QUANTITY;

		switch ( $status ) {
			case $MStatus:
				Factory::getLiraxCore($this->Phone)->setStatus(0);
				Factory::getLiraxCore($this->Phone)->setMode(false);
				Factory::log('trueIsFreeUsersRESULT', [
					'MaxNumberOfCalls' => $this->MAX_QUANTITY,
					$this->Phone => 'OFF'
				]);
				break;
			default:
				$new_status = $status + 1;
				Factory::getLiraxCore($this->Phone)->setStatus($new_status);
				$NUMBERAPD = self::searchNumberANDUPD($this->Phone);
				$speech = "Авто звоНок набор на номер плюс $NUMBERAPD";
				Factory::getLirax()->getLiraxSettingsStruct()->setSpeech($speech);
				Factory::getLirax()->getLiraxSettingsStruct()->setTargetNumber($this->Phone);
				Factory::getLirax()->getLiraxSettingsStruct()->setInnerNumber($ext);
				Factory::getLirax()->call();
				Factory::log('trueIsFreeUsersRESULT', [
					'New Status' => $new_status,
				]);
				break;

		}

	}

	function falseIsFreeUsers( string $responsibility, $N, string $member_id ): void
	{
		$res = Factory::getLirax()->IsFreeUsers($responsibility, 0);
		$data_res = $res['result'];

		switch ( $data_res ) {
			case null:
			case 'null':
				if ( $N < 30 ) {
					$data = $this->IsFreeUsersNULLandNO30($responsibility, $N, $member_id);
					Factory::getLiraxCore($this->Phone)->getLiraxCoreStorage()->initFile('falseIsFreeUsersNULLandNO30', $data, 60);
				} else $this->trueIsFreeUsers();
				break;
			default:
				$NUMBERAPD = self::searchNumberANDUPD($this->Phone);
				$speech = "Авто звоНок набор на номер плюс $NUMBERAPD";
				Factory::getLirax()->getLiraxSettingsStruct()->setSpeech($speech);
				Factory::getLirax()->getLiraxSettingsStruct()->setTargetNumber($this->Phone);
				Factory::getLirax()->getLiraxSettingsStruct()->setInnerNumber($res['ext']);
				Factory::getLirax()->call();
				break;
		}

	}


	static function searchNumberANDUPD( $str ): string
	{
		$newStr = '';
		$pieces = explode(" ", $str);
		foreach ( $pieces as $item ) {
			if ( preg_match('([0-9-]+)', $item) ) {
				$newStr .= self::PhoneD($item);
			} else {
				$newStr .= $item . ' ';
			}
		}
		return $newStr;
	}

	static function PhoneD( string $str ): string
	{
		$newStr = '';
		for ( $i = 0; $i < strlen($str); $i++ ) {
			$dit = $str[$i];
			switch ( $i ) {
				case 2:
				case 4:
				case 7:
				case 9:
					$newStr .= $dit . " ";
					break;
				default:
					$newStr .= $dit;
					break;
			}
		}
		return $newStr;
	}


	function IsFreeUsersNULLandNO30( string $responsibility, int $n, string $member_id ): string
	{
		return '
require_once __DIR__ . "/../../../../../bitrix/Controller/Outgoing/File.php";
$referrer = \'' . $this->referrer . '\';
$Phone = ' . $this->Phone . ';
$responsibility = ' . $responsibility . ';
$n = ' . $n . ';
$member_id = "' . $member_id . '";
$FILE = new \Autocall\bitrix\File ($referrer, $Phone, $member_id);
$FILE->falseIsFreeUsers($responsibility, $n, $member_id);
';

	}

	function Render_DATA_NULL_(): string
	{
		return '
require_once __DIR__ . "/../../../../../bitrix/Controller/Outgoing/File.php";
$referrer = \'' . $this->referrer . '\';
$Phone = ' . $this->Phone . ';

$FILE = new \Autocall\Bitrix\File($referrer, $Phone);
$FILE->trueIsFreeUsers();
';
	}


}