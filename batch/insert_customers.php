<?php

//Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH',  realpath(dirname(__FILE__). '/../application'));

//define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV')?getenv('APPLICATION_ENV'):'development'));

//ensure library/is on include_path
set_include_path(implode(PATH_SEPARATOR, array(realpath(APPLICATION_PATH.'/../library').get_include_path(),)));

/**Zend_Application*/
require_once 'Zend/Application.php';

//create application, bootstrap, and run
$application=new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH. '/configs/application.ini'
        );
$application->bootstrap('db');

//insert records
$xml=simplexml_load_file('../data/customers.xml');

$Customers=new Application_Model_DbTable_Customers();
$Customers->delete('');

foreach($xml->customer as $customer){
    $data = (array)$customer;
    try{
        $Obj=$Customers->createRow($data)->save();
    } catch (Zend_Db_Statement_Exception $ex) {
        die($e->getMessage());
    }
}
/*
foreach($xml->customer as $customer) {
    $data = [
        'customers_id'=>$customer->customers_id,
        'firstname'=>$customer->firstname,
        'lastname'=>$customer->lastname,
        'email'=>$customer->email,
        'phone'=>$customer->phone
    ];
    $record=new Application_Model_DbTable_Customers();
    $record->insert($data);
}*/