<?php
class Ccc_Hulk_Model_Resource_Hulk_Collection extends Mage_Catalog_Model_Resource_Collection_Abstract
{
	public function __construct()
	{
		$this->setEntity('hulk');
		parent::__construct();
	}
}
