<?php

use Storage\Factory;

require_once __DIR__ . '/../../../CONSTANTS.php';
require_once __DIR__ . '/../../lib/Factory.php';

class Stock
{
    static private array $changed_product_imports = [];

    public function __construct(string $REFERRER, $log_writer)
    {
        Factory::init(\Storage\WIDGET_NAME, $REFERRER, $log_writer);
    }

    function section_add(array $POST): array
    {
        $name_category = urldecode($POST['title']);
        $stores = $POST['stores'] ?? [];
        $specs = $POST['specs_new'] ?? [];

        $new_section = Factory::getAmocrmStorage()->createCategory($name_category, $stores, $specs);

        return $new_section->toArray();
    }

    function section_get(): array
    {
        return Factory::getCatalog()->getCategoriesModels();
    }

    function section_delete(array $POST): bool
    {
        $category_id = intval($POST['coming_id']);
        $storage_id = intval($POST['storage_id']);
        return Factory::getAmocrmStorage()->deleteCategory($category_id, $storage_id);

    }

    function section_restore(array $POST): bool
    {
        $category_id = intval($POST['coming_id']);
        $storage_id = intval($POST['storage_id']);
        return Factory::getAmocrmStorage()->restoreCategory($category_id, $storage_id);
    }


    function section_update(array $POST): bool
    {
        $category_id = intval($POST['coming_id']);
        $new_title = $POST['title'];
        $linked_stores = $POST['stores'] ?? null;
        $specs_new = $POST['specs_new'] ?? null;
        $specs = $POST['specs'] ?? null;
        $model_specifications = [
        	'specifications' => $specs,
	        'new_specifications' => $specs_new
        ];
	    $model = ['title' => $new_title , 'specifications' => $model_specifications];

        $category = Factory::getAmocrmStorage()->getCategory($category_id);

        is_array($linked_stores) && $category->setStores($linked_stores);

        return $category->update($model);
    }

    function specification_delete(array $POST): bool
    {
        $specifications_id = intval($POST['specification_id']);

        \Storage\Factory::getLogWriter()->add('$specifications_id', $specifications_id);

        Factory::getAmocrmStorage()->specificationsDelete($specifications_id);

        return true;
    }


    function specifications_get(array $POST): array
    {
        $category_id = intval($POST['category_id']);
        $product_id = $POST['product_id'] ?? null;
        $specifications = Factory::getAmocrmStorage()->specificationsGet($category_id);
        $arr = [];
        foreach ($specifications as $specification) {
	        array_push($arr, $specification->toArray());
        }
	    $result['specification_title'] = $arr;
//        if ($product_id !== null) {
//            $result['specification_value'] = Factory::getAmocrmStorage()->specificationsValueGet($category_id, $product_id);
//        }

        return $result;

    }

    static function specifications_set(int $category_id, array $specs): void
    {
        foreach ($specs as $spec) {
            Factory::getAmocrmStorage()->specificationsSet($category_id, $spec);
        }
    }


    function save_product(array $POST): array
    {
        $category_id = intval($POST['section_id']); // int
        $article = urldecode($POST['article']);// str
        $title = urldecode($POST['title']); //str
        $description = $POST['desc'] ?? null; // int
        $unit = urldecode($POST['unit']);
        $selling_price = floatval($POST['selling_price']);
        //float
        $model = array(
            'unit' => $unit
        );
        $specs_new = $POST['specs_new'] ?? null;
        $data = Factory::getAmocrmStorage()->createProduct($category_id, $article, $title, $selling_price, $model);
        $product_id = intval($data->toArray()['id']);

        if ($specs_new !== null) {
            foreach ($specs_new as $spec) {
                $specification_id = intval($spec['specification_id']);
                $value = $spec['value'];
                Factory::getAmocrmStorage()->specificationsValueSet($specification_id, $product_id, $value);
            }
        }

        if ($description !== null) {
            Factory::getAmocrmStorage()->descriptionSet($product_id, $description);
        }

        return $data->toArray();
    }

    function products_get(array $POST): array
    {
        $filter = [];
        $filter['with'] = 'balance';
        $store_id = intval($POST['store_id']);
        $store_id = $store_id == -1 ? '' : $filter['store_id'] = $store_id;
        $Products = Factory::getCatalog()->getProductModels($filter);
        $arr = [];
        foreach ($Products as &$item) {
            $product_id = $item['id'] * 1;
            $item['img_link'] = Factory::getFiles()->getProductsFiles($product_id);
            array_push($arr, $item);
        }
        return $arr;
    }

