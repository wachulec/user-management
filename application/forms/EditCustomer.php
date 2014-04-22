<?php

class Application_Form_EditCustomer extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->addElement('text','firstname',['label'=>'Imię:']);
    }


}

