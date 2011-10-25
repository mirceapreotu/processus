<?php

/**
 * Lib_JsonRpc_Response
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_JsonRpc
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */

/**
 * Lib_JsonRpc_Response
 *
 *
 *
 * @category	meetidaaa.com
 * @package		Lib_JsonRpc
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id:$
 */
class Lib_JsonRpc_Response
{

    protected $_version;
    protected $_id;

    protected $_error;
    protected $_result;

    /**
     * @return
     */
    public function getError()
    {
        return $this->_error;
    }

    /**
     * @param Exception $error
     * @return void
     */
    public function setError(Exception $error)
    {
        $this->_error = $error;
    }

    /**
     * @return
     */
    public function getResult()
    {
        return $this->_result;
    }

    /**
     * @param  $result
     * @return void
     */
    public function setResult($result)
    {
        $this->_result = $result;
    }


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
}

