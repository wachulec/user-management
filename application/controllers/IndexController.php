<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->controllerName=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $this->view->title = "Pulpit";
        // action body
    }


}