    function descriptions_get(array $POST): array
    {
        $product_id = intval($POST['product_id']); // int
        return Factory::getAmocrmStorage()->descriptionGet($product_id);
    }

    function history_product(array $POST): array
    {
        $product_id = intval($POST['product_id']);
        return Factory::getCatalog()->getProductImportModels([
            'product_id' => $product_id,
            'with' => 'balance'
        ]);

    }

    function update_product(array $POST): bool
    {
        $product_id = intval($POST['id']); // int
        $description = $POST['desc'] ?? null; // int
        $category_id = intval($POST['section_id']); // int
        $article = urldecode($POST['article']);// str
        $title = urldecode($POST['title']); //str
        $unit = urldecode($POST['unit']);
        $selling_price = floatval($POST['selling_price']); //float
        $specifications = $POST['specs_new'] ?? []; // array
        $model = array(
            'category_id' => $category_id,
            'title' => $title,
            'selling_price' => $selling_price,
            'article' => $article,
            'unit' => $unit
        );

        if ($specifications !== null) {
	        Factory::getAmocrmStorage()->specificationsValueUpdate($specifications);
        }

//        if ($description !== null) {
//            Factory::getAmocrmStorage()->descriptionSet($product_id, $description);
//        }

        return Factory::getAmocrmStorage()->getProduct($product_id)->update($model);
    }

    function product_delete(array $POST): bool
    {
        $product_id = intval($POST['product_id']);
        return Factory::getAmocrmStorage()->deleteProduct($product_id);
    }

    function coming_add(array $POST): array
    {
        $store_id = intval($POST['data']['store_id']);
        $time = $POST['data']['date_supply'];
        $provider = $POST['data']['provider'];
        $arr = ['date' => $time, 'provider' => $provider];
        $obj_create = Factory::getAmocrmStorage()->createImport($store_id, $arr);
        $Model = $obj_create->toArray();
        $import_id = $Model['import_id'];
        $data_arr = $POST['data']['data_supply'];

        foreach ($data_arr as $item) {
            $product_id = intval($item['product_id']);
            $quantity = floatval($item['quantity']);
            $purchase_price = floatval($item['price_purchase']);
            Factory::getAmocrmStorage()->createProductImport($import_id, $product_id, $quantity, $purchase_price);
        }
        return $Model;
    }

    function add_position_in_coming(array $POST): array
    {
        $import_id = intval($POST['data']['import_id']);
        $product_id = intval($POST['data']['product_id']);
        $quantity = floatval($POST['data']['quantity']);
        $purchase_price = floatval($POST['data']['purchase_price']);
        $model = Factory::getAmocrmStorage()->createProductImport($import_id, $product_id, $quantity, $purchase_price);
        return $model->toArray();
    }

    function coming_get(array $POST): array
    {

        $store_id = intval($POST['store_id']);
        switch ($store_id) {
            case -1 :
                $array_all_import = Factory::getCatalog()->getImportModels();
                break;
            default:
                $array_all_import = Factory::getCatalog()->getImportModels(['store_id' => $store_id]);
                break;

        }

        $new_array = [];
        foreach ($array_all_import as $item) {
            array_push($new_array, [
                'id_supply' => $item['id'],
                'date_supply' => $item['date'],
                'num_document' => '534545',
                'num_supply' => $item['id'],
                'provider' => $item['provider'],
                'data_supply' => $this->data_supply($item['id'])
            ]);
        }
        return $new_array;

    }

    private function data_supply(int $import_id): array
    {
        $new_data = [];
        $data = Factory::getCatalog()->getProductImportModels(['import_id' => $import_id]);
        foreach ($data as $key => $item) {
            array_push($new_data, [
                'title' => $item['title'],
                'article' => $item['article'],
                'id' => $item['id'],
                'no' => ($key + 1),
                'product_id' => $item['product_id'],
                'price_purchase' => $item['purchase_price'],
                'price_sail' => '777',
                'quantity' => $item['quantity'],

            ]);
        }
        return $new_data;
    }

    function coming_delete(array $POST): bool
    {
        $import_id = intval($POST['coming_id']);
        return Factory::getAmocrmStorage()->deleteImport($import_id);
    }

    function delete_position_coming(array $POST): bool
    {
        $product_import_id = $POST['product_import_id'];
        return Factory::getAmocrmStorage()->deleteProductImport($product_import_id);
    }

