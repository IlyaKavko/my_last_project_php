<?php


namespace Storage;

require_once __DIR__ . '/TechnologicalCardProductsSchema.php';
require_once __DIR__ . '/../../business_rules/technological_cards_products/iTechnologicalCardProducts.php';

class TechnologicalCardProducts extends TechnologicalCardProductsSchema implements iTechnologicalCardProducts
{
    public function __construct(private IStoreApp $app)
    {
        parent::__construct($this->app->getPragmaAccountId());
    }

    function setTechnologicalCardProducts(int $technological_card_id, int $product_id, int $quantity): void
    {
        $this->setDataOfTechnologicalCardProducts($technological_card_id, $product_id, $quantity);
    }

}