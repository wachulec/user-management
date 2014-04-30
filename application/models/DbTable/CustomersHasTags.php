<?php

class Application_Model_DbTable_CustomersHasTags extends Zend_Db_Table_Abstract
{

    protected $_name = 'customers_has_tags';
    protected $_referenceMap=[
      'Tags'=>[
          'columns'=>['tag_id'],
          'refTableClass'=>'Application_Model_DbTable_Tags',
          'refTableColumns'=>['tags_id']
      ],
      'Customers'=>[
          'columns'=>['customer_id'],
          'refTableClass'=>'Application_Model_DbTable_Customers',
          'refTableColumns'=>['customers_id']
      ]
    ];

}

