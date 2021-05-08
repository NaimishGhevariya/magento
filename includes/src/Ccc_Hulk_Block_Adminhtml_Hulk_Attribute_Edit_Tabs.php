<?php

class Ccc_Hulk_Block_Adminhtml_Hulk_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('hulk_attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('hulk')->__('Attribute Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('main', array(
            'label'     => Mage::helper('hulk')->__('Properties'),
            'title'     => Mage::helper('hulk')->__('Properties'),
            'content'   => $this->getLayout()->createBlock('hulk/adminhtml_hulk_attribute_edit_tab_main')->toHtml(),
            'active'    => true
        ));

        $model = Mage::registry('entity_attribute');

        $this->addTab('labels', array(
            'label'     => Mage::helper('hulk')->__('Manage Label / Options'),
            'title'     => Mage::helper('hulk')->__('Manage Label / Options'),
            'content'   => $this->getLayout()->createBlock('hulk/adminhtml_hulk_attribute_edit_tab_options')->toHtml(),
        ));

        if ('select' == $model->getFrontendInput()) {
            $this->addTab('options_section', array(
                'label'     => Mage::helper('hulk')->__('Options Control'),
                'title'     => Mage::helper('hulk')->__('Options Control'),
                'content'   => $this->getLayout()->createBlock('hulk/adminhtml_hulk_attribute_edit_tab_options')->toHtml(),
            ));
        }

        return parent::_beforeToHtml();
    }
}
