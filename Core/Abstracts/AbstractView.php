<?php

abstract class Core_Abstracts_AbstractView
{
	/**
	 * @return mixed
	 */
	protected function getControllerName()
	{
		$names = explode('_', get_class($this));
		$last = array_pop($names);

		// strip "Controller"
		$last = str_replace('Controller', '', $last);

		return $last;
	}

	/**
	 * @param string $viewName
	 * @param array $vars
	 * @param null $controllerName
	 * @return 
	 */
	protected function renderView($viewName = 'index', $vars = array(), $controllerName = NULL)
	{
		// view directories are based on their controller names
		if(is_null($controllerName))
		{
			$controllerName = $this->getControllerName();
		}

		$view = new Zend_View(array('basePath' => PATH_APP . '/View/'));

		// assign vars to view
		$view->assign($vars);

		// return rendered view
		return $view->render($controllerName . '/' . $viewName . 'View.php');
	}
}
