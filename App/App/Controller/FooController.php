<?php

Class App_Controller_FooController extends Core_GaintS_Core_AbstractController
{
  public function index()
  {
    $view = $this->getView('Foo');

    $model = new App_Model_FooModel();
    $view->results = $model->foo();

    echo $view->render('foobar.php');
  }
}

?>
