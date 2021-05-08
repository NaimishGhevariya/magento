<?php
class Ccc_Hulk_Block_Adminhtml_Hulk extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'hulk';
        $this->_controller = 'adminhtml_hulk';
        $this->_headerText = $this->__('Hulk Grid');
        parent::__construct();
    }
}
