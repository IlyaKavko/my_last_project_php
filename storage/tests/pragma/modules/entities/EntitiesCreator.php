<?php


namespace Storage\Test;


use Storage\Entity;
use Storage\iEntity;
use Storage\iEntityForStore;

trait EntitiesCreator {
	static function getUniqueEntity() : iEntity{
		$crm_entity = self::createCrmEntity();
		return new Entity($crm_entity);
	}

	static private function createCrmEntity($group_name = 'leads') : iEntityForStore {
		return AbstractCreator::getTestEntities()->createEntity($group_name);
	}

	static function clearEntities() : void {
		AbstractCreator::getTestEntities()::clearTestEntities();
	}
}