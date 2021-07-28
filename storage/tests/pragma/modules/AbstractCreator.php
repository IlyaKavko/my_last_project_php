<?php


namespace Storage\Test;


use PragmaCRM\Test\iTestEntities;
use Storage\iCategories;
use Storage\iExportDetail;
use Storage\iExportDetails;
use Storage\iExports;
use Storage\iImports;
use Storage\iProductImports;
use Storage\iProducts;
use Storage\iStores;

trait AbstractCreator {
	static function getCategories () : iCategories {
		return TestPragmaFactory::getTestCategories();
	}

	static function getProducts() : iProducts {
		return TestPragmaFactory::getTestProducts();
	}

	static function getStores () : iStores {
		return TestPragmaFactory::getTestStores();
	}

	static function getImports() : iImports {
		return TestPragmaFactory::getTestImports();
	}

	static function getProductImports() : iProductImports {
		return TestPragmaFactory::getTestProductImports();
	}

	static function getExports() : iExports {
		return TestPragmaFactory::getTestExports();
	}

	static function getTestEntities() : iTestEntities {
		return TestPragmaFactory::getTestEntities();
	}

	static function getTestDetails() : iExportDetails {
		return TestPragmaFactory::getExportDetails();
	}

	static function getUniqueString () : string {
		return uniqid('string');
	}
}