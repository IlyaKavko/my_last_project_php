<?php


namespace Storage;

require_once __DIR__ . '/IImportStruct.php';

interface iImport extends iBasis, IImportStruct
{
    function getOwnedProductImports () : array;
    function isExported () : bool;
}