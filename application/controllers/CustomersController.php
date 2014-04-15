<?php

class CustomersController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function listAction()
    {
        //wyciąganie danych do tabeli
        $this->view->title="Klienci";
        $Customer=new Application_Model_DbTable_Customers();
        $this->view->customers=$Customer->fetchAll();
    }

    public function detailsAction()
    {
        //jeśli wybrano konkretnego klienta
        $this->view->title="Szczegóły";
        $Customer=new Application_Model_DbTable_Customers();
        $this->view->customers=$Customer->fetchAll();
        $customer_id=$this->getRequest()->getParam('customers_id');
        $this->view->customer=$this->view->customers->getRow($customer_id-1);
    }


}





