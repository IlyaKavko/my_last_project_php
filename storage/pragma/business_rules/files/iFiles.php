<?php


namespace Storage;


interface iFiles
{
    function saveProductsFile(string $name_file,int $product_id, $tmp_name): string;

    function deleteProductsFile(int $file_id): void;

    function deleteProductsFiles(int $product_id): void;

    function getProductsFiles(int $product_id): string;
}