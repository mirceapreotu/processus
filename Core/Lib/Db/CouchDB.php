<?php
/**
 * Created by IntelliJ IDEA.
 * User: francis
 * Date: 9/25/11
 * Time: 3:45 AM
 * To change this template use File | Settings | File Templates.
 */
 
class App_Lib_CouchDB extends App_GaintS_Core_AbstractClass
{

    private $_couchClient;

    private $_isInitialized = false;

    public function init()
    {
        $this->_isInitialized = true;
    }

    public function setDNS()
    {

    }

    public function setDBName()
    {

    }

}
