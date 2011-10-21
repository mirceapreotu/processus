<?php

abstract class Core_GaintS_Core_AbstractController
{
  /**
   * @return Zend_View_Instance
   */
  protected function getView($modelName = NULL)
  {
    return new Zend_View(array('basePath' => PATH_APP . '/View/' . $modelName));
  }
}
