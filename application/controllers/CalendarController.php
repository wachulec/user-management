<?php

class CalendarController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->controllerName=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
    }

    public function indexAction()
    {
        $this->view->title="Kalendarz";
    }


}

