<?php

use \Autocall\Bitrix\Factory;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . '/../../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../../constants.php';
require_once __DIR__ . '/../Factory.php';

$logger = new LogJSON($_REQUEST['DOMAIN'], \Lirax\WIDGET_NAME, 'HANDLER');
$logger->set_container('');
Factory::init($_REQUEST['member_id'], $logger);
try {
	$arResult = Factory::getWay('crm.dealcategory.list');
	$arUserResult = Factory::getWay('user.get');
	$arLead = Factory::getWay('crm.status.list', array("filter"=>array("ENTITY_ID"=>"STATUS")));
} catch (\Exception $exception)
{
	$logger->send_error($exception);
}

//echo '<pre>';
//    var_dump($arResult);
//echo '</pre>';

?>
<!DOCTYPE html>
<html lang="ru-RU">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Заголовок</title>
    <meta name="title" content="Заголовок">
    <meta name="description" content="Описание">
    <link rel="icon" href="img/favicons/favicon-32.png" sizes="32x32" type="image/png"/>
    <link rel="icon" href="img/favicons/favicon-48.png" sizes="48x48" type="image/png"/>
    <link rel="icon" href="img/favicons/favicon-62.png" sizes="62x62" type="image/png"/>
    <link rel="icon" href="img/favicons/favicon-192.png" sizes="192x192" type="image/png"/>
    <meta name="theme-color" content="#012632"/>
    <style>
        header,main{display:block;overflow:hidden;}

    </style>
    <link rel="stylesheet" type="text/css" href="css/style.css?v=1.0"/>
</head>
<body>
<main>
    <input type="hidden" name="<?=$_REQUEST['DOMAIN']?>" id="<?=$_REQUEST['member_id']?>">
    <div class="lirax-widget">
        <div class="lirax-container">
            <form class="lirax__block">
                <div class="lirax__block-header">
                    <div class="lirax__header-logo">
                        <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 85.9 18" width="100" height="21">
                            <path fill="#39c3de" d="M13.8 9.2L8 .9 6.4 7.4z"></path>
                            <path fill="#00a3dc" d="M0 0l8 .9-1.8 7.5-2.9-3.9z"></path>
                            <path fill="#0081c7" d="M0 1.5l1-.2 2.5 3.5z"></path>
                            <path fill="#3cc4e0" d="M6.2 8.4l1 4.5v1.2l-3.7 2.8z"></path>
                            <path fill="#00a4da" d="M13.8 9.2l-6.6 3.7-1-4.5.2-1z"></path>
                            <path fill="#50c3df" d="M13.5 10.3l.3-1.1-6.6 3.7z"></path>
                            <path fill="#00aad4" d="M15.4 5.3l-4 .6 2.4 3.3z"></path>
                            <path fill="#0084bf" d="M17 7l-1.6-1.7-.7 1.7z"></path>
                            <path d="M27.6 6.3c0 1.2-.4 2.1-1.1 2.8s-1.7 1-3 1h-.8v3.5h-2.9V2.9h3.7c1.3 0 2.4.3 3 .9.8.6 1.1 1.5 1.1 2.5zm-4.8 1.4h.5c.4 0 .8-.1 1-.4s.4-.6.4-1c0-.7-.4-1.1-1.2-1.1h-.7v2.5zm9.4 2v3.9h-2.9V2.9h3.5c2.9 0 4.3 1.1 4.3 3.2 0 1.2-.6 2.2-1.8 2.9l3.1 4.6h-3.3l-2.3-3.9h-.6zm0-2.1h.5c1 0 1.5-.4 1.5-1.3 0-.7-.5-1.1-1.5-1.1h-.6v2.4z"></path>
                            <path d="M46 13.6l-.5-2H42l-.5 2h-3.2l3.5-10.7h3.8l3.5 10.7H46zm-1.1-4.3l-.5-1.8c-.1-.4-.2-.9-.4-1.5s-.3-1.1-.3-1.3c0 .2-.1.7-.3 1.2s-.4 1.7-.8 3.4h2.3zm9.7-1.9h4.6v5.8c-1.3.4-2.6.6-4.1.6-1.6 0-2.9-.5-3.8-1.4-.9-1-1.3-2.3-1.3-4.1 0-1.7.5-3.1 1.5-4s2.4-1.4 4.1-1.4c.7 0 1.3.1 1.9.2s1.1.3 1.6.5l-.9 2.3c-.8-.4-1.6-.6-2.5-.6-.8 0-1.5.3-2 .8S53 7.4 53 8.4c0 1 .2 1.8.6 2.3.4.5 1 .8 1.8.8.4 0 .8 0 1.2-.1V9.6h-1.9V7.4zM66 13.6l-2.2-7.7h-.1c.1 1.3.2 2.3.2 3.1v4.6h-2.6V2.9h3.8l2.2 7.6h.1l2.2-7.6h3.8v10.7h-2.6V8.1c0-.3 0-1 .1-2.2h-.1l-2.2 7.7H66zm16.4 0l-.5-2h-3.5l-.5 2h-3.2l3.5-10.7H82l3.5 10.7h-3.1zm-1.2-4.3l-.5-1.8c-.1-.4-.2-.9-.4-1.5S80 4.9 80 4.7c0 .2-.1.7-.3 1.2s-.3 1.7-.7 3.4h2.2z"></path>
                            <g fill="#0073bb">
                                <path d="M28 5.8c0 1.2-.3 2.1-1 2.8s-1.7 1-3 1h-.8v3.5h-2.9V2.4H24c1.3 0 2.4.3 3 .9.7.6 1 1.4 1 2.5zm-4.8 1.4h.5c.4 0 .8-.1 1-.4s.4-.6.4-1c0-.7-.4-1.1-1.2-1.1h-.7v2.5zm9.4 2v3.9h-2.9V2.4h3.5c2.9 0 4.3 1.1 4.3 3.2 0 1.2-.6 2.2-1.8 2.9l3.1 4.6h-3.3l-2.3-3.9h-.6zm0-2.2h.5c1 0 1.5-.4 1.5-1.3 0-.7-.5-1.1-1.5-1.1h-.6V7z"></path>
                                <path d="M46.4 13.1l-.5-2h-3.5l-.5 2h-3.2l3.5-10.7H46l3.5 10.7h-3.1zm-1.1-4.4L44.8 7c-.1-.4-.2-.9-.4-1.5s-.3-1.1-.3-1.3c0 .2-.1.7-.3 1.2s-.4 1.7-.8 3.3h2.3zM55 6.9h4.6v5.8c-1.3.4-2.6.6-4.1.6-1.6 0-2.9-.5-3.8-1.4-.9-1-1.3-2.3-1.3-4.1 0-1.7.5-3.1 1.5-4 1-1 2.4-1.4 4.1-1.4.7 0 1.3.1 1.9.2s1.1.3 1.6.5l-.9 2.3c-.8-.4-1.6-.6-2.5-.6-.8 0-1.5.3-2 .8s-.7 1.3-.7 2.3c0 1 .2 1.8.6 2.3s1 .8 1.8.8c.4 0 .8 0 1.2-.1V9.1h-2V6.9zm11.4 6.2l-2.2-7.7h-.1c.1 1.3.2 2.3.2 3.1v4.6h-2.6V2.4h3.8l2.2 7.6h.1L70 2.4h3.8v10.7h-2.6V7.6c0-.3 0-1 .1-2.2h-.1L69 13.1h-2.6zm16.4 0l-.5-2h-3.5l-.5 2h-3.2l3.5-10.7h3.8l3.5 10.7h-3.1zm-1.2-4.4L81.2 7c-.1-.4-.2-.9-.4-1.5s-.3-1.1-.3-1.3c0 .2-.1.7-.3 1.2s-.4 1.7-.9 3.4h2.3z"></path>
                            </g>
                        </svg><img src="img/icons/lirax-logo.png" alt="logo lirax">
                    </div>
                    <div class="lirax__header-key">
                        <h4>Ключ LiraX</h4>
                        <input id="lirax_key" type="text" name="lirax_key" placeholder="Ключ">
                    </div>
                </div>
                <div class="lirax__block-items">
                    <div class="lirax__item"><a class="lirax__item-btn" href="#" data-settings="false">Укажите активные воронки</a>
                        <div class="lirax__item-content">
                            <div class="lirax__content-voronki">
                                <div class="lirax__content-voronki-lists">
                                    <label class="lirax__content-voronki-list js-all">
                                        <input id="lirax_voronki_all" type="checkbox" name="lirax_voronki_all" value="все">
                                        <p>Выбрать всё</p>
                                    </label>
                                    <?php foreach ($arResult['result'] as $key => $arItems):?>
                                        <label class="lirax__content-voronki-list js-allPip" id="<?=$arItems['ID']?>">
                                            <input type="checkbox" class="js-pip" name="lirax_voronki[]" value="<?=$arItems['NAME']?>">
                                            <p><?=$arItems['NAME']?></p>
                                        </label>
                                    <?php endforeach;?>
                                </div>
                                <div class="lirax__content-voronki-result">
                                    <div class="lirax__content-voronki-result-table"></div><span class="lirax__content-voronki-result-btn">ещё...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lirax__item">
                        <div class="lirax__content-call">
                            <div class="lirax__content-call-time">
                                <p>Время проверки дозвона до «Ответственного»</p>
                                <label>
                                    <input type="number" id="time-auto-call-title" name="call_time">
                                    <p>минут</p>
                                </label>
                            </div>
                            <div class="lirax__content-call-attempt">
                                <div class="lirax__content-call-attempt-select">
                                    <p>Количество попыток дозвона до клиента</p>
                                    <select id="call_attempt" name="call_attempt">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                    </select>
                                </div>
                                <div class="lirax__content-call-attempt-lists"></div>
                            </div>
                            <div class="lirax__content-call-work">
                                <label>
                                    <p>Рабочее время с</p>
                                    <input type="time" id="call_work_before" name="call_work_before">
                                </label>
                                <label>
                                    <p>до</p>
                                    <input type="time" id="call_work_after" name="call_work_after">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="lirax__item lirax__item-row"><a class="lirax__item-btn" href="#" id="user-chek" data-settings="false">Приоритет</a>
                        <div class="lirax__item-content scroll lirax__content-priority">
                            <table class="lirax__content-priority-table">
                                <thead>
                                <tr>
                                    <th class="number">№</th>
                                    <th class="name">Имя</th>
                                    <th class="priority">Приоритет<br> менеджеров</th>
                                </tr>
                                </thead>
                                <tbody id="table_user">
                                <?php foreach ($arUserResult['result'] as $key => $arUser):?>
                                <tr>
                                    <td class="number-list" id="<?=$arUser['ID']?>"><?=$key?></td>
                                    <td class="name-user">
                                        <input type="text" name="user_name[]" disabled="true" id="<?=$arUser['ID']?>" value="<?=$arUser['NAME']?>&nbsp;<?=$arUser['LAST_NAME']?>">
                                    </td>
                                    <td class="user-priority">
                                        <input type="number" name="user_priority[]">
                                    </td>
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="lirax__item lirax__item-row"><a class="lirax__item-btn" href="#" id="lead-status" data-settings="false">Статусы лидов</a>
                        <div class="lirax__item-content scroll lirax__content-lead">
                            <table class="lirax__content-lead-table">
                                <tbody>
                                <?php foreach ($arLead['result'] as $key => $arItems):?>
                                    <tr>
                                        <td class="lead">
                                            <input type="text" name="lead_name[]" disabled="true" id="<?=$arItems['ID']?>" value="<?=$arItems['NAME']?>">
                                        </td>
                                        <td class="num_lead">
                                            <input type="number" name="lead_number[]">
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="lirax__item lirax__item-row"><a class="lirax__item-btn" href="#" id="number-funnels" data-settings="false">Номера воронок</a>
                        <div class="lirax__item-content scroll lirax__content-numbers">
                            <table class="lirax__content-numbers-table">
                                <tbody>
                                <?php foreach ($arResult['result'] as $key => $arItems):?>
                                <tr>
                                    <td class="funnels">
                                        <input type="text" name="voronka_name[]" disabled="true" id="<?=$arItems['ID']?>" value="<?=$arItems['NAME']?>">
                                    </td>
                                    <td class="num_funnels">
                                        <input type="number" name="voronka_number[]">
                                    </td>
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="lirax__item lirax__item-row"><a class="lirax__item-btn" href="#" id="using-stores" data-settings="false">Использование<br> магазинов</a>
                        <div class="lirax__item-content scroll lirax__content-shops">
                            <table class="lirax__content-shops-table">
                                <tbody>
                                <?php foreach ($arResult['result'] as $key => $arItems):?>
                                <tr>
                                    <td class="store">
                                        <input type="text" class="info_store" name="shop_name[]" disabled="true" id="<?=$arItems['ID']?>" value="<?=$arItems['NAME']?>">
                                    </td>
                                    <td class="num_store">
                                        <input type="number" name="shop_number[]">
                                    </td>
                                </tr>
                                <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="lirax__btn">
                        <input id="lirax_save" value="Сохранить">
                    </div>
                </div>
            </form>
            <div class="lirax__modal" id="lirax_modal">
                <div class="lirax__modal-block"><span class="lirax__modal-btn-close" id="lirax_modal_close"></span>
                    <div class="lirax__modal-content">
                        <div class="lirax__modal-icon" id="lirax_modal-icon"></div>
                        <h4 class="lirax__modal-title" id="lirax_modal_title"></h4>
                        <p class="lirax__modal-descr" id="lirax_modal_descr"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="js/jqure.js"></script>
<script src="js/GetSettings.js"></script>
<script src="js/Settings.js"></script>
<script src="js/build.js"></script>
<script>
    (function(w,d,u){
        var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/60000|0);
        var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
    })(window,document,'https://crm.pragma.by/upload/crm/site_button/loader_2_s4vjwt.js');
</script>
</body>
</html>