<?php


namespace Autocall\Pragma;


interface iLiraxSettings
{
    function getSettingsStruct(): iLiraxSettingsStruct;

    function saveToken(string $token): void;

    function saveUseLead(string $use): void;

    function saveUseStore(string $use): void;

    function saveUseNumber(string $use): void;

    function saveUsePriory(string $use): void;

    function saveUseResponsible(string $quantity): void;

    function saveReferrer(string $referrer): void;

    function saveApplication(int $id):void;

    function setPipelineId(int $id): void;

    function createNewUser():bool;

	function saveGeneralSettings(array $arrSettings = []): bool;

}