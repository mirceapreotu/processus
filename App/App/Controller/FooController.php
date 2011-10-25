<?php

Class App_Controller_FooController extends Core_Abstracts_AbstractView
{
  public function index()
  {
    $model = new App_Model_FooModel();
    echo $this->renderView('foobar', array('results' => $model->foo()));
  }
}

?>
