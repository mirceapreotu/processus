<?php
/**
 * Dispatcher Class
 *
 * @category	meetidaaa.com
 * @package		App
 * @copyright	Copyright (c) 2011 meetidaaa.com
 * @license		http://meetidaaa.com/license/default
 * @version		$Id$
 */
class App_Dispatcher
{
	/**
	 * Create controller and call the run() method
	 */
	public function dispatch()
	{

		$request = new Lib_Ctrl_Request();
		$response = new Zend_Controller_Response_Http();

		$channel = Zend_Wildfire_Channel_HttpHeaders::getInstance();
		$channel->setRequest($request);
		$channel->setResponse($response);

		ob_start();

		$controller = $request->getControllerName();
		$action		= $request->getActionName();

		// sanitize controller
		$controller = preg_replace('#[^a-z0-9-]#', '', strtolower($controller));

		if ($controller == '') {

			$controller = 'index';
		}

		$controllerClassName = "App_Ctrl_".ucfirst($controller);

		// sanitize action
		$action = preg_replace('#[^a-z0-9-]#', '', strtolower($action));

		if ($action == '') {

			$action = 'index';
		}

		$actionMethodName = $action.'Action';


		try {

			if (class_exists($controllerClassName)) {

				$controllerClass = new $controllerClassName();

				$controllerClass->setRequest($request);
				$controllerClass->setResponse($response);

				if (method_exists($controllerClass, $actionMethodName)) {

					$controllerClass->$actionMethodName();

				} else {

					// method not found, run the index Action
					$controllerClass->indexAction();
				}

			} else {

                // controller not found, use error controller:

                $controllerClass = new App_Ctrl_Error();
                $controllerClass->setRequest($request);
                $controllerClass->setResponse($response);
                $controllerClass->indexAction();

			}

		} catch (Exception $e) {

			//unset($e); // not needed

			die($e->getMessage());

			// use the index controller as fallback .. ?

		}

		$channel->flush();
		$response->sendHeaders();


	}
}
