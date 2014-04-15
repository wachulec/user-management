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
        
        //jeśli wybrano konkretnego klienta
        $customer_id=$this->getRequest()->getParam('customers_id');
        if(isset($customer_id) and !is_null($customer_id) ){
            $s=$Customer->select()->where('customers_id=?',$customer_id);
            $this->view->customer=$Customer->fetchRow($s);          
        }
    }

    public function detailsAction()
    {
        //
    }


}





