<?php

/**
 * Lib_Io_JsonRpc_Request
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
 * Lib_Io_JsonRpc_Request
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

class Lib_Io_JsonRpc_Request
{
    
    protected $_id;
    protected $_version;
    protected $_params;
    protected $_method;

    protected $_dataProvider; // is the raw request object

    // ACCESSORS
    
    /**
     *
     * @return mixed 
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     *
     * @param mixed $id 
     */
    public function setId($id)
    {
        $this->_id = $id;
    }
    
    /**
     *
     * @return string|null 
     */
    public function getVersion()
    {
        return $this->_version;
    }
    /**
     *
     * @param string $version 
     */
    public function setVersion($version)
    {
        $this->_version = $version;
    }
    
    /**
     *
     * @return string 
     */
    public function getMethod()
    {
        if (is_string($this->_method)!==true) {
            $this->_method = "";
        }
        return $this->_method;
    }
    
    /**
     *
     * @param string $method 
     */
    public function setMethod($method)
    {
        $this->_method = $method;
    }
    
    /**
     *
     * @return array
     */
    public function getParams()
    {
        if (is_array($this->_params)!==true) {
            $this->_params = array();
        }
        return $this->_params;
    }
    
    public function setParams($params)
    {
        $this->_params = $params;
    }


    /**
     * @return array|mixed
     */
    public function getDataProvider()
    {
        return $this->_dataProvider;
    }

    /**
     * @param  array|mixed $data
     * @return void
     */
    public function setDataProvider($data)
    {
        $this->_dataProvider = $data;
    }
    
    // METHODS
    
    
}



