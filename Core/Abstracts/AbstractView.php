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
	 * @return
	 */
	protected function renderView($viewName = 'index', $vars = array())
	{
		// view directories are based on their controller names
		$controllerName = $this->getControllerName();

		$view = new Zend_View(array('basePath' => PATH_APP . '/View/' . $controllerName));
		$view->assign($vars);
		return $view->render($viewName . 'View.php');
	}
}
