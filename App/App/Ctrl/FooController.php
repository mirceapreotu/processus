<?php

Class App_Ctrl_FooController extends Core_GaintS_Core_AbstractController
{
  public function index()
  {
    // $model = new App_Manager_App_FooManager();

    $view = $this->getView('Foo');
    echo $view->render('foobar.php');
  }
}
