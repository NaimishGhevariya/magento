<?php

class Ccc_User_Block_Adminhtml_User_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                'method' => 'post'
            )
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        $fieldset = $form->addFieldset('user_form', array('legend' => Mage::helper('user')->__('User information')));

        $fieldset->addField('first_name', 'text', array(
            'label' => Mage::helper('user')->__('First Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'first_name',
        ));

        $fieldset->addField('last_name', 'text', array(
            'label' => Mage::helper('user')->__('Last Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'last_name',
        ));
        //Edit
        if (Mage::getSingleton('adminhtml/session')->getUserData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getUserData());
            Mage::getSingleton('adminhtml/session')->setUserData(null);
        } elseif (Mage::registry('user_data')) {
            $form->setValues(Mage::registry('user_data')->getData());
        }
        return parent::_prepareForm();
    }
}
