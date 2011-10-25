<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 7/8/11
 * Time: 11:25 AM
 * To change this template use File | Settings | File Templates.
 */

abstract class Core_Abstracts_AbstractJsonRPCService extends Core_Abstracts_AbstractClass
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
