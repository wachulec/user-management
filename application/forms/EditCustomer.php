<?php

class Application_Form_EditCustomer extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->addElement('text','firstname',['label'=>'Imię:']);
        $this->addElement('text','lastname',['label'=>'Nazwisko:']);
        $this->addElement('text','phone',['label'=>'Telefon:']);
        $this->addElement('text','email',['label'=>'Email:']);
        $this->addElement('text','type',['label'=>'Typ:']);
        
        $this->addElement('text','street',['label'=>'Ulica:']);
        $this->addElement('text','house_no',['label'=>'Numer budynku:']);
        $this->addElement('text','flat_no',['label'=>'Numer lokalu:']);
        
        
        $this->addElement('text','city',['label'=>'Miasto:']);
        $this->addElement('text','postcode',['label'=>'Kod pocztowy:']);
        $this->addElement('text','province',['label'=>'Województwo:']);
        
        $this->addElement('submit','submit',['label'=>'Zapisz']);
    }


}

