<?php
/**
 * Lib_Ctrl_Request Class
 *
 * @category	meetidaaa.com
 * @package		Lib_Ctrl
 * 
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version    $Id$
 *
 */

/**
 * Lib_Ctrl_Request
 *
 * @category	meetidaaa.com
 * @package		Lib_Ctrl
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */
class Lib_Ctrl_Request extends Zend_Controller_Request_Http
{
	/**
	 * @var array
	 */
	protected $_pathListParams = array();

	/**
	 * @var array
	 */
	protected $_pathKeyParams = array();

    /**
     * Constructor
     *
     * If a $uri is passed, the object will attempt to populate itself using
     * that information.
     *
     * @param string|Zend_Uri $uri
     * @return void
     */
    public function __construct($uri = null)
    {
	    parent::__construct($uri);

		$pathItems = explode('/', $this->getPathInfo());

		switch (true) {

			case (count($pathItems) > 0):
		        array_shift($pathItems); // discard first slash

			case (count($pathItems) > 0):
		        $this->setControllerName(array_shift($pathItems));

			case (count($pathItems) > 0):
		        $this->setActionName(array_shift($pathItems));
		}

		$this->_pathListParams  = array();
		$this->_pathKeyParams   = array();
		$pathKey                = null;

        /** @noinspection PhpAssignmentInConditionInspection */
        while ($param = array_shift($pathItems)) {

	        // simple list
	        $this->_pathListParams[] = $param;

	        // key value
	        if ($pathKey !== null) {
		        $this->_pathKeyParams[$pathKey] = $param;
	            $pathKey = null;
	        } else {
		        $pathKey = $param;
	        }
        }
    }

	/**
	 * @return array
	 */
	public function getPathKeyParams()
	{
		return $this->_pathKeyParams;
	}

	/**
	 * @return array
	 */
	public function getPathListParams()
	{
		return $this->_pathListParams;
	}

	/**
	 * @param int|string $key int for list based access or key for strings
	 * @return mixed|boolean value or false if not found
	 */
	public function getPathParam($key)
	{
		if (is_int($key)) {

			if (isset($this->_pathListParams[$key])) {

				return $this->_pathListParams[$key];

			} else {

				return false;
			}
		} else {

			if (isset($this->_pathKeyParams[$key])) {

				return $this->_pathKeyParams[$key];

			} else {

				return false;
			}
		}
	}

}
