<?php


namespace Autocall\Bitrix;


interface iBitrixGetLead
{
    function getName(): string;
    function getPhone(): int;
    function getPipelineID(): int;
}