<?php


namespace Storage\Test;


use Storage\DetailsCreator;
use Storage\ProductImportSchema;

require_once __DIR__ . '/../../TestPragmaFactory.php';

class ProductsImportsSchemaTest extends \PHPUnit\Framework\TestCase {
	use DetailsCreator;

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();
		TestPragmaFactory::ifInitTest();
		self::$default_pimport_quantity = 10;
		self::setDefaultExportQuantity(4.5);
		self::setDefaultSellingPrice(1);
	}

	protected function tearDown(): void {
		parent::tearDown();
	}

	function testSetBalanceQuantity(){
		self::setDefaultProduct(self::getUniqueProduct());
		$product_import = self::getUniqueProductImport(self::getUniqueImport());
		$this->assertEquals(10, $product_import->getFreeBalanceQuantity());
		$detail1 = self::uniqueDetail(null, $product_import);
		$detail2 = self::uniqueDetail(null, $product_import);
		$this->assertEquals(9, $detail1->getQuantity() + $detail2->getQuantity());
		$this->assertEquals(1, $product_import->getFreeBalanceQuantity());
	}
}