<?php
require_once __DIR__ . '/../../autocall/amocrm/Controller/Hook.php';
require_once __DIR__ . '/LogTask.php';


class TimeCreateLeads
{

    static function start($PostData)
    {
        $subdomain = $PostData['subdomain'];
        $element_type = intval($PostData['element_type']);
        $element_id = intval($PostData['element_id']);
        $referrer = $subdomain . '.amocrm.ru';
        $logger = LogTask::create_log($referrer, 'api_leads');
        $logger->add('$PostData', $PostData);

        try {
            \Services\Factory::init('pmLirax', $referrer, $logger);
            $node = \Services\Factory::getNodesService()->findAmocrmNodeCode('pmLirax', $subdomain);
            $api = new \RestApi\Amocrm\AmoCRM_API($node, $logger);


            $params = array(
                'id' => $element_id
            );
            $logger->add('$element_type', $element_type);
            return match ($element_type) {
                1 => $api->contacts($params, 'GET'),
                2 => $api->leads($params, 'GET'),
                3 => $api->companies($params, 'GET'),
                12 => $api->customers($params, 'GET'),
            };
        } catch (Exception $e) {
            $logger->send_error($e);
            echo json_encode(array('error' => 486));
        }

    }

}
