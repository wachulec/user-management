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
        //pobieranie danych dla wszystkich...
        $this->view->customers=$Customer->fetchAll();
        //...dla konkretnego rekordu
        $customer_id=$this->getRequest()->getParam('customers_id');
        $this->view->customer=$this->view->customers->getRow($customer_id-1);
        $this->view->c_tags=explode('.',$this->view->customer['tags']);
    }

    public function editAction()
    {
        $customer_id=$this->getRequest()->getParam('customers_id');
        $this->view->title="Edycja";
        $Customer=new Application_Model_DbTable_Customers();
        $obj=$Customer->fin($customer_id)->current();
        if(!$obj){
            throw new Zend_Controller_Action_Exception('Błędny adres. Brak rekordu dla wybranego klienta!', 404);
        }
        $this->view->form=new Application_Form_EditCustomer();
        $this->view->form->populate($obj->toArray());
        $url=$this->view->url(['action'=>'update','customer_id'=>$customer_id]);
        $this->view->form=setAction($url);
        $this->view->object=$obj;
    }

    public function updateAction()
    {
        // action body
    }


}