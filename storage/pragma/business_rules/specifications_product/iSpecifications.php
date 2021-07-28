<?php


namespace Storage;


interface iSpecifications
{
	function getSpecifications(int $category_id = null): array|null;

	function setSpecifications(int $categories_id, string $title): void;

	function updateSpecifications(int $specifications_id, string $title): void;

	function deleteSpecifications(int $specifications_id): void;
}