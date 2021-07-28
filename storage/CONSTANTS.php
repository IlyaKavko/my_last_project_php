<?php

namespace Storage;

const WIDGET_NAME = 'PragmaStorage';
const PRODUCT_IS_USED_DELETE_ERROR = 801;
const PRODUCT_ARTICLE_EXISTS = 802;
const PRODUCTS_IMPORT_ALREADY_EXISTS = 803;
const PART_OF_THE_GOODS_SENT_TO_THE_CUSTOMER = 804;

//Статусы для отгрузок
const EXPORT_STATUS_LINKED = 1;//export привязан
const EXPORT_STATUS_RESERVED = 2;//export зарезервирован
const EXPORT_STATUS_EXPORTED = 3;//export отправлен клиенту

//Типы поставок и отгрузок
const DEFAULT_SOURCE = 1; //default client_type. Закупка для поставки, и клиент для экспорта
const STORE_SOURCE = 2; //store client_type. При переводе со склада на склад ставится типом для import-a и export-a
const DEFICIT_SOURCE = 3; //deficit client_type. только для поставвки если создаётся дефицит

//Типы состояний для всех сущностей удалаён или активен
const UNARCHIVED_STATUS = 1; //значит не удалён
const ARCHIVED_STATUS = 2;//значит удалён
const ALL_ARCHIVE_STATUS = 3;//значит все типы