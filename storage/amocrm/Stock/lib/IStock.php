<?php


interface IStock
{
    public function coming_add(array $POST);

    public function section_add(array $POST);

    public function coming_delete(array $POST);

    public function update_quantity_products(array $POST);

    public function change_quantity_products(array $POST);

    public function comings_get(array $POST);

    public function section_get(array $POST);

    public function products_get(array $POST);

    public function update_coming(array $POST);

    public function update_product(array $POST);

    public function save_product(array $POST);

    public function del_img(string $ICON);

    public function save_img($FILES);
}