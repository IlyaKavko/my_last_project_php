<?php


namespace Storage;


require_once __DIR__ . '/../basics/iBasis.php';
require_once __DIR__ . '/ICategoryStruct.php';


interface iCategory extends iBasis, ICategoryStruct {
    function setStores(array $store_id): void;
}