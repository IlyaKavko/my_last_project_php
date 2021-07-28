<?php


namespace Storage;


interface ICategoryStoreLinkStruct {
	function getStoreId(): int;
	function getCategoryId(): int;
	function getLinkStatus(): int;
	function setIsUnarvhived(): void;
	function setIsArchived(): void;
}