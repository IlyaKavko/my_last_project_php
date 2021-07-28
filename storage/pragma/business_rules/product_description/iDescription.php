<?php


namespace Storage;


interface iDescription
{
    function setDescription(int $product_id, string $description): void;

    function getDescription(int $product_id): array;
}