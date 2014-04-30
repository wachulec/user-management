<?php

class Application_Model_DbTable_Tags extends Zend_Db_Table_Abstract
{

    protected $_name = 'tags';
   // protected $_rowClass='Application_Model_DbTable_Tags_Row';
    protected $_dependentTables=['Application_Model_DbTable_CustomersHasTags'];

}