    function coming_change(array $POST): bool
    {
        $data_array = $POST['data'];
        $store_id = $data_array['store_id'];
        $import_id = $data_array['id_supply'];
        $date_coming = $data_array['date_supply'];
        $provider = $data_array['provider'];
        $array_product_update = $data_array['data_supply'];
        $obj_import = Factory::getAmocrmStorage()->getImport($import_id);
        $model_import_update = [
            'store_id' => $store_id,
            'import_date' => $date_coming,
            'provider' => $provider
        ];
        $obj_import->update($model_import_update);
        $res_data = false;
        foreach ($array_product_update as $item) {
            $id = intval($item['id']);
            $price_purchase = floatval($item['price_purchase']);
            $quantity = floatval($item['quantity']);
            $res_data = $this->update_coming_one($id, $quantity, $price_purchase);
        }
        self::productImportChangeTrigger();
        return $res_data;
    }

    private function update_coming_one(int $product_import_id, float $quantity, float $purchase_price): bool
    {
        $model = [
            'quantity' => $quantity,
            'purchase_price' => $purchase_price,
        ];
        $obj_prod_import = Factory::getAmocrmStorage()->getProductImport($product_import_id);
        self::setIsChangedProductImport($obj_prod_import);
        return $obj_prod_import->update($model);
    }

    static private function setIsChangedProductImport(\Storage\iProductImport $productImport): void
    {
        self::$changed_product_imports[] = $productImport;
    }

    static private function productImportChangeTrigger(): void
    {
        Factory::getStoreApp()->changeProductImportsTrigger(self::$changed_product_imports);
        self::$changed_product_imports = [];
    }

    function create_export(array $POST): array
    {
        $exports_models = self::fetchExportModels($POST);
        $exports = Factory::getAmocrmStorage()->createExports($POST['entity_type'], $POST['entity_id'], $exports_models);
        return self::getExportModels($exports);
    }

    private static function fetchExportModels(array $request): array
    {
        $arr = $request['data'] ?? [];
        foreach ($arr as $item)
            $answer[] = [
                'product_id' => (int)$item['product_id'],
                'quantity' => (float)$item['quantity'],
                'selling_price' => (float)$item['selling_price'],
                'priority_store_id' => (int)$request['stock_id'],
            ];
        return $answer ?? [];
    }

    private static function getExportModels(array $exports): array
    {
        foreach ($exports as $export)
            $answer[] = $export->toArray();
        return $answer ?? [];
    }

    function get_export(array $POST): array
    {
        $new_arr = [];
        $entity_type = strval($POST['entity_type']);
        $entity_id = intval($POST['entity_id']);


        $data = Factory::getCatalog()->getEntityExportModels($entity_type, $entity_id);
        foreach ($data as $item) {
            $product_id = intval($item['product_id']);
            $export_product_id = intval($item['id']);
            $Discount = Factory::getDiscount()->getDiscountOnId($export_product_id);
            $item['discount'] = $Discount['discount'];
            $item['full_price'] = $Discount['full_price'];
            $data = Factory::getCatalog()->getProductModels(['id' => $product_id]);
            $item['title'] = $data[0]['title'];
            $item['article'] = $data[0]['article'];
            $item['category_id'] = $data[0]['category_id'];
            array_push($new_arr, $item);
        }


        return $new_arr;

    }

    function delete_export(array $POST): bool
    {
        $export_id = intval($POST['export_id']);
        return Factory::getAmocrmStorage()->deleteExport($export_id);
    }

    function delete_export_many(array $POST): bool
    {
        return count($POST['export_ids']) && Factory::getAmocrmStorage()->deleteExport($POST['export_ids']);
    }


    function update_export_quantity(array $POST): bool
    {
        $export_id = intval($POST['export_id']);
        $quantity = floatval($POST['quantity']);
        $data = Factory::getAmocrmStorage()->getExport($export_id);
        $model = [
            'quantity' => $quantity,
        ];

        $flag = $data->update($model);
        self::changeExportTrigger();
        return $flag;
    }

    function update_export_selling_price(array $POST): bool
    {
        $export_id = intval($POST['export_id']);
        $selling_price = floatval($POST['selling_price']);
        $this->save_discountId([
            'export_id' => $export_id,
            'discount' => 0,
            'full_price' => $selling_price
        ]);
        Factory::getDiscount()->deleteDiscount($export_id);
        $flag = $this->updateExport($export_id, $selling_price);
        self::changeExportTrigger();
        return $flag;
    }

