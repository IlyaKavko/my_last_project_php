<?php


namespace Storage;


interface ICreationTravelModel {
	function getStartStoreId(): int;
	function getEndStoreId(): int;
	function getUserId(): int;
	function getTravelDate(): int;
}