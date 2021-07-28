<?php


namespace Storage;
require_once __DIR__ . '/../db/PragmaStoreDB.php';
require_once __DIR__ . '/../specifications_product/SpecificationsSchema.php';

class SpecificationsValueSchema extends PragmaStoreDB
{
    public function __construct(int $account_id)
    {
        parent::__construct();
    }

    protected function getSpecificationVal(int $category_id, int $product_id): array
    {
        $specifications = self::getSpecificationsSchema();
        $specificationsValue = self::getSpecificationValueSchema();
        $sql = "SELECT * FROM $specificationsValue
                WHERE specification_id IN (SELECT specifications_id FROM $specifications WHERE category_id = $category_id) AND product_id = $product_id";
        return self::querySql($sql);
    }

    protected function setSpecificationVal(int $specification_id, int $product_id, string $value): void
    {
        $flag = $this->validValue($specification_id, $product_id);
        $specifications = self::getSpecificationValueSchema();

        if ($flag) {
            $sql = "UPDATE $specifications SET value = :value WHERE specification_id = $specification_id AND product_id = $product_id";
        } else {
            $sql = "INSERT INTO $specifications (specification_id, product_id, value)
                VALUE ($specification_id, $product_id, :value)";
        }

        if (!self::execute($sql, ['value' => $value]))
            throw new \Exception('Failed to update Specifications');
    }

    protected function validValue(int $specification_id, int $product_id): bool
    {
        $specificationsValue = self::getSpecificationValueSchema();
        $sql = "SELECT * FROM $specificationsValue WHERE specification_id = $specification_id AND product_id = $product_id";
        $req = self::querySql($sql);
        if (count($req) >= 1) {
            return true;
        } else {
            return false;
        }
    }
}