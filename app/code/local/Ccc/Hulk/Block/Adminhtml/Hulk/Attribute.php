<?php

class Ccc_Hulk_Block_Adminhtml_Hulk_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'hulk';
        $this->_controller = 'adminhtml_hulk_attribute';
        $this->_headerText = Mage::helper('hulk')->__('Manage Attributes');
        $this->_addButtonLabel = Mage::helper('hulk')->__('Add New Attribute');
        parent::__construct();
    }
}
