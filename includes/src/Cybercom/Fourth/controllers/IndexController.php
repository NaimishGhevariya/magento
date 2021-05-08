<?php

class Cybercom_Fourth_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        echo "inside fourth controller";
    }

    public function saveAction()
    {
        $model = Mage::getModel('cybercom_fourth/fourthData');
        echo "<pre>";
        var_dump($model);
    }
}
