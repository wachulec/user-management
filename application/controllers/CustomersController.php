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
        $rowsArray=$this->view->customer->findDependentRowset('Application_Model_DbTable_CustomersHasTags')->toArray();        
        $c_tags=array();
        foreach($rowsArray as $row){
            $c_tags[]=(new Application_Model_DbTable_Tags())->find($row['tag_id'])->current()['name'];
        }
        $this->view->c_tags=$c_tags;      
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
        
        if($this->getRequest()->isPost()) {
            $data=$this->getRequest()->getParam('data');
            $tags=explode('.',$data);
            $Tags=new Application_Model_DbTable_Tags();
            //W foreachu dodaje nieistniejące jeszcze w słowniku tagi
            foreach($tags as $tag){
                $query=$Tags->select()->where('name LIKE ?', $tag);
                $result=$Tags->fetchRow($query);
                if($result==null){
                    $row = [
                        'name'=>$tag
                    ];
                    $tagsId=$Tags->insert($row);                    
                }else{
                    $tagsId=$result->tags_id;
                }
                $CustomersHasTags=new Application_Model_DbTable_CustomersHasTags();
                $q=$CustomersHasTags->select()->where('tag_id=?',$tagsId)->where('customer_id=?',$customer_id);
                $r=$CustomersHasTags->fetchRow($q);
                if($r==null){   
                    $relation=[
                        'tag_id'=>$tagsId,
                        'customer_id'=>$customer_id
                    ];
                    $CustomersHasTags->createRow($relation)->save();
                }
            }                                    
        }else{
            throw new Zend_Controller_Action_Exception('Błędny adres. Brak rekordu wybranego klienta', 404);
        }
    }
}