    function update_selling_price(array $POST): bool
    {
        $export_id = intval($POST['export_id']);
        $selling_price = floatval($POST['selling_price']);
        $flag = $this->updateExport($export_id, $selling_price);
        self::changeExportTrigger();
        return $flag;
    }

    private function updateExport(int $export_id, float $selling_price): bool
    {
        $data = Factory::getAmocrmStorage()->getExport($export_id);
        $model = ['selling_price' => $selling_price];
        return $data->update($model);
    }

    private function update_export_selling_price_many(array $data): bool
    {
        $flag = true;
        foreach ($data as $item)
            $flag = $flag && $this->updateExport($item['export_id'], $item['price_new']);
        self::changeExportTrigger();
        return $flag;
    }

    function get_all_export(array $POST)
    {
        $data = [];
        $store_id = intval($POST['stock_id']);
        switch ($store_id) {
            case -1 :
                $data = Factory::getCatalog()->getExportModels();
                break;
            default:
                $filter = ['store_id' => $store_id];
                $data = Factory::getCatalog()->getExportModels($filter);
                break;

        }
        return $data;
    }

    function create_stock(array $POST): array

    {
        $address = $POST['data']['address'];
        $title = $POST['data']['title'];
        $create_stock = Factory::getAmocrmStorage()->createStore($title, $address);
        return $create_stock->toArray();

    }

    function get_stock(): array
    {
        return Factory::getCatalog()->getStoreModels();
    }

    function get_active_stock(array $POST): array
    {
        $category_id = intval($POST['category_id']);
        return Factory::getCatalog()->getCategoriesToStoresActive($category_id);
    }

    function delete_stock($POST): bool
    {
        $story_id = intval($POST['id_stock']);
        $model_store_id = Factory::getAmocrmStorage()->getStore($story_id);
        return $model_store_id->delete();
    }

    function restoreStock($POST): void
    {
        $story_id = intval($POST['id_stock']);
        Factory::getAmocrmStorage()->getStore($story_id)->restore();
    }

    function change_stock($POST): bool
    {
        $story_id = intval($POST['data']['store_id']);
        $address = $POST['data']['address'];
        $title = $POST['data']['title'];
        $model_store_id = Factory::getAmocrmStorage()->getStore($story_id);

        $new_data = ['title' => $title, 'address' => $address];
        return $model_store_id->update($new_data);
    }

    function get_dificit(): array
    {
        return Factory::getCatalog()->getProductDeficitModels();
    }

    function del_img($ICON)
    {
        $product_id = $ICON * 1;
        Factory::getFiles()->deleteProductsFiles($product_id);
    }

    public function get_units(): array
    {
        return Factory::getCatalog()->getUnits();
    }


    function save_discountId(array $POST): int
    {
        $res = $this->saveDiscount($POST);
        self::changeExportTrigger();
        return $res;
    }

    private function saveDiscount(array $POST): int
    {
        $idS = intval($POST['export_id']);
        $discount = intval($POST['discount']);
        $full_price = intval($POST['full_price']);

        return Factory::getDiscount()->saveDiscount($idS, $discount, $full_price);
    }

    function save_discount_all(array $POST): int
    {
        $ArrS = $POST['update_exports'];
        $change_price_many = $this->update_export_selling_price_many($ArrS);

        foreach ($ArrS as $item) {
            $export_id = $item['export_id'];
            $full_price = $item['full_price'];
            $PriceNew = $item['price_new'];
            $discount = $item['discount'];
            $change_price_many = $this->saveDiscount([
                'export_id' => $export_id,
                'discount' => $discount,
                'full_price' => $full_price,
            ]);

        }

        self::changeExportTrigger();

        return $change_price_many;
    }

    static private function changeExportTrigger(): void
    {
        Factory::getStoreApp()->changeEntitiesTrigger();
    }

    function getCategoriesLinks(): array
    {
        return Factory::getCatalog()->getCategoriesToStoresLinks();
    }

    /*
        store_id
        category_id
        link_status
     */

    function saveCategoriesLinks(array $links): void
    {
        Factory::getStoreApp()->getCategoriesToStores()->saveLinksFromArr($links);
    }

    function create_export_tovars(array $POST): array
    {
        $subdomain = $POST['referrer'];
        $Products = Factory::getCatalog()->getProductModels();
        $arr = [];
        foreach ($Products as &$item) {
            $product_id = $item['id'] * 1;
            $item['img_link'] = Factory::getFiles()->getProductsFiles($product_id);
            array_push($arr, $item);
        }
        $today_date = date("Y-m-d-H-i-s");
        $file_name = $subdomain . '_' . $today_date . '.json';
        $path = '/var/www/pragma/data/projects/python_project-dev/PragmaStorage/ExportProduct/temp_export/' . $file_name;
        $data_export = array(
            "entity" => "products",
            "subdomain" => $POST['referrer'],
            "product_list" => $file_name,
            "flag" => "create_export_tovars"
        );
        $export = json_encode($data_export);
        file_put_contents($path, json_encode($arr));

        $str = "'$export'";
        Factory::getLogWriter()->add('$str', $str);
        $output = exec("python3.9 /var/www/pragma/data/projects/python_project-dev/PragmaStorage/ExportProduct/export.py $str");
        Factory::getLogWriter()->add('$output', $output);
        return json_decode($output, true);

    }

    function create_export_check(array $POST): array|null
    {
        $data_export = array(
            "entity" => "products",
            "subdomain" => $POST['referrer'],
            "flag" => "check"
        );
        $export = json_encode($data_export);
        $str = "'$export'";
        Factory::getLogWriter()->add('create_export_check', $str);

        $output = exec("python3.9 /var/www/pragma/data/projects/python_project-dev/PragmaStorage/ExportProduct/export.py $str");
        Factory::getLogWriter()->add('create_export_check$output', $output);

        return json_decode($output, true);

    }

    function create_import_tovars(array $POST)
    {
        $import = array(
            "flag" => "download",
            "format_file" => "csv",
            "subdomain" => $POST['referrer'],
            "delimiter" => $POST['delimiter'],
            "name_file" => explode('.', $POST['name_file'])[0]
        );
        $import_ = json_encode($import);

        $str = "'$import_'";
        Factory::getLogWriter()->add('create import_', $str);

        $output = exec("python3.9 /var/www/pragma/data/projects/python_project-dev/PragmaStorage/ImportProduct/import.py $str");
        Factory::getLogWriter()->add('import_$output', $output);

        return json_decode($output, true);

    }

    function create_product_from_export($POST): bool
    {
        Factory::getLogWriter()->add('POST', $POST);
        $arr_set = $POST['array_set'];
        $path = __DIR__ . '/../../../../../Desktop' . $POST['path'];
        $file = json_decode(file_get_contents($path), true);

        $answer = true;

        foreach ($file['product'] as $a => $item) {

            $new_obj = [];
            $article = array_search('article', $arr_set);
            $title = array_search('title', $arr_set);
            $selling_price = array_search('selling_price', $arr_set);
            $img_link = array_search('img_link', $arr_set);
            $unit = array_search('unit', $arr_set);
            $new_obj['section_id'] = 97;
            $new_obj['article'] = $item[$article] . '_import_' . $a;
            $new_obj['title'] = $item[$title];
            $new_obj['selling_price'] = $item[$selling_price];
            $new_obj['img_link'] = $item[$img_link];
            $new_obj['unit'] = $item[$unit];
            $answer = $this->save_product($new_obj);
        }

        unlink($path);

        return (bool)$answer;
    }

    function add_technological_card($POST): bool
    {
        $title = $POST['data']['title'] ?? null;
        $model = Factory::getAmocrmStorage()->setTechnologicalCard($title);
        $technological_card_id = $model[0]['id'];

        foreach ($POST['data']['data_supply'] as $item) {
            $product_id = intval($item['product_id']);
            $quantity = intval($item['quantity']);
            Factory::getAmocrmStorage()->setTechnologicalCardProduct($technological_card_id, $product_id, $quantity);
        }

        return true;
    }

    function get_technological_cards(): array
    {
        $model = Factory::getAmocrmStorage()->getTechnologicalCard();



        $arr = [];
        foreach ($model as $item) {
            array_push($arr, $item);

            if (in_array($item['technological_card_id'], $item)) {
                foreach ($item as $i) {
                    array_push($i['product_title'], );
                }
            } else {
                continue;
            }
        }

        return Factory::getAmocrmStorage()->getTechnologicalCard();
    }


//    static private function returnKey()
//    {
//
//    }
//
//    static private function changeExportTrigger(): void
//    {
//        Factory::getStorage()->changeEntitiesTrigger();
//    }
}