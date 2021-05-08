<?php

// class Ccc_Hulk_Adminhtml_Hulk_GroupController extends Mage_Adminhtml_Controller_Action
// {
//     protected function _isAllowed()
//     {
//         return Mage::getSingleton('admin/session')->isAllowed('hulk/hulk');
//     }

//     public function saveAction()
//     {
//         $model = Mage::getModel('eav/entity_attribute_group');

//         $model->setAttributeGroupName($this->getRequest()->getParam('attribute_group_name'))
//             ->setAttributeSetId($this->getRequest()->getParam('attribute_set_id'));

//         if ($model->itemExists()) {
//             Mage::getSingleton('hulk/session')->addError(Mage::helper('hulk')->__('A group with the same name already exists.'));
//         } else {
//             try {
//                 $model->save();
//             } catch (Exception $e) {
//                 Mage::getSingleton('hulk/session')->addError(Mage::helper('hulk')->__('An error occurred while saving this group.'));
//             }
//         }
//     }
// }
