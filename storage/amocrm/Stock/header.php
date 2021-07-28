<?php

header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../../../lib/log/LogJSON.php';
require_once __DIR__ . '/lib/Stock.php';

//save();die;

$POST = $_POST;
$FLAG = $POST['flag'];
$ACCOUNT_ID = intval($POST['account_id']);
$subdomain = $POST['referrer'];
$referrer = $subdomain . ".amocrm.ru";

$log_writer = new LogJSON($referrer, \Storage\WIDGET_NAME, 'header');
\Storage\Factory::initById(\Storage\WIDGET_NAME, $ACCOUNT_ID, $log_writer);

$module = \Storage\Factory::getNodesService()->findAmocrmNodeAccId(\Storage\WIDGET_NAME, $ACCOUNT_ID);

$log_writer->set_container($FLAG);
$Stock = new Stock($referrer, $log_writer);


try {
//    $module->checkActive();
    if ($_POST) {
        switch ($FLAG) {
            case 'section_add':
                $data_new_section = $Stock->section_add($POST);
                echo json_encode($data_new_section);
                break;

            case 'section_get':
                $data = $Stock->section_get();
                echo json_encode($data);
                break;

            case 'section_delete':
                $data = $Stock->section_delete($POST);
                echo json_encode($data);
                break;

	        case 'section_restore':
	        	$data = $Stock->section_restore($POST);
	        	echo json_encode($data);
	        	break;

            case 'section_update':
                $data = $Stock->section_update($POST);
                echo json_encode($data);
                break;

            case 'specification_delete':
                $data = $Stock->specification_delete($POST);
                echo json_encode($data);
                break;

	        case 'specifications_get':
	        	$data = $Stock->specifications_get($POST);
	        	echo json_encode($data);
	        	break;

            case 'categories.stores.get':
                $data = $Stock->getCategoriesLinks();
                echo json_encode($data);
                break;

            case 'categories.stores.save':
                $Stock->saveCategoriesLinks($POST['links'] ?? []);
                echo json_encode(true);
                break;

            case 'save_product':
                $data = $Stock->save_product($POST);
                echo json_encode($data);
                break;

            case 'products_get':
                $data = $Stock->products_get($POST);
                echo json_encode($data);
                break;

            case 'descriptions_get':
                $data = $Stock->descriptions_get($POST);
                echo json_encode($data);
                break;

            case 'history_product':
                $data = $Stock->history_product($POST);
                echo json_encode($data);
                break;

            case 'update_product':
                $data = $Stock->update_product($POST);
                echo json_encode($data);
                break;

            case 'product_delete':
                $data = $Stock->product_delete($POST);
                echo json_encode($data);
                break;

            case 'del_img':
                $Stock->del_img($POST['product_id']);
                break;

            case 'coming_add':
                $data = $Stock->coming_add($POST);
                echo json_encode($data);
                break;

            case 'add_position_in_coming':
                $data = $Stock->add_position_in_coming($POST);
                echo json_encode($data);
                break;

            case 'coming_get':
                $data = $Stock->coming_get($POST);
                echo json_encode($data);
                break;

            case 'coming_delete':
                $data = $Stock->coming_delete($POST);
                echo json_encode($data);
                break;

            case 'delete_position_coming':
                $data = $Stock->delete_position_coming($POST);
                echo json_encode($data);
                break;

            case 'coming_change':
                $data = $Stock->coming_change($POST);
                echo json_encode($data);
                break;

            case 'create_export':
                $data = $Stock->create_export($POST);
                echo json_encode($data);
                break;

            case 'get_export':
                $data = $Stock->get_export($POST);
                echo json_encode($data);
                break;

            case 'delete_export':
                $data = $Stock->delete_export($POST);
                echo json_encode($data);
                break;
            case 'delete_export_many':
                $data = $Stock->delete_export_many($POST);
                echo json_encode($data);
                break;
            case 'update_export_quantity':
                $data = $Stock->update_export_quantity($POST);
                echo json_encode($data);
                break;

            case 'update_export_selling_price':
                $data = $Stock->update_export_selling_price($POST);
                echo json_encode($data);
                break;

            case 'get_all_export':
                $data = $Stock->get_all_export($POST);
                echo json_encode($data);
                break;

            case 'create_stock':
                $data = $Stock->create_stock($POST);
                echo json_encode($data);
                break;

            case 'get_stock':
                $data = $Stock->get_stock();
                echo json_encode($data);
                break;

            case 'get_active_stock':
                $data = $Stock->get_active_stock($POST);
                echo json_encode($data);
                break;

            case 'delete_stock':
                $data = $Stock->delete_stock($POST);
                echo json_encode($data);
                break;

            case 'restore_stock':
                $Stock->restoreStock($POST);
                echo json_encode(true);
                break;

            case 'change_stock':
                $data = $Stock->change_stock($POST);
                echo json_encode($data);
                break;

            case 'get_dificit':
                $data = $Stock->get_dificit();
                echo json_encode($data);
                break;

            case 'get_units':
                $data = $Stock->get_units();
                echo json_encode($data);
                break;

            case 'save_discount':
                $data = $Stock->save_discountId($POST);
                echo json_encode($data);
                break;

            case 'save_discount_all':
                $data = $Stock->save_discount_all($POST);
                echo json_encode($data);
                break;


            case "update_selling_price":
                $data = $Stock->update_selling_price($POST);
                echo json_encode($data);
                break;


            case 'create_export_tovars':
                $data = $Stock->create_export_tovars($POST);
                echo json_encode($data);
                break;

            case 'create_export_check':
                $data = $Stock->create_export_check($POST);
                echo json_encode($data);
                break;

            case 'create_product_from_export':
                $data = $Stock->create_product_from_export($POST);
                echo json_encode($data);
                break;

            case 'add_technological_card':
                $data = $Stock->add_technological_card($POST);
                echo json_encode($data);
                break;

//            case 'get_technological_cards':
//                \Storage\Factory::getLogWriter()->add('$POST', $POST);
//                $data = $Stock->get_technological_cards();
//                echo json_encode($data);
//                break;

        }
    } elseif ($_FILES) {
        $log_writer->add('$_FILES', $_FILES);

    }
} catch (Exception $e) {
    $log_writer->send_error($e);

    echo json_encode([
        'message' => $e->getMessage(),
        'code' => $e->getCode(),
    ]);
}

