<?php


namespace Storage;


interface iEntities
{
    function getEntity (int $pragma_entity_id) : iEntity;
}