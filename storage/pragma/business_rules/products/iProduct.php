<?php


namespace Storage;


interface iProduct extends iBasis
{
    function getProductId(): int;
    function getCategoryId(): int;
    function getTitle() : string;
	function getUnit() : string;
    function getArticle() : string;
    function getSellingPrice(): float;
    function getExports () : array;
    function getOwnedProductImports() : array;
    function getFreeQuantity(int $store_id): float;
    function getLinkedStores(): array;
}