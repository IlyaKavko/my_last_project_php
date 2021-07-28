<?php


namespace Storage;

require_once __DIR__ . '/../db/PragmaStoreDB.php';

class DescriptionSchema extends PragmaStoreDB
{
    private int $account_id;

    public function __construct(int $account_id)
    {
        parent::__construct();
        $this->account_id = $account_id;
    }

    protected function setDescriptions(int $product_id, string $description): void
    {
        $description = trim($description);
        $flag = $this->validValue($product_id);
        $descriptionSchema = self::getProductDescriptionSchema();

        if ($flag) {
            $sql = "UPDATE $descriptionSchema SET description = :description WHERE product_id = $product_id AND account_id = $this->account_id";
        } else {
            $sql = "INSERT INTO $descriptionSchema (account_id, product_id, description)
				VALUE ($this->account_id, $product_id, :description)";
        }

        self::executeSql($sql, ['description' => $description]);
    }

    protected function getDescriptions(int $product_id): array
    {
        $descriptionSchema = self::getProductDescriptionSchema();

        $sql = "SELECT * FROM $descriptionSchema
                WHERE product_id = $product_id";
        return self::querySql($sql);
    }

    protected function validValue(int $product_id): bool
    {
        $descriptionSchema = self::getProductDescriptionSchema();
        $sql = "SELECT * FROM $descriptionSchema WHERE product_id = $product_id AND account_id = $this->account_id";
        $req = self::querySql($sql);

        return count($req) >= 1;
    }
}