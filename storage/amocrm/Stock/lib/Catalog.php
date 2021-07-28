<?php
require_once __DIR__ . '/StockDB.php';
require_once __DIR__ . '/IStock.php';


class Catalog implements IStock {

	function coming_add($POST) {
		$DB = new StockDB();
		$ACCOUNT_ID = intval($POST['account_id']);
		$data = $POST['data'];
		$DB->AddAccountId($ACCOUNT_ID);
		$DB->create_new_coming($ACCOUNT_ID, $data);
		$DB->update_all_quantity($ACCOUNT_ID);

	}

	function section_add($POST) {
		$DB = new StockDB();
		$ACCOUNT_ID = intval($POST['account_id']);
		$DB->AddAccountId($ACCOUNT_ID);
		$NAME = $POST['name'];
		$ID = $POST['section_id'];
		$DESCRIPTION = $POST['description'];
		$DB->create_new_section($ACCOUNT_ID, $NAME, $DESCRIPTION, $ID);
	}

	function coming_delete($POST) {
		$DB = new StockDB();
		$ACCOUNT_ID = intval($POST['account_id']);
		$ID_COMING = $POST['coming_id'];
		$DB->delete_coming($ACCOUNT_ID, $ID_COMING);
		$DB->update_all_quantity($ACCOUNT_ID);
	}

	function update_quantity_products($POST) {
		$ACCOUNT_ID = intval($POST['account_id']);
		$DB = new StockDB();
		$DB->update_all_quantity($ACCOUNT_ID);

	}

	function change_quantity_products($POST) {
		$ACCOUNT_ID = intval($POST['account_id']);
		$DB = new StockDB();
		$ID_PRODUCT = $POST['product_id'];
		$NEW_VALUE = $POST['new_value'];
		$DB->change_quantity_product($ACCOUNT_ID, $ID_PRODUCT, $NEW_VALUE);

	}

	function comings_get($POST) {
		$ACCOUNT_ID = intval($POST['account_id']);
		$DB = new StockDB();
		return $DB->get_all_coming($ACCOUNT_ID);
	}

	function section_get($POST) {
		$ACCOUNT_ID = intval($POST['account_id']);
		$DB = new StockDB();
		return $DB->get_all_section($ACCOUNT_ID);
	}

	function products_get($POST) {
		$ACCOUNT_ID = intval($POST['account_id']);
		$DB = new StockDB();
		return $DB->get_all_products($ACCOUNT_ID);

	}

	function update_coming($POST) {
		$ACCOUNT_ID = intval($POST['account_id']);
		$ID = $POST['data']['id_supply'];
		$new_data = $POST['data'];
		$DB = new StockDB();
		$DB->update_coming($ACCOUNT_ID, $ID, $new_data);
		$DB->update_all_quantity($ACCOUNT_ID);


	}

	function update_product($POST) {
		$ACCOUNT_ID = intval($POST['account_id']);
		$ID = $POST['product_id'];
		$new_data = ['icon' => $POST['icon'], 'name' => $POST['name'], 'desc' => $POST['desc'], 'article' => $POST['article'], 'section' => $POST['section'], 'section_id' => $POST['section_id'], 'quantity' => $POST['quantity'], 'unit' => $POST['unit'],];
		$DB = new StockDB();
		$DB->update_product($ACCOUNT_ID, $ID, $new_data);
		$DB->update_all_quantity($ACCOUNT_ID);


	}

	function save_product($POST) {
		$DB = new StockDB();
		$ACCOUNT_ID = $POST['account_id'];
		$CATALOG_ID = $POST['product_id'];
		$ICON = $POST['icon'];
		$SECTION_ID = $POST['section_id'];
		$NAME = $POST['name'];
		$DESCRIPTION = $POST['desc'];
		$ARTICLE = $POST['article'];
		$SECTION = $POST['section'];
		$QUANTITY = $POST['quantity'];
		$UNIT = $POST['unit'];
//        $PRICE_PURCHASE = $POST['price_purchase'];
//        $PRICE_SAIL = $POST['price_sail'];

		$data = ['icon' => $ICON, 'id' => $CATALOG_ID, 'name' => $NAME, 'desc' => $DESCRIPTION, 'article' => $ARTICLE, 'section' => $SECTION, 'section_id' => $SECTION_ID, 'quantity' => $QUANTITY, 'unit' => $UNIT, //            'price_purchase' => $PRICE_PURCHASE,
//            'price_sail' => $PRICE_SAIL,
		];
		$DB->AddAccountId($ACCOUNT_ID);
		$DB->create_new__product($ACCOUNT_ID, $data);

	}

	function del_img($ICON) {
		$way = __DIR__ . '/Stock/' . $ICON;
		unlink($way);
	}

	function save_img($FILES) {
		$data_file = $FILES['files']['name'];
		$array_file_data = preg_split('[&]', $data_file);
		$it_array = [];
		foreach ($array_file_data as $el) {
			$str_val = explode('=', $el);
			$it_array[$str_val[0]] = str_replace('|', '/', $str_val[1]);
		}
		$way = __DIR__ . "/../temp/" . $it_array['account_id'];
		if (!file_exists($way)) {
			mkdir($way, 0700);
		}
		$way_file = $way . '/' . $it_array['name_file'];
		move_uploaded_file($FILES['files']['tmp_name'], $way_file);
		return $way_file;
	}


}