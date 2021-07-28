<?php


namespace Storage;


interface iTechnologicalCardProducts
{
    function setTechnologicalCardProducts(int $technological_card_id, int $product_id, int $quantity): void;
}