<?php

class Application_Model_DbTable_Customers extends Zend_Db_Table_Abstract
{
    protected $_name = 'customers';
   // protected $_rowClass='Application_Model_DbTable_Customers_Row';
    protected $_dependentTables=['Application_Model_DbTable_CustomersHasTags'];
    
}

