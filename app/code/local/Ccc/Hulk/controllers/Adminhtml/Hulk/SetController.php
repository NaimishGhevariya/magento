<?php

class Ccc_Hulk_Adminhtml_Hulk_setController extends Mage_Adminhtml_Controller_Action
{
    protected $_forcedFormKeyActions = [];

    protected function _setTypeId()
    {
        Mage::register(
            'entityType',
            Mage::getModel('hulk/hulk')->getResource()->getTypeId()
        );
    }


    public function preDispatch()
    {
        $this->_setForcedFormKeyActions('delete');
        return parent::preDispatch();
    }

    public function indexAction()
    {
        $this->_title($this->__('Hulk'))
            ->_title($this->__('Attributes'))
            ->_title($this->__('Manage Attribute Sets'));

        $this->_setTypeId();

        $this->loadLayout();
        $this->_setActiveMenu('hulk');


        $this->_addBreadcrumb(Mage::helper('hulk')->__('Hulk'), Mage::helper('hulk')->__('Hulk'));
        $this->_addBreadcrumb(
            Mage::helper('hulk')->__('Manage hulk Sets'),
            Mage::helper('hulk')->__('Manage hulk Sets')
        );

        $this->_addContent($this->getLayout()->createBlock('hulk/adminhtml_hulk_attribute_set_toolbar_main'));

        $this->_addContent($this->getLayout()->createBlock('hulk/adminhtml_hulk_attribute_set_grid'));

        $this->renderLayout();
    }

    public function addAction()
    {
        $this->_title($this->__('Hulk'))
            ->_title($this->__('Attributes'))
            ->_title($this->__('Manage Hulk Attribute Sets'))
            ->_title($this->__('New Set'));

        $this->_setTypeId();

        $this->loadLayout();
        $this->_setActiveMenu('hulk');

        $this->_addContent($this->getLayout()->createBlock('hulk/adminhtml_hulk_attribute_set_toolbar_add'));

        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_title($this->__('Hulk'))
            ->_title($this->__('Attributes'))
            ->_title($this->__('Manage Attribute Sets'));

        $this->_setTypeId();
        $attributeSet = Mage::getModel('eav/entity_attribute_set')
            ->load($this->getRequest()->getParam('id'));
        if (!$attributeSet->getId()) {
            $this->_redirect('*/*/index');
            return;
        }

        $this->_title($attributeSet->getId() ? $attributeSet->getAttributeSetName() : $this->__('New Set'));

        Mage::register('current_attribute_set', $attributeSet);

        $this->loadLayout();
        $this->_setActiveMenu('Hulk');

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addBreadcrumb(Mage::helper('hulk')->__('hulk'), Mage::helper('hulk')->__('hulk'));
        $this->_addBreadcrumb(
            Mage::helper('hulk')->__('Manage Hulk Attribute Sets'),
            Mage::helper('hulk')->__('Manage Hulk Attribute Sets')
        );

        $this->_addContent($this->getLayout()->createBlock('hulk/adminhtml_hulk_attribute_set_main'));

        $this->renderLayout();
    }

    protected function _getEntityTypeId()
    {
        if (is_null(Mage::registry('entityType'))) {
            $this->_setTypeId();
        }
        return Mage::registry('entityType');
    }

    public function saveAction()
    {
        $entityTypeId   = $this->_getEntityTypeId();
        $hasError       = false;
        $attributeSetId = $this->getRequest()->getParam('id', false);
        $isNewSet       = $this->getRequest()->getParam('gotoEdit', false) == '1';

        /* @var $model Mage_Eav_Model_Entity_Attribute_Set */
        $model  = Mage::getModel('eav/entity_attribute_set')
            ->setEntityTypeId($entityTypeId);

        /** @var $helper Mage_Adminhtml_Helper_Data */
        $helper = Mage::helper('hulk');

        try {
            if ($isNewSet) {
                //filter html tags
                $name = $helper->stripTags($this->getRequest()->getParam('attribute_set_name'));
                $model->setAttributeSetName(trim($name));
            } else {
                if ($attributeSetId) {
                    $model->load($attributeSetId);
                }
                if (!$model->getId()) {
                    Mage::throwException(Mage::helper('hulk')->__('This attribute set no longer exists.'));
                }
                $data = Mage::helper('core')->jsonDecode($this->getRequest()->getPost('data'));

                //filter html tags
                $data['attribute_set_name'] = $helper->stripTags($data['attribute_set_name']);

                $model->organizeData($data);
            }

            $model->validate();
            if ($isNewSet) {
                $model->save();
                $model->initFromSkeleton($this->getRequest()->getParam('skeleton_set'));
            }
            $model->save();
            $this->_getSession()->addSuccess(Mage::helper('hulk')->__('The attribute set has been saved.'));
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
            $hasError = true;
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('hulk')->__('An error occurred while saving the attribute set.')
            );
            $hasError = true;
        }

        if ($isNewSet) {
            if ($hasError) {
                $this->_redirect('*/*/add');
            } else {
                $this->_redirect('*/*/edit', array('id' => $model->getId()));
            }
        } else {
            $response = array();
            if ($hasError) {
                $this->_initLayoutMessages('adminhtml/session');
                $response['error']   = 1;
                $response['message'] = $this->getLayout()->getMessagesBlock()->getGroupedHtml();
            } else {
                $response['error']   = 0;
                $response['url']     = $this->getUrl('*/*/');
            }
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        }
    }

    public function deleteAction()
    {
        $setId = $this->getRequest()->getParam('id');
        try {
            Mage::getModel('eav/entity_attribute_set')
                ->setId($setId)
                ->delete();

            $this->_getSession()->addSuccess($this->__('The attribute set has been removed.'));
            $this->getResponse()->setRedirect($this->getUrl('*/*/'));
        } catch (Exception $e) {
            $this->_getSession()->addError($this->__('An error occurred while deleting this set.'));
            $this->_redirectReferer();
        }
        $this->_redirect('*/*/');
    }

    protected function _setForcedFormKeyActions($actionNames)
    {
        $actionNames = (is_array($actionNames)) ? $actionNames : (array)$actionNames;
        $actionNames = array_merge($this->_forcedFormKeyActions, $actionNames);
        $actionNames = array_unique($actionNames);
        $this->_forcedFormKeyActions = $actionNames;
    }
}
