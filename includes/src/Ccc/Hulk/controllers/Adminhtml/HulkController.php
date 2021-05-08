<?php

class Ccc_Hulk_Adminhtml_HulkController extends Mage_Adminhtml_Controller_Action
{

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('hulk/hulk');
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('hulk');
        $this->_title('Hulk Grid');

        $this->_addContent($this->getLayout()->createBlock('hulk/adminhtml_hulk'));

        $this->renderLayout();
    }

    protected function _initHulk()
    {
        $this->_title($this->__('Hulk'))
            ->_title($this->__('Manage hulks'));

        $hulkId = (int) $this->getRequest()->getParam('id');
        $hulk   = Mage::getModel('hulk/hulk')
            ->setStoreId($this->getRequest()->getParam('store', 0))
            ->load($hulkId);

        Mage::register('current_hulk', $hulk);
        Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
        return $hulk;
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $hulkId = (int) $this->getRequest()->getParam('id');
        $hulk   = $this->_initHulk();

        if ($hulkId && !$hulk->getId()) {
            $this->_getSession()->addError(Mage::helper('hulk')->__('This hulk no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        $this->_title($hulk->getName());

        $this->loadLayout();

        $this->_setActiveMenu('hulk/hulk');

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->renderLayout();
    }

    public function saveAction()
    {

        try {

            $hulkData = $this->getRequest()->getPost('account');

            $hulk = Mage::getSingleton('hulk/hulk');

            if ($hulkId = $this->getRequest()->getParam('id')) {

                if (!$hulk->load($hulkId)) {
                    throw new Exception("No Row Found");
                }
                Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
            }

            $hulk->addData($hulkData);

            $hulk->save();

            Mage::getSingleton('core/session')->addSuccess("Hulk data added.");
            $this->_redirect('*/*/');
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->_redirect('*/*/');
        }
    }

    public function deleteAction()
    {
        try {

            $hulkModel = Mage::getModel('hulk/hulk');

            if (!($hulkId = (int) $this->getRequest()->getParam('id')))
                throw new Exception('Id not found');

            if (!$hulkModel->load($hulkId)) {
                throw new Exception('hulk does not exist');
            }

            if (!$hulkModel->delete()) {
                throw new Exception('Error in delete record', 1);
            }

            Mage::getSingleton('core/session')->addSuccess($this->__('The hulk has been deleted.'));
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/');
    }
}
