<?php


namespace Storage;


interface iDiscount
{
    function saveDiscount(int $id, int $discount, float $full_price): int;

    function getDiscounts(): array;

    function getDiscountOnId(int $id): array;

    function deleteDiscount(int $product_export_id): void;

}