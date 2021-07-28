<?php


namespace Autocall\Pragma;


interface iLiraxCore
{
    function getLiraxCoreStorage(): iLiraxCoreStorage;


    function setStatus(int $status): void;
    function getStatus(): int;


    function setMode(bool $mode): void;
    function getMode(): int;

    function getPhoneStruct(): iLiraxCoreStruct;

    function getWorkTime(): int;

    function setLiraxAdditionallySettings(iLiraxAdditionallySettings $settings): void;
}