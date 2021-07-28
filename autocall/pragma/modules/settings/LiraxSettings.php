<?php


namespace Autocall\Pragma;
require_once __DIR__ . '/LiraxSettingsSchema.php';
require_once __DIR__ . '/../../business_rules/settings/iLiraxSettings.php';
require_once __DIR__ . '/LiraxSettingsStruct.php';

class LiraxSettings implements iLiraxSettings
{
	protected LiraxSettingsSchema $general;
	protected int $pragma_account_id;

	public function __construct(int $pragma_account_id)
	{
		$this->pragma_account_id = $pragma_account_id;
		$this->general = new LiraxSettingsSchema($pragma_account_id);
	}

	function getSettingsStruct(): iLiraxSettingsStruct
	{
		$settings_model = $this->general->getAccountAutocallSettings()[0];

		if ($settings_model === null) {
			$this->saveGeneralSettings();
			$settings_model = $this->general->getAccountAutocallSettings()[0];
		}


		return new LiraxSettingsStruct(
			$settings_model['token'],
			$settings_model['use_store'],
			$settings_model['use_number'],
			$settings_model['use_priory'],
			$settings_model['use_responsible'],
			$settings_model['application'],
			$settings_model['use_lead'],
		);
	}

	function saveGeneralSettings(array $arrSettings = []): bool
	{
		return $this->general->setAutcallSettings($arrSettings);
	}

	function createNewUser(): bool
	{
		return $this->general->createNewUser();
	}

	function getPipeline():array
	{
		return $this->general->getPipelines();
	}

	function savePipeline(int $id): void
	{
		$this->general->setPipelines($id);
	}

	function deletePipeline(int $id): void
	{
		$this->general->deletePipeline($id);
	}

	function saveUseLead( string $use ): void
	{
		// TODO: Implement saveUseLead() method.
	}

	function saveUseStore( string $use ): void
	{
		// TODO: Implement saveUseStore() method.
	}

	function saveUseNumber( string $use ): void
	{
		// TODO: Implement saveUseNumber() method.
	}

	function saveUsePriory( string $use ): void
	{
		// TODO: Implement saveUsePriory() method.
	}

	function saveUseResponsible( string $quantity ): void
	{
		// TODO: Implement saveUseResponsible() method.
	}

	function saveReferrer( string $referrer ): void
	{
		// TODO: Implement saveReferrer() method.
	}

	function saveApplication( int $id ): void
	{
		// TODO: Implement saveApplication() method.
	}

	function setPipelineId( int $id ): void
	{
		// TODO: Implement setPipelineId() method.
	}

	function saveToken( string $token ): void
	{
		// TODO: Implement saveToken() method.
	}
}