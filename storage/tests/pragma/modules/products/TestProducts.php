<?php


namespace Storage\Test;


use Storage\iProduct;

require_once __DIR__ . '/../../../../pragma/modules/products/Products.php';

class TestProducts extends \Storage\Products {
	function uniqueProduct(): iProduct {
		$category = TestPragmaFactory::getTestCategories()->createCategory('sdfsdf');
		return $this->createProduct($category->getCategoryId(), uniqid('sdfsd'), uniqid('sdfsd'), 5.5);
	}
}