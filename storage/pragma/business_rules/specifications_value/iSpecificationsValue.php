<?php


namespace Storage;


interface iSpecificationsValue
{
    function getSpecificationValue(int $category_id, int $product_id): array;

    function setSpecificationValue(int $specification_id, int $product_id, string $value): void;

    function updateSpecificationValue(array $model): bool;

    function deleteSpecificationValue(int $id): void;
}