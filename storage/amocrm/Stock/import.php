<?php
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../../../../lib/log/LogJSON.php';
require_once __DIR__ . '/../../CONSTANTS.php';
require_once __DIR__ . '/lib/Stock.php';

$FILES = $_FILES;


if ($FILES) {

    $log_writer = new LogJSON('TEST', \Storage\WIDGET_NAME, 'import');
    try {
        $log_writer->add('$FILES', $FILES);

        $fileData = ($_FILES['files'] ?? null);
        $name = $fileData['name'];
        $subdomain = explode('.', import::getReferrer())[0];

        $full_path = "/var/www/pragma/data/projects/python_project-dev/PragmaStorage/ImportProduct/temp_storage/" . $subdomain;
        mkdir($full_path, 0755, true);
        $success = move_uploaded_file($fileData['tmp_name'], $full_path . '/' . $name);

        $import = new import($subdomain, $log_writer);
        $import->create_import($name);
    }catch (Exception $e){
        $log_writer->send_error($e);
    }

}


class import
{

    private Stock $Stock;
    private LogJSON $Log;

    public function __construct(private string $subdomain, $log_writer)
    {
        $this->Log = $log_writer;
        $referrer = $this->subdomain . ".amocrm.ru";
        $this->Stock = new Stock($referrer, $log_writer);
    }

    function create_import($name)
    {
        $part_1_path = $this->create_import_tovars($name);
        $path = __DIR__ . '/../../../../Desktop' . $part_1_path;
        $this->Log->add('$path', $path);
        $file = json_decode(file_get_contents($path), true);
        $data = $file['product'];
        $treeData = array_slice($data, 0, 3);
        $this->Log->add('$treeData', $treeData);

        $data = array(
            "product" => $treeData,
            "temp" => $part_1_path
        );

        echo json_encode($data);
        die();
    }


    function create_import_tovars($name): string
    {
        $POST = array(
            "referrer" => $this->subdomain,
            "delimiter" => ';',
            "name_file" => $name,
        );
        $data = $this->Stock->create_import_tovars($POST);
        return $data['url'];
    }

    static function getReferrer()
    {
        return parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    }


}