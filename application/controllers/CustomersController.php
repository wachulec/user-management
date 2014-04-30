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
        $this->view->tags_update_url=$this->view->url(['action'=>'updatetags','customer_id'=>$customer_id]);
    }

    public function editAction()
    {
        $customer_id=$this->getRequest()->getParam('customers_id');
        $this->view->title="Edycja";
        $Customer=new Application_Model_DbTable_Customers();
        $obj=$Customer->find($customer_id)->current();
        if(!$obj){
            throw new Zend_Controller_Action_Exception('Błędny adres. Brak rekordu dla wybranego klienta!', 404);
        }
        $this->view->form=new Application_Form_EditCustomer();
        $this->view->form->populate($obj->toArray());
        $url=$this->view->url(['action'=>'update','customer_id'=>$customer_id]);
        $this->view->form->setAction($url);
        $this->view->object=$obj;
    }

    public function updateAction()
    {
        $customer_id=$this->getRequest()->getParam('customers_id');
        $Customer=new Application_Model_DbTable_Customers();
        $obj=$Customer->find($customer_id)->current();
        if(!$obj){
            throw new Zend_Controller_Action_Exception('Błędny adres. Brak rekordu dla wybranego klienta!', 404);
        }
        
        if($this->getRequest()->isPost()) {
            $form=new Application_Form_EditCustomer();
            if($form->isValid($this->getRequest()->getPost())){
                $data=$form->getValues();
                $obj->setFromArray($data);
                $obj->save();
                return $this->_helper->redirector(
                        'edit','customers',null,array('customers_id'=>$customer_id)
                        );
            }
            $this->view->form=$form;
        }else{
            throw new Zend_Controller_Action_Exception('Błędny adres. Brak rekordu wybranego klienta', 404);
        }
    }

    public function updatetagsAction()
    {
        $customer_id=$this->getRequest()->getParam('customers_id');
        $Customer=new Application_Model_DbTable_Customers();
        $obj=$Customer->find($customer_id)->current();
        if(!$obj){
            throw new Zend_Controller_Action_Exception('Błędny adres. Brak rekordu dla wybranego klienta!', 404);
        }
        
        if(true/*$this->getRequest()->isPost()*/) {
            $data="klient.finanse";//$this->getRequest()->getParam('data');
            $tags=explode('.',$data);
            $Tags=new Application_Model_DbTable_Tags();
            //$existTags=$Tags->fetchAll();
            foreach($tags as $tag){
                $query=$Tags->select()->where('name LIKE ?', $tag);
            }
            
            
        }else{
            throw new Zend_Controller_Action_Exception('Błędny adres. Brak rekordu wybranego klienta', 404);
        }
    }


}



