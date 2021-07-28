<?php


namespace Storage;


interface iAmocrmCatalog extends iCatalog {
	function getEntityExportModels(string $entity_type, string $entity_id): array;
}