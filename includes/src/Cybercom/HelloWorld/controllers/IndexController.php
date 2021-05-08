<?php

class Cybercom_HelloWorld_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        echo 'Hello world...';
    }

    public function sayHelloAction()
    {
        echo 'Hello one more time...';
    }
}
