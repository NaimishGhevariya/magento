<?php

class Ccc_Hulk_Model_Attribute extends Mage_Eav_Model_Attribute
{
    const MODULE_NAME = 'Ccc_Hulk';

    protected $_eventObject = 'attribute';

    protected function _construct()
    {
        $this->_init('hulk/attribute');
    }
}
