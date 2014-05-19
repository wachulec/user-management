<?php

class CustomersController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->controllerName=Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
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
        $Customers=new Application_Model_DbTable_Customers();
        $customer_id=$this->getRequest()->getParam('customers_id');
        $query=$Customers->select()->where("customers_id=?",$customer_id);
        $this->view->customer=$Customers->fetchRow($query);
        $this->view->customers=$Customers->fetchAll();
               
        $rowsArray=$this->view->customer->findDependentRowset('Application_Model_DbTable_CustomersHasTags')->toArray();        
        $c_tags=array();
        foreach($rowsArray as $row){
            $c_tags[]=(new Application_Model_DbTable_Tags())->find($row['tag_id'])->current()['name'];
        }
        $this->view->c_tags=$c_tags;      
        $this->view->tags_update_url=$this->view->url(['action'=>'updatetags','customer_id'=>$customer_id]);
        $this->view->tags_remove_url=$this->view->url(['action'=>'removetags','customer_id'=>$customer_id]);
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
                        'details','customers',null,array('customers_id'=>$customer_id)
                        );
            }
            $this->view->form=$form;
        }else{throw new Zend_Controller_Action_Exception('Błędny adres. Brak rekordu wybranego klienta', 404);}
            
    }

    public function updatetagsAction()
    {
        //blokuje Layout żeby móc klorzystać z jsona
        $this->getHelper('Layout')->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();       
        $this->getResponse()->setHeader('Content-Type', 'application/json');
       
        $customer_id=$this->getRequest()->getParam('customers_id');
        $Customer=new Application_Model_DbTable_Customers();
        $obj=$Customer->find($customer_id)->current();
        if(!$obj){
            throw new Zend_Controller_Action_Exception('Błędny adres. Brak rekordu dla wybranego klienta!', 404);
        }
        
        if($this->getRequest()->isPost()) {
            $data=$this->getRequest()->getParam('data');
            if(!empty($data)) {
                $tags=explode('.',$data);
            } else{
                $tags=null;
            }
            $Tags=new Application_Model_DbTable_Tags();
            //W foreachu dodaje nieistniejące jeszcze w słowniku tagi
            $addedTags=array();
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
                    $addedTags[]=$tag;
                }
            }
            //zwaracam wyniki w formacie json w zapytaniu ajax w obsłudze kliknięcia linku #dialog_link (jquery.ready.js)
            echo json_encode($addedTags);
        }else{throw new Zend_Controller_Action_Exception('Błędny adres. Brak rekordu wybranego klienta', 404);}
        
    }

    public function createformAction()
    {
        $this->view->title="Dodaj nowego klienta";
        $this->view->form=new Application_Form_EditCustomer();
        $url=$this->view->url(['action'=>'create']);
        $this->view->form->setAction($url);
    }

    public function createAction()
    {
        $this->getHelper('Layout')->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();     
        if($this->getRequest()->isPost()){
            $form=new Application_Form_EditCustomer();
            if($form->isValid($this->getRequest()->getPost())) {
                /*dane z formularza pobieram do $data*/
                $data=$form->getValues();
                $Customers=new Application_Model_DbTable_Customers();
                $customers_id=$Customers->insert($data);
                return $this->_helper->redirector(
                        'details','customers',null,['customers_id'=>$customers_id]
                        );/*akca, kontroler, ?, parametry*/
            }
            /*formularz wraca do widoku akcji, jeśli nie przejdzie walidacji*/
            $this->view->form=$form;
        }else{throw new Zend_Controller_Action_Exception('Błedny adres!', 404);}
    }

    public function deleteAction()
    {
        $this->getHelper('Layout')->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();       
                
        $customers_id=$this->getRequest()->getParam('customers_id');
        $Customers=new Application_Model_DbTable_Customers();
        $obj=$Customers->find($customers_id)->current();
        if(!$obj) {
            throw new Zend_Controller_Action_Exception('Błedny adres!', 404);
        }
        $obj->delete();
        return $this->_helper->redirector('list');
    }

    public function removetagsAction()
    {
        $this->getHelper('Layout')->disableLayout();
        $this->getHelper('ViewRenderer')->setNoRender();       
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        
        $customer_id=$this->getRequest()->getParam('customers_id');
        $Customer=new Application_Model_DbTable_Customers();
        $obj=$Customer->find($customer_id)->current();
        if(!$obj){
            throw new Zend_Controller_Action_Exception('Błędny adres. Brak rekordu dla wybranego klienta!', 404);
        }
        
        if($this->getRequest()->isPost()) {
            
            $tag=trim($this->getRequest()->getParam('tag'));
            $Tags=new Application_Model_DbTable_Tags();
            $query=$Tags->select()->where('name LIKE ?',$tag);
            $result=$Tags->fetchRow($query);
            if($result!=null){
                $tags_id=$result->tags_id;            
                $CustomersHasTags=new Application_Model_DbTable_CustomersHasTags();            
                $CustomersHasTags->delete(
                        [
                            'tag_id=?'=>$tags_id,
                            'customer_id=?'=>$customer_id
                        ]
                    );
            }
        }else{throw new Zend_Controller_Action_Exception('Błędny adres. Brak rekordu wybranego klienta', 404);}
        
    }


}











