<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 8/22/11
 * Time: 6:42 PM
 * To change this template use File | Settings | File Templates.
 */

abstract class Core_GaintS_Core_AbstractClass
{
    /**
     * @var Lib_Log_CouchDBLogger
     */
    protected $_logger;

    /**
     * @return App_GaintS_Lib_Logger_CouchDBLogger
     */
    protected function getCouchDBLogger()
    {
        if (!$this->_logger) {
            /** @var $_logger App_GaintS_Lib_Logger_CouchDBLogger */
            $this->_logger = new Core_GaintS_Lib_Logger_CouchDBLogger();
        }

        return $this->_logger;
    }

    /**
     * @return App_Registry
     */
    protected function getGlobalRegistry()
    {
        return Bootstrap::getRegistry();
    }

    /**
     * @return ArrayObject|null
     */
    protected function getGlobalConfig()
    {
        return Bootstrap::getRegistry()->getConfig();
    }

    /**
     * @return ArrayObject|null
     */
    protected function getGaintSConfig()
    {
        return Bootstrap::getRegistry()->getGaintSConfig();
    }
}
