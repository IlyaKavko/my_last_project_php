<?php


namespace Storage;


class TechnologicalCardSchema extends PragmaStoreDB
{
    private int $account_id;

    public function __construct(int $account_id)
    {
        parent::__construct();
        $this->account_id = $account_id;
    }

    protected function setTechnologicalCards(string $title): array
    {
        $this->validTitle($title);
        $technologicalCardSchema = self::getTechnologicalCardSchema();
        $sql = "INSERT INTO $technologicalCardSchema (title, account_id) VALUES (:title, $this->account_id)";
        self::executeSql($sql, ['title' => $title]);

        $sqlGetModel = "SELECT * FROM $technologicalCardSchema WHERE account_id = $this->account_id AND title = '" .$title."'";
        return self::querySql($sqlGetModel);
    }

    protected function getTechnologicalCard(): array
    {
        $technologicalCardSchema = self::getTechnologicalCardSchema();
        $technologicalCardProductSchema = self::getTechnologicalCardProductSchema();
        $productsSchema = self::getStorageProductsSchema();

        $sql = "SELECT 
                    tc_title.id technological_card_id,
                    tc_title.title,
                    pr.id product_id,
                    pr.title product_title,
                    tc_products.quantity,
                    pr.unit

                FROM $technologicalCardProductSchema tc_products 
        
                JOIN $technologicalCardSchema tc_title ON tc_title.id = tc_products.technological_card_id 
        
                JOIN $productsSchema pr ON pr.id = tc_products.product_id";

        return self::querySql($sql);
    }

    private function validTitle(string $title): void
    {
        if (!$title)
            throw new \Exception('Title cannot be empty');
        if ($this->findSpecification($title))
            throw new \Exception('Such a specifications with this name already exists');
    }

    public function findSpecification(string $title)
    {
        $title = trim($title);
        foreach ($this->getSpecificationSchema() as $specifications)
            if ($specifications['title'] === $title)
                return $specifications;
        return null;
    }

    protected function getSpecificationSchema(): array
    {
        $getTechnologicalCardSchema = self::getTechnologicalCardSchema();
        $sql = "SELECT * FROM $getTechnologicalCardSchema
				WHERE account_id = $this->account_id";
        return self::querySql($sql);
    }

}