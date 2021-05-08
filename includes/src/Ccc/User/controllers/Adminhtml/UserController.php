<?php

class Ccc_User_Adminhtml_UserController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('Ccc/user')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Manage User'),
                Mage::helper('adminhtml')->__('Manage User')
            );
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('user_admin/user'))
            ->renderLayout();
        $this->_title($this->__("User Grid"));
    }
    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $userId = $this->getRequest()->getParam('id');
        $userModel = Mage::getModel('ccc_user/data')->load($userId);
        if ($userModel->getData() || $userId == 0) {
            Mage::register('user_data', $userModel);
            $this->loadLayout();
            $this->_setActiveMenu('Ccc/user');

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true)
                ->_addContent($this->getLayout()->createBlock('user/adminhtml_user_edit'))
                ->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('user')->__(
                'User does not exists!'
            ));
            $this->_redirect('*/*/');
        }
    }
    public function saveAction()
    {
        if ($this->getRequest()->getPost()) {
            try {
                $postData = $this->getRequest()->getPost();
                $id = $this->getRequest()->getParam('id');
                $userModel = Mage::getModel('ccc_user/data')->load($id);
                if ($userModel->getId()) {
                    $userModel->setFirstName($postData['first_name'])
                        ->setLastName($postData['last_name'])
                        ->save();
                } else {
                    $userModel->setId($this->getRequest()->getParam('id'))
                        ->setFirstName($postData['first_name'])
                        ->setLastName($postData['last_name'])
                        ->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('User saved successfully!'));
                Mage::getSingleton('adminhtml/session')->setUserData(false);
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setUserData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $userModel = Mage::getModel('ccc_user/data');
                $userModel->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('User deleted successfully!'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }
}
