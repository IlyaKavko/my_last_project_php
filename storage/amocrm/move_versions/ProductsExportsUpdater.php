<?php


namespace Storage\Move;


use const Storage\DEFAULT_SOURCE;

class ProductsExportsUpdater extends \Storage\PragmaStoreDB {
    static function update(): void {
        self::updateClientType();
    }

    private static function updateClientType(): void {
        $schema = self::getStorageProductExportsSchema();
        $client_type = DEFAULT_SOURCE;
        $sql = "UPDATE $schema SET client_type = $client_type WHERE client_type = 0";
        self::executeSql($sql);
    }
}