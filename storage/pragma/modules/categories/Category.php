<?php


namespace Storage;


require_once __DIR__ . '/../../business_rules/categories/iCategory.php';


class Category implements iCategory {
	private iCategories $categories;
	private int $category_id;
	private string $title;
	private bool $deleted;

	public function __construct(iCategories $categories, array $model) {
		$this->categories = $categories;
		$this->category_id = $model['category_id'];
		$this->title = $model['title'];
		$this->deleted = isset($model['deleted']) && !!$model['deleted'];
	}

	public function getCategoryId(): int {
		return $this->category_id;
	}

	function getTitle(): string {
		return $this->title;
	}

	function isDeleted(): bool {
		return $this->deleted;
	}

	function delete(): bool {
		return false;
	}

	function recover() {
		// TODO: Implement recover() method.
	}

	function toArray(): array {
		return ['category_id' => $this->getCategoryId(), 'title' => $this->getTitle(),];
	}

	function update(array $model): bool {
		foreach ($model as $field_name => $val)
			switch ( $field_name ) {
				case 'title':
					$this->title = trim($val);
					break;
				case 'specifications':
					$this->fetchSpecifications($val);
					break;
			}
		$this->getCategories()->save($this);
		return true;
	}

	private function getCategories(): iCategories {
		return $this->categories;
	}

    function setStores(array $store_id): void {
        $this->validStores($store_id);
        $this->clearOldStores($store_id);
        $this->addNewStores($store_id);
    }

    private function validStores(array $store_id): void {
	    foreach($store_id as $id)
	        PragmaFactory::getStores()->getStore($id);
    }

    private function clearOldStores(array $new_stores_id): void {
	    $to_delete_ids = array_diff($this->linkedStoreId(), $new_stores_id);
	    foreach($to_delete_ids as $store_id)
	        $this->deleteStoreLink($store_id);
    }

    private function deleteStoreLink(int $store_id): void {
        PragmaFactory::getStoreApp()->getCategoriesToStores()->deleteLink($this->getCategoryId(), $store_id);
    }

    private function addNewStores(array $new_stores_id): void {
	    PragmaFactory::getStoreApp()->getCategoriesToStores()->saveCategoryLinks($this->getCategoryId(), $new_stores_id);
    }

	function linkedStoreId(): array {
		return PragmaFactory::getStoreApp()->getCategoriesToStores()->getStoresForCategory($this->getCategoryId());
	}

	private function fetchSpecifications(array $models): void
	{
		if (array_key_exists('specifications', $models))
		{
			$models_value = $models['specifications'];
			if ($models_value !== null) {
				foreach ( $models_value as $value )
					Factory::getAmocrmStorage()->specificationsUpdate($value['id'], $value['title']);
			}
		}

		if (array_key_exists('new_specifications', $models))
		{
			$models_value = $models['new_specifications'];
			if ($models_value !== null)
				foreach ($models_value as $value)
					Factory::getAmocrmStorage()->specificationsSet($this->getCategoryId(), $value);
		}
	}
}