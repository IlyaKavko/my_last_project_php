<?php


namespace Storage;


require_once __DIR__ . '/../../../pragma/business_rules/iStorage.php';


interface iAmocrmStorage extends iStorage {
	function createExports(string $entity_type, int $entity_id, array $models): array;
	function createExport(string $entity_type, int $entity_id, int $product_id, float $quantity, float $selling_price): iExport;
	function getPipelinesOfAccount(): array;
}