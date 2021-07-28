<?php


namespace Storage;


interface iSpecificationsStruct
{
    function getIdSpecifications(): int;

    function getCategoryId(): int;

    function getTitleSpecifications(): string;

    function toArray(): array;
}