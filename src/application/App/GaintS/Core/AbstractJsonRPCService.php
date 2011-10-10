<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 7/8/11
 * Time: 11:25 AM
 * To change this template use File | Settings | File Templates.
 */

abstract class App_GaintS_Core_AbstractJsonRPCService extends App_GaintS_Core_AbstractClass
{
    /**
     */
    protected $_manager;

    /**
     */
    protected function getManager()
    {
        return $this->_manager;
    }
}