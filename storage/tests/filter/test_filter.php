<?php

require_once __DIR__ . '/../../../../lib/db/CRMDB.php';
require_once __DIR__ . '/CatalogExportsFilter.php';


$model = [
	'id' => null,
	'store_id' => null,
	'import_id' => null,
	'product_id' => null,
	'status_id' => null,
	'user_id' => null,
	'group_by' => 'id',
	'order' => 'asc',
	'date' => ['start' => time() - 86400 * 5, 'end' => time()],
];

$filter = new \Storage\CatalogExportsFilter(82, $model);
$sql = $filter->getSql();
echo '';