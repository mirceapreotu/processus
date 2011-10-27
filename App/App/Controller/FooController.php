<?php

namespace App\Controller
{
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

        echo '<pre>';

        foreach($model->foo() as $obj)
        {
            print_r($obj);
            echo '<hr>';
        }

        echo '</pre>';
      }
    }
}

?>