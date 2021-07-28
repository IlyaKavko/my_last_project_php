<?php


namespace Storage;

require_once __DIR__ . '/DescriptionSchema.php';
require_once __DIR__ . '/../../business_rules/product_description/iDescription.php';

class Description extends DescriptionSchema implements iDescription
{
    public function __construct(private IStoreApp $app) {
        parent::__construct($this->app->getPragmaAccountId());
    }

    function setDescription(int $product_id, string $description): void
    {
        $this->setDescriptions($product_id, $description);
    }

    function getDescription(int $product_id): array
    {
        return $this->getDescriptions($product_id);
    }
}