<?php


namespace Storage;


interface ICategoryStruct {
	function getCategoryId(): int;
	function getTitle(): string;
	function linkedStoreId(): array;
}