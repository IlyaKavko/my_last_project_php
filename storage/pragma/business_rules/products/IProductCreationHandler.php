<?php


namespace Storage;


interface IProductCreationHandler {
	function productCreateEvent(iProduct $product): void;
}