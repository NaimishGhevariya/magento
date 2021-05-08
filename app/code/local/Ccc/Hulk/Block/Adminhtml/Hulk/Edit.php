<?php

class Ccc_Hulk_Block_Adminhtml_Hulk_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'hulk';
        $this->_controller = 'adminhtml_hulk';
        parent::__construct();
    }
}
