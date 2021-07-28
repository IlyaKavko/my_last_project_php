<?php


namespace Storage;


interface iSettings
{
    function setFractional(string $fractional): void;

    function getFractional(): bool;

    function setStock(int $id): void;

    function getStock(): int;

    function getFirstStockId(): int;

    function setHandbook(array $handbooks): void;

    function getHandbook(): array;

}