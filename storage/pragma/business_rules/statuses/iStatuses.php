<?php


namespace Storage;


interface iStatuses
{
    function getStatusByCode (string $code) : iStatus;

    function getStatus (int $status_id) : iStatus;

    static function getStatusModels () : array;
}