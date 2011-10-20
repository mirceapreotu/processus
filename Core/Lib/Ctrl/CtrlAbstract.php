<?php
/**
 * Lib_Ctrl_CtrlAbstract Class
 *
 * @category	meetidaaa.com
 * @package		Lib_Ctrl
 * 
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */

/**
 * Lib_Ctrl_CtrlAbstract
 *
 * @category	meetidaaa.com
 * @package		Lib_Ctrl
 *
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version     $Id$
 *
 */
abstract class Lib_Ctrl_CtrlAbstract
{
	/**
	 * @var Lib_Ctrl_Request
	 */
	protected $_request;

	/**
	 * @var Zend_Controller_Response_Http
	 */
	protected $_response;

	/**
	 * @var array
	 */
	protected $_params = array();
	
	/**
	 * Default Action - MUST BE IMPLEMENTED
	 */
	abstract public function indexAction();
	
	/**
	 * Sets a request
	 * 
	 * @see App_Dispatcher
	 * @param Lib_Ctrl_Request $request
	 */
	public function setRequest(&$request)
	{
		$this->_request = $request;
	}

	/**
	 * @return Lib_Ctrl_Request
	 */
	public function getRequest()
	{
		return $this->_request;
	}

	/**
	 * @return Lib_Ctrl_Request
	 */
	public function getName()
	{
		
		$classPathParts = explode('_', get_class($this));
		$ctrlName = array_pop($classPathParts);
		
		return strtolower($ctrlName);
	}


	/**
	 * Sets a response
	 * 
	 * @see		App_Dispatcher
	 * @param	Zend_Controller_Response_Http $response
	 */
	public function setResponse(&$response)
	{
		$this->_response = $response;
	}

    /**
     * perform any further init steps .. auth?
     * @return void
     */
    public function init()
    {
    }

	/**
	 * redirect
	 * 
	 * @param	string $target
	 */
	public function redirect($target)
	{
		header('Location: '.$target);
		exit;
	}
	
	/**
	 * Sets additional parameters (from the url)
	 * 
	 * @see App_Dispatcher
	 * 
	 * @param array $params
	 */
	public function setParams($params)
	{
		if (is_array($params)) {
			$this->_params = $params;
		}
	}
	
	/**
	 * Constructs & returns a relative URL to a controller/action
	 * 
	 * @param string $ctrl
	 * @param string $action
	 * @param array $params
	 * 
	 * @return string An URI
	 */
	public function getLink($ctrl='', $action='', $params=array())
	{

		if (!is_array($params)) {
			$params = array($params);
		}

		if ($ctrl != '') {
			$ctrl .= '/';
		}

		if ($action != '') {
			
			$action .= '/';
		}
		
		$param = '';
		if (count($params) > 0) {
			$param = join('/', $params).'/';
		}
		
		$url =
			$this->_request->getBaseUrl().'/'.
			strtolower($ctrl).
			strtolower($action).
			$param;

	    return $url;
	}
	
	/**
	 * Constructs & returns an absolute URL to a controller/action
	 * 
	 * @param string $ctrl
	 * @param string $action
	 * @param array $params
	 * 
	 * @return string An URI
	 */
	public function getLinkAbsolute($ctrl='', $action='', $params=array())
	{
		
		$link = $this->getLink($ctrl, $action, $params);
		
		return 
			$this->_request->getScheme().'://'.
			$this->_request->getHttpHost().$link;
	}
	
}
