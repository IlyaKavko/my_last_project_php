<?php


namespace Autocall\Pragma;


interface iPip
{
    function savePipe($id):void;

    function deletePipe($id):void;

    function getPips(): array|null;

}