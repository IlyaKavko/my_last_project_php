<?php


namespace Autocall\Bitrix;


class BitrixSettings implements iBitrixSettings
{
    private string $id;

    public function __construct($id)
    {
        $this->id = $id;

    }

}