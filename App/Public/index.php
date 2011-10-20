<?php

  define('PATH_ROOT', dirname(dirname(__FILE__)) . '/..');
  define('PATH_CORE', PATH_ROOT.'/Core');
  define('PATH_APP', PATH_ROOT.'/App/App');
  define('PATH_PUBLIC', PATH_ROOT.'/App/Public');

  require_once(PATH_CORE.'/Bootstrap.php');
  Bootstrap::init();

  $fooController = new App_Ctrl_FooController();
  $fooController->index();
