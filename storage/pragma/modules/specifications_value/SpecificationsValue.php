<?php


namespace Storage;

require_once __DIR__ . '/SpecificationsValueSchema.php';
require_once __DIR__ . '/../../business_rules/specifications_value/iSpecificationsValue.php';

class SpecificationsValue extends SpecificationsValueSchema implements iSpecificationsValue
{

    public function __construct(private IStoreApp $app) {
        parent::__construct($this->app->getPragmaAccountId());
    }

    function getSpecificationValue(int $category_id, int $product_id): array
    {
        return $this->getSpecificationVal($category_id, $product_id);
    }

    function setSpecificationValue(int $specification_id, int $product_id, string $value): void
    {
        $this->setSpecificationVal($specification_id, $product_id, $value);
    }

    function updateSpecificationValue(array $model): bool
    {

    }

    function deleteSpecificationValue(int $id): void
    {
        // TODO: Implement deleteSpecificationValue() method.
    }

}