<?php


class StockDB
{
    private $db;

    public function __construct()
    {
        $this->db = unserialize(file_get_contents(__DIR__ . '/db.txt'));
    }

    function change_quantity_product(int $account_id, string $product_id, string $new_value)
    {
        foreach ($this->db[$account_id]['products'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] = $new_value;
            }
        }
        self::save();
    }


    function AddAccountId(int $id)
    {
        $search_id = $this->search_key($id);

        switch ($search_id) {
            case false:
                $arr3 = array($id => array('section' => array(),
                    'products' => array(),
                    'coming' => array()
                ));
                file_put_contents(__DIR__ . '/db.txt', serialize($this->db += $arr3));
                break;
            case true:
                break;
        }

    }

    function update_coming(int $account_id, string $coming_id, array $new_data)
    {
        foreach ($this->db[$account_id]['coming'] as &$item) {
            if ($item['id_supply'] == $coming_id) {
                $item = $new_data;
            }
        }
        self::save();

    }


    function update_product(int $account_id, string $product_id, array $new_data)
    {
        foreach ($this->db[$account_id]['products'] as &$item) {
            if ($item['id'] == $product_id) {
                foreach ($new_data as $key => $value) {
                    $item["$key"] = $value;
                }
            }
        }
        self::save();
    }


    function get_all_section(int $account_id): array
    {
        $arr = [];
        foreach ($this->db[$account_id]['section'] as $item) {
            array_push($arr, $item);
        }
        return $arr;
    }

    function get_all_coming(int $account_id): array
    {
        $arr = [];
        foreach ($this->db[$account_id]['coming'] as $item) {
            array_push($arr, $item);
        }
        return $arr;
    }

    function get_all_products(int $account_id): array
    {
        $arr = [];
        foreach ($this->db[$account_id]['products'] as $item) {
            array_push($arr, $item);
        }
        return $arr;
    }

    function create_new_section(int $account_id, string $name_new_section, string $description_new_section, string $id)
    {
        $new_section = array('name' => $name_new_section, 'description' => $description_new_section, 'id' => $id);
        $this->db[$account_id]['section'][] = $new_section;
        self::save();
    }

    function create_new__product(int $account_id, array $data)
    {
        $this->db[$account_id]['products'][] = $data;
        self::save();
    }

    function create_new_coming(int $account_id, array $data)
    {
        $this->db[$account_id]['coming'][] = $data;
        self::save();
    }

    function update_all_quantity(int $account_id)
    {
        foreach ($this->db[$account_id]['products'] as &$item) {
                $item['quantity'] =  strval(self::product_quantity_sum($account_id, $item['id']));
        }
        self::save();
    }


    function update_quantity__id(int $account_id, string $product_id, string $new_value)
    {
        foreach ($this->db[$account_id]['products'] as &$item) {
            if ($product_id == $item['id']) {
                $item['quantity'] = $new_value;
                break;
            }
        }
        self::save();
    }


    function product_quantity_sum(int $account_id, string $id_product)
    {
        $sum = 0;
        foreach ($this->db[$account_id]['coming'] as $item) {
            foreach ($item['data_supply'] as $data) {
                if ($data['id'] == $id_product) {
                    $sum += intval($data['quantity']);
                }
            }
        }
        return $sum;
    }

    function delete_section(int $account_id, string $name_section)
    {
        foreach ($this->db[$account_id]['section'] as $data => $value) {
            if ($value['name'] == $name_section) {
                unset($this->db[$account_id]['section'][$data]);
            }
        }
        self::save();
    }

    function delete_coming(int $account_id, string $id_coming)
    {
        foreach ($this->db[$account_id]['coming'] as $data => $value) {
            if ($value['id_supply'] == $id_coming) {
                unset($this->db[$account_id]['coming'][$data]);
            }
        }
        self::save();
    }

    function delete_product(int $account_id, string $name_product)
    {
        foreach ($this->db[$account_id]['products'] as $data => $value) {
            if ($value['name'] == $name_product) {
                unset($this->db[$account_id]['products'][$data]);
            }
        }
        self::save();
    }

    function delete($id)
    {
        unset($this->db[$id]);
        $this->save();
    }

    function change($id, array $data)
    {
        $this->db[$id] = $data;
        $this->save();
    }

    function print_AccountId($id)
    {
        echo json_encode($this->db[$id]);
    }


    function print_db()
    {


//        foreach ($this->db['28967662']['products'] as $key => &$item){
//            if(strlen($item['icon']) < 10){
//            unset($this->db['28967662']['products'][$key]);
//
//            }
//        }
//
//self::save();

        echo json_encode($this->db);



    }

    private
    function search_key($id): bool
    {
        return array_key_exists($id, $this->db);
    }

    private
    function save()
    {
        file_put_contents(__DIR__ . '/db.txt', serialize($this->db));
    }


}