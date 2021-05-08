<?php

class Cybercom_Third_Block_Data extends Mage_Core_Block_Template
{
    public function gridPrint($x = null)
    {
        $model = Mage::getModel('cybercom_third/data')->getCollection()->getData();
        echo '<pre>';
        print_r($model);
    }
}
