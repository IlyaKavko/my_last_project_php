<?php


namespace Storage;


class TechnologicalCardProductsSchema extends PragmaStoreDB
{
    public function __construct(int $account_id)
    {
        parent::__construct();
    }

    protected function setDataOfTechnologicalCardProducts(int $technological_card_id, int $product_id, int $quantity): void
    {
        $technologicalCardProductSchema = self::getTechnologicalCardProductSchema();
        $sql ="INSERT INTO $technologicalCardProductSchema (technological_card_id, product_id, quantity) VALUES ($technological_card_id, $product_id, $quantity)";
        self::executeSql($sql);
    }
}