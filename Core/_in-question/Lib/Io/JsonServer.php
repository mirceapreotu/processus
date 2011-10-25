<?php
/**
 * Lib_Io_JsonServer Class
 *
 * @package		Lib_Io
 * @category	meetidaaa.com
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 *
 */

/**
 * Lib_IO_JsonServer
 *
 * @package     Lib_Io
 * @category	meetidaaa.com
 * 
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */
class Lib_Io_JsonServer extends Zend_Json_Server
{

    /**
     * Set request object
     *
     * We split the class/methodname and use only the method name ...
     *
     * @param  Zend_Json_Server_Request $request
     * @return Zend_Json_Server
     */
    public function setRequest(Zend_Json_Server_Request $request)
    {

	    $method = $request->getMethod();
	    list($className, $methodName) = explode('.', $method);
	    unset($className); // ignore it ..
	    $request->setMethod($methodName);
        $this->_request = $request;
        return $this;
    }

    /**
     * Constructor
     *
     * Parse classname from method ..
     *
     * @param string $namespace Prefix, i.e. "App_JsonRpc_"
     *
     * @return void
     */
    public function __construct($namespace)
    {

		$postData = file_get_contents('php://input');
		$data = json_decode($postData, true);
		$classAndMethod = $data['method'];

		preg_match('/([a-z0-9_]+)\.([a-z0-9_]+)/i', $classAndMethod, $match);

		$className = $match[1];

	    parent::__construct();

	    $this->setClass($namespace.$className);

    }
}
