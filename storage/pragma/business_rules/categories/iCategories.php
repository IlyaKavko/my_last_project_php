<?php


namespace Storage;


interface iCategories {
    function createCategory (string $title, array $stores_id = [], array $specifications = []) : iCategory;
	function save(ICategoryStruct $category): void;
    function delete (iCategory $category) : bool;
    function findCategory (string $title);
    function getCategory (int $category_id) : iCategory;
    function getCategories () : array;
}