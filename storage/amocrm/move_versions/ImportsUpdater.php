<?php


namespace Storage\Move;


use const Storage\DEFAULT_SOURCE;

class ImportsUpdater extends \Storage\PragmaStoreDB {
    static function update(): void {
        self::updateSource();
    }

    private static function updateSource(): void {
        $imports = self::getStorageImportsSchema();
        $source = DEFAULT_SOURCE;
        $sql = "UPDATE $imports SET `source` = $source WHERE `source` AND `source` NOT IN(1,2,3)";
        self::executeSql($sql);
    }
}