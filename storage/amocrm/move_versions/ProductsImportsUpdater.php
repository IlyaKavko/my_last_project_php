<?php


namespace Storage\Move;


use Storage\ProductImportBalanceUpdater;
use const Storage\DEFAULT_SOURCE;
use const Storage\DEFICIT_SOURCE;

require_once __DIR__ . '/../../pragma/modules/product_imports/ProductImportBalanceUpdater.php';

class ProductsImportsUpdater extends \Storage\PragmaStoreDB {
    use ProductImportBalanceUpdater;
    static function update(): void {
        self::updateSource();
        self::updateQuantities();
        self::createDeficitImports();
    }

    private static function updateSource(): void {
        self::updateDefaultSource();
        self::updateDeficitSource();
    }

    private static function updateDefaultSource(): void {
        $schema = self::getStorageProductImportsSchema();
        $source = DEFAULT_SOURCE;
        $sql = "UPDATE $schema SET `source` = $source WHERE import_id IS NOT NULL AND `source` NOT IN(1,2,3)";
        self::executeSql($sql);
    }

    private static function updateDeficitSource(): void {
        $schema = self::getStorageProductImportsSchema();
        $source = DEFICIT_SOURCE;
        $sql = "UPDATE $schema SET `source` = $source WHERE import_id IS NULL";
        self::executeSql($sql);
    }

    private static function updateQuantities(): void {
        $all_imports = self::getAllId();
        foreach($all_imports as $row)
            self::updateBalance($row['id']);
    }

    private static function getAllId(): array {
        $product_imports = self::getStorageProductImportsSchema();
        $sql = "SELECT
                   $product_imports.`id`
                FROM $product_imports
                WHERE 1";
        return self::querySql($sql);
    }

    private static function createDeficitImports(): void {
        $accounts = self::getAccounts();
        foreach($accounts as $account)
            self::addDeficitImport($account['account_id']);
    }

    private static function getAccounts(): array {
        $stores = self::getStorageStoresSchema();
        $sql = "SELECT account_id FROM $stores WHERE 1 GROUP BY account_id";
        return self::querySql($sql);
    }

    private static function addDeficitImport(int $account_id): void {
        $import_id = self::getDeficitImportId($account_id);
        $deficits = self::getDeficits($account_id);
        self::saveDeficitImportIdByChunk($import_id, $deficits);
    }

    private static function getDeficits(int $account_id): array {
        $productsImports = self::getStorageProductImportsSchema();
        $products = self::getStorageProductsSchema();
        $sql = "SELECT id FROM $productsImports WHERE product_id IN (SELECT id FROM $products WHERE account_id = $account_id) AND import_id IS NULL";
        return self::querySql($sql);
    }

    private static function getDeficitImportId(int $account_id): int {
        $store_id = self::getStoreIdOfAccount($account_id);
        return self::findDeficitImport($store_id) ?? self::createDeficitImport($account_id, $store_id);
    }

    private static function getStoreIdOfAccount(int $account_id): int {
        $stores = self::getStorageStoresSchema();
        $sql = "SELECT MIN(id) as id FROM $stores WHERE account_id = $account_id";
        return self::querySql($sql)[0]['id'];
    }

    private static function findDeficitImport(int $store_id): int|null {
        $imports = self::getStorageImportsSchema();
        $deficit_flag = DEFICIT_SOURCE;
        $sql = "SELECT id FROM $imports WHERE store_id = $store_id AND `source` = $deficit_flag";
        return self::querySql($sql)[0]['id'] ?? null;
    }

    private static function createDeficitImport(int $account_id, int $store_id): int {
        $import_schema = parent::getStorageImportsSchema();
        $number = self::getNextImportNumber($account_id);
        $source = DEFICIT_SOURCE;
        $sql = "INSERT INTO $import_schema (`store_id`, `number`, `source`)
				VALUES($store_id, $number, $source)";
        self::executeSql($sql);
        return self::last_id();
    }

    private static function getNextImportNumber(int $account_id): int {
        $imports = parent::getStorageImportsSchema();
        $stores = parent::getStorageStoresSchema();

        $sql = "SELECT 
                    MAX($imports.`number`) AS `max_number` 
                FROM $stores
                    INNER JOIN $imports ON $imports.`store_id` = $stores.`id`
                WHERE $stores.`account_id` = $account_id";

        $number = self::query($sql)[0]['max_number'] ?? 0;
        return $number + 1;
    }

    private static function saveDeficitImportIdByChunk(int $import_id, array $products_imports): void {
        $chunks = array_chunk($products_imports, 100);
        foreach($chunks as $chunk)
            self::saveDeficitImportId($import_id, $chunk);
    }

    private static function saveDeficitImportId(int $import_id, array $products_imports): void {
        $str_values = self::strValues($products_imports);
        if(!$str_values) return;
        $productsImportsSchema = self::getStorageProductImportsSchema();
        $sql = "UPDATE $productsImportsSchema SET import_id = $import_id WHERE id IN ($str_values) AND import_id IS NULL";
        self::executeSql($sql);
    }

    private static function strValues(array $products_imports): string {
        foreach($products_imports as $product_import)
            $values[] = $product_import['id'];
        return implode(',', $values ?? []);
    }

    private static function getImportedQuantity(int $product_import_id): float {
        $products_imports = self::getStorageProductImportsSchema();
        $sql = "SELECT quantity FROM $products_imports WHERE id = $product_import_id";
        return self::querySql($sql)[0]['quantity'] ?? 0.0;
    }
}