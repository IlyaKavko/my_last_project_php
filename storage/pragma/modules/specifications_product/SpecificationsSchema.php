<?php


namespace Storage;
require_once __DIR__ . '/../db/PragmaStoreDB.php';

class SpecificationsSchema extends PragmaStoreDB
{
    private static int $account_id;

    public function __construct(int $account_id)
    {
        parent::__construct();
        self::$account_id = $account_id;
    }

    protected function getSpecificationSchema(int $category_id = null): array
    {
        $sql = $this->sql($category_id);
        return self::querySql($sql);
    }

    protected function setSpecification(int $category_id, string $title): void
    {
        $this->validTitle($category_id, $title);
        $specifications = self::getSpecificationsSchema();
        $sql = "INSERT INTO $specifications (category_id, title)
				VALUE ($category_id, :title)";
        if (!self::execute($sql, ['title' => $title]))
            throw new \Exception('Failed to create new Specifications');
    }

    protected function updateSpecification(int $specifications_id, string $title): void
    {
        $title = trim($title);
        $specifications = self::getSpecificationsSchema();
        $sql = "UPDATE $specifications SET title = :title WHERE specifications_id = $specifications_id";
        if (!self::execute($sql, ['title' => $title]))
            throw new \Exception('Failed to update Specifications');
    }

    protected function deleteSpecification(int $specifications_id): void
    {
        $specifications = self::getSpecificationsSchema();
        $sql = "DELETE FROM $specifications WHERE $specifications.`specifications_id` = $specifications_id";
        self::executeSql($sql);
    }

    private function validTitle(int $catygory_id, string $title): void
    {
        if (!$title)
            throw new \Exception('Title cannot be empty');
        if ($this->findeSpecification($catygory_id, $title))
            throw new \Exception('Such a specifications with this name already exists');
    }

    public function findeSpecification(int $category_id, string $title)
    {
        $title = trim($title);
        foreach ($this->getSpecificationSchema($category_id) as $specifications)
            if ($specifications['title'] === $title)
                return $specifications;
        return null;
    }

    private function sql(int $category_id = null): string
    {
        $category = self::getStorageCategoriesSchema();
        $specifications = self::getSpecificationsSchema();
        return $category_id == null ? "SELECT * FROM $specifications
				WHERE category_id IN (SELECT id FROM $category WHERE account_id =" . self::$account_id . ")" :
            "SELECT * FROM $specifications
				WHERE category_id = $category_id";
    }
}