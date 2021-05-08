<?php

class Cybercom_Third_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        // $block = $this->getLayout()->createBlock('cybercom_third/data');
        // $block->setText("from controller");
        // $block->setText("content set !!");
        // $this->getLayout()->getBlock('content')->insert($block);
        $this->renderLayout();
    }

    public function gridAction()
    {
        echo "hello";

        $this->loadLayout();
        // $block = $this->getLayout()->createBlock('cybercom_third/data');
        $this->renderLayout();
        // Zend_Debug::dump($this->getLayout()->getUpdate()->getHandles());
    }

    public function testAction()
    {
        $this->loadLayout();
        $this->renderLayout();
        // echo get_class($this);
        Zend_Debug::dump($this->getLayout()->getUpdate()->getHandles());
    }
    public function saveAction()
    {
        $thirdData = Mage::getModel('cybercom_third/data')->setFirstName('naimish');
        try {
            $thirdData->save();
            echo 'Successfull';
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        echo '<pre>';
        print_r($thirdData);

        print_r($thirdData->getFirstName());
        echo '</pre>';
    }
}
