<?php


namespace Autocall\Pragma;


interface iLiraxAdditionallySettings
{
    function getSettingsStruct(): iLiraxAdditionallySettingsStruct;

//    function saveTimeResponsible(array $quantity): void;

    function saveWorkTime(array $array_calls): void;

    function saveQuantityCallClient(array $quantity): void;

    function saveNumberOfCallAttempts(array $array_calls): void;

    function saveLeadPipeline(array $array_pipeline): void;

    function saveArrayUsePipelineShops(array $array_pipeline): void;

    function saveArrayUsePipelineNumbers(array $array_pipeline): void;

    function saveArrayUsePriority(array $array_pipeline): void;

}