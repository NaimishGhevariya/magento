<?php
class Ccc_Hulk_Model_Resource_Hulk extends Mage_Eav_Model_Entity_Abstract
{

	const ENTITY = 'hulk';

	public function __construct()
	{

		$this->setType(self::ENTITY)
			->setConnection('core_read', 'core_write');

		parent::__construct();
	}
}
