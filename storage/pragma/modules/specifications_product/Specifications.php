<?php


namespace Storage;
require_once __DIR__ . '/SpecificationsSchema.php';
require_once __DIR__ . '/../../business_rules/specifications_product/iSpecifications.php';
require_once __DIR__ . '/SpecificationsBuffer.php';
class Specifications extends SpecificationsSchema implements iSpecifications
{
	use SpecificationsBuffer;

	public function __construct(private IStoreApp $app) {
		parent::__construct($this->app->getPragmaAccountId());
	}

	function getSpecifications(int $category_id = null): array | null // возращает структуру базы specifications_product
	{
		$models = $this->getSpecificationSchema($category_id);
		foreach ($models as $model)
		{
			$specifications[] = $this->findInBuffer($model['specifications_id']) ?? $this->_create($model);
		}
		return $specifications;
	}

	function setSpecifications( int $categories_id, string $title ): void
	{
		$this->setSpecification($categories_id, $title);
	}

	function updateSpecifications( int $specifications_id, string $title ): void
	{
        $this->updateSpecification($specifications_id, $title);
    }

	function deleteSpecifications( int $specifications_id ): void
	{
		$this->deleteFromBuffer($specifications_id);
        $this->deleteSpecification($specifications_id);
	}

	function _create(array $model): iSpecificationsStruct
	{
		$specification = $this->findInBuffer($model['specifications_id']) ?? new SpecificationsStruct($model);
		$this->addBuffer($specification);
		return $specification;
	}

}