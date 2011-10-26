<?php

    namespace App\Controller;

    use Core\Abstracts\AbstractView;
    use App\Model\FooModel;

    /**
     *
     */
    Class FooController extends AbstractView
    {
      /**
       *
       */
      public function index()
      {
        $model = new FooModel();
        echo $this->renderView('foobar', array('results' => $model->foo()));
      }
    }

?>
