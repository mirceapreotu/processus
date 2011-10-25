<?php

/**
 * Lib_Io_JsonRpc_Logger
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Io_JsonRpc
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_Io_JsonRpc_Logger
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_Io_JsonRpc
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_Io_JsonRpc_Logger
{

    /**
     *
     * @var array
     */
    protected $_items;

    /**
     *
     * @return array
     */
    public function getItems()
    {
        if (is_array($this->_items) !== true) {
            $this->_items = array();
        }
        return $this->_items;
    }

    /**
     *
     * @param string $method
     * @param string $message
     * @param mixed|null $data
     */
    public function log($method, $message, $data = null)
    {
        if (is_array($this->_items) !== true) {
            $this->_items = array();
        }
        $this->_items[] = array(
            "method" => $method,
            "message" => $message,
            "data" => $data,
        );
    }


}

