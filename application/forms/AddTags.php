<?php

class Application_Form_AddTags extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        
        $tags=new Zend_Form_Element_Text('tags',['label'=>'Dodaj tagi:']);
        $tags->setAttribute("id", "tags_input");
        $this->addElement($tags);
    }


}

