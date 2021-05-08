<?php

class Cybercom_Third_Model_Resource_Data extends  Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('cybercom_third/data', 'thirdData_id');
    }
}
