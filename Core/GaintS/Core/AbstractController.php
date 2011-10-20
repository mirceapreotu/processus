<?php

abstract class Core_GaintS_Core_AbstractController
{

  /**
   * @return Zend_View_Instance
   */
  protected function getView($model = NULL)
  {
    $basePath = str_replace('/GaintS/C', '', PATH_APP.'/View/'.$model);
    return new Zend_View(array('basePath' => $basePath));
  }

}
