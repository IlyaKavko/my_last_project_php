<?php


namespace Storage;
require_once __DIR__ . '/../../business_rules/specifications_product/iSpecificationsStruct.php';

class SpecificationsStruct implements iSpecificationsStruct
{
    private $id;
    private $category_id;
    private $title;

    public function __construct(array $specifications_schema)
    {
        $this->id = $specifications_schema['specifications_id'];
		$this->category_id = $specifications_schema['category_id'];
        $this->title = $specifications_schema['title'];
    }

    function getIdSpecifications(): int
    {
        return $this->id;
    }

    function getCategoryId(): int
    {
	    return $this->category_id;
    }

	function getTitleSpecifications(): string
    {
        return $this->title;
    }

	function toArray(): array
    {
	    return ['id' => $this->getIdSpecifications(), 'category_id' => $this->getCategoryId(), 'title' => $this->getTitleSpecifications(),];
    }
}