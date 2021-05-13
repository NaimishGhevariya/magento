<?php

class Ccc_Vendor_Adminhtml_VendorproductController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('vendor/product');
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('vendor');
        $this->_title('Vendor Product Grid');

        $this->_addContent($this->getLayout()->createBlock('vendor/adminhtml_vendorproduct'));

        $this->renderLayout();
    }

    protected function _initVendorProduct()
    {
        $this->_title($this->__('Vendor'))
            ->_title($this->__('Manage vendor productss'));

        $productId = (int) $this->getRequest()->getParam('id');
        $product   = Mage::getModel('vendor/adminhtml_vendorproduct')
            ->setStoreId($this->getRequest()->getParam('store', 0))
            ->load($productId);

        Mage::register('current_vendor', $product);
        Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
        return $product;
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $productId = (int) $this->getRequest()->getParam('id');
        $product   = $this->_initVendorProduct();

        if ($productId && !$product->getId()) {
            $this->_getSession()->addError(Mage::helper('vendor')->__('This vendor product no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        $this->_title($product->getName());

        $this->loadLayout();

        $this->_setActiveMenu('vendor/vendor');

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->renderLayout();
    }

    public function saveAction()
    {

        try {

            $vendorData = $this->getRequest()->getPost('account');

            $product = Mage::getSingleton('vendor/adminhtml_vendorproduct ');

            if ($productId = $this->getRequest()->getParam('id')) {

                if (!$product->load($productId)) {
                    throw new Exception("No Row Found");
                }
                Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
            }

            $product->addData($vendorData);

            $product->save();

            Mage::getSingleton('core/session')->addSuccess("Vendor product data added.");
            $this->_redirect('*/*/');
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->_redirect('*/*/');
        }
    }

    public function deleteAction()
    {
        try {

            $productModel = Mage::getModel('vendor/adminhtml_product');

            if (!($productId = (int) $this->getRequest()->getParam('id')))
                throw new Exception('Id not found');

            if (!$productModel->load($productId)) {
                throw new Exception('vendor product does not exist');
            }

            if (!$productModel->delete()) {
                throw new Exception('Error in delete record', 1);
            }

            Mage::getSingleton('core/session')->addSuccess($this->__('The vendor product has been deleted.'));
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/');
    }
}
