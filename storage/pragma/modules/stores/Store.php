<?php


namespace Storage;


require_once __DIR__ . '/../../business_rules/stores/iStore.php';
require_once __DIR__ . '/../../PragmaFactory.php';


class Store implements iStore {
	private iStores $stores;
	private int $store_id;
	private string $title;
	private string $address;
	private int $deleted;

	public function __construct(private IStoreApp $app, iStores $store, array $model) {
		$this->stores = $store;
		$this->store_id = $model['store_id'];
		$this->title = $model['title'];
		$this->address = $model['address'];
		$this->deleted = $model['deleted'] ?? UNARCHIVED_STATUS;
	}

	public function getStoreId(): int {
		return $this->store_id;
	}

	public function getTitle(): string {
		return $this->title;
	}

	public function getAddress(): string {
		return $this->address;
	}

	function isDeleted(): bool {
		return $this->deleted === ARCHIVED_STATUS;
	}

	function getArchiveStatus(): int {
	    return $this->deleted;
    }

	function delete(): bool {
		return $this->stores->deleteStore($this);
	}

	function recover() {
		// TODO: Implement recover() method.
	}

	function toArray(): array {
		return [
			'store_id' => $this->getStoreId(),
			'title' => $this->getTitle(),
			'address' => $this->getAddress(),
			'deleted' => $this->getArchiveStatus(),
		];
	}

	function update(array $model): bool {
		foreach ($model as $field_name => $val)
			switch ($field_name) {
				case 'title':
					$this->title = $val;
					break;
				case 'address':
					$this->address = $val;
					break;
			}
		$this->stores->save($this);
		return true;
	}

	function getOwnImports(): array {
		return self::getImports()->getImports($this);
	}

	function getOwnProductImports(): array {
		$imports = $this->getOwnImports();
		foreach ($imports ?? [] as $import)
			$ids[] = $import->getImportId();
		return isset($ids) ? $this->getProductImports()->getAllImportProductImports($ids) : [];
	}

	private function getProductImports(): iProductImports {
		return $this->app->getProductImports();
	}

	private function getImports(): iImports {
		return $this->app->getImports();
	}

	function getOwnCategoriesId(): array {
		return $this->app->getCategoriesToStores()->getCategoriesIdInStore($this->getStoreId());
	}

	function getOwnProductsId(): array {
		return $this->app->getCategoriesToStores()->getProductsIdInStore($this->getStoreId());
	}

	function setDeleted(): void {
		$this->deleted = ARCHIVED_STATUS;
	}

    function restore(): void {
        $this->deleted = UNARCHIVED_STATUS;
        $this->stores->save($this);
    }

	function addCategory(iCategory $category): void {
		$this->app->getCategoriesToStores()->saveCategoryLinks($category->getCategoryId(), [$this->getStoreId()]);
	}

	function linkProducts(array $product_id): void {
		$linker = $this->getLinker($product_id);
		$linker->link();
	}

	function unlinkProducts(array $product_id): void {
		$linker = $this->getLinker($product_id);
		$linker->unlink();
	}

	private function getLinker(array $product_id): ProductsToStores {
		return new ProductsToStores(Factory::getPragmaAccountId(), $this->getStoreId(), $product_id);
	}
}