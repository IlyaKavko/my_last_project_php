<?php


namespace Storage\Exports\Tests;


use Storage\iExport;
use Storage\iStore;
use Storage\IStoreExportPriority;
use Storage\StorePriorities;
use Storage\StorePriorityModel;
use Storage\Test\ExportsCreator;
use Storage\Test\TestPragmaFactory;

require_once __DIR__ . '/../../../../pragma/modules/priorities/StorePriorityModel.php';
require_once __DIR__ . '/../../../../pragma/modules/priorities/StorePriorities.php';

trait PrioritiesCreator {
	use ExportsCreator;

	static function uniquePriorityModels(array $stores, iExport $export = null): array {
		$stores = array_values($stores);
		$export = $export ?? self::getUniqueExport();
		foreach ($stores as $index => $store)
			$models[] = new StorePriorityModel(['store_id' => $store->getStoreId(), 'export_id' => $export->getExportId(), 'sort' => $index]);
		$fabric = new StorePriorities(TestPragmaFactory::getStoreApp());
		$fabric->savePriorities($export->getExportId(), $models);
		return $fabric->getPriorities([$export->getExportId()])[$export->getExportId()];
	}

	static function uniquePriorityModel(int $sort, iStore $store = null, iExport $export = null): IStoreExportPriority {
		$store = $store ?? self::getUniqueStore();
		$export = $export ?? self::getUniqueExport();
		return new StorePriorityModel(['store_id' => $store->getStoreId(), 'export_id' => $export->getExportId(), 'sort' => $sort]);
	}

	static function clearPriorities(): void {
		self::clearExports();
		self::clearStores();
	}
}