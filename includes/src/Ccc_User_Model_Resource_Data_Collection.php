<?php

class Ccc_User_Model_Resource_Data_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
	protected function _construct() {
		$this->_init('ccc_user/data');
	}
}

?>