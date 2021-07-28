<?php


namespace Storage;


interface iCrmEntities
{
    function getEntityForStore (int $pragma_entity_id) : iEntityForStore;
}