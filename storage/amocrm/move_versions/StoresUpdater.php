<?php


namespace Storage\Move;


use Storage\CategoryStoreLinkStruct;
use const Storage\UNARCHIVED_STATUS;

class StoresUpdater extends \Storage\PragmaStoreDB {
    static function update(): void {
        self::updateArchived();
        self::createCategoriesToStoresLinks();
    }

    private static function updateArchived(): void {
        $schema = self::getStorageStoresSchema();
        $deleted = UNARCHIVED_STATUS;
        $sql = "UPDATE $schema SET deleted = $deleted WHERE deleted = 0";
        self::executeSql($sql);
    }

    private static function createCategoriesToStoresLinks(): void {
        $accounts = self::getAccounts();
        foreach($accounts as $account)
            self::updateForAccount($account['account_id']);
    }

    private static function getAccounts(): array {
        $schema = self::getStorageCategoriesSchema();
        $sql = "SELECT account_id FROM $schema WHERE 1 GROUP BY account_id";
        return self::querySql($sql);
    }

    private static function updateForAccount(int $account_id): void {
        $stores = self::getStores($account_id);
        $categories = self::getCategories($account_id);
        $structs = self::createStructs($stores, $categories);
        self::saveByPack($structs);
    }

    private static function getStores(int $account_id): array {
        $stores = self::getStorageStoresSchema();
        $sql = "SELECT id FROM $stores WHERE account_id = $account_id";
        return self::querySql($sql);
    }

    private static function getCategories(int $account_id): array {
        $categories = self::getStorageCategoriesSchema();
        $sql = "SELECT id FROM $categories WHERE account_id = $account_id";
        return self::querySql($sql);
    }

    private static function createStructs(array $stores, array $categories): array {
        foreach($stores as $store)
            foreach($categories as $category)
                $structs[] = self::createStruct($store['id'], $category['id']);
        return $structs ?? [];
    }

    private static function createStruct(int $store_id, int $category_id): CategoryStoreLinkStruct {
        return new CategoryStoreLinkStruct(['store_id' => $store_id, 'category_id' => $category_id, 'link_status' => UNARCHIVED_STATUS]);
    }

    protected static function saveByPack(array $structs): void {
        $chunks = array_chunk($structs, 100);
        foreach($chunks as $chunk)
            self::saveLinkRows($chunk);
    }

    protected static function saveLinkRows(array $structs): void {
        $strValues = self::strValues($structs);
        if(!$strValues) return;
        $links = self::getStorageCategoriesToStoresSchema();
        $sql = "INSERT INTO $links (store_id, category_id, status)
				VALUES $strValues
				ON DUPLICATE KEY UPDATE
					store_id = VALUES(store_id),
					category_id = VALUES(category_id)";
        self::executeSql($sql);
    }

    private static function strValues(array $structs): string {
        foreach ($structs as $struct)
            $arr[] = '(' . $struct->getStoreId() . ',' . $struct->getCategoryId() . ',' . $struct->getLinkStatus() . ')';
        return implode(',', $arr ?? []);
    }
}