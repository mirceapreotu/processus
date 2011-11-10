<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>FooBar App Example</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Facebook prefered image source <link href="" rel="image_src"> -->

    <?php

      require('assets.php');

      $live = ! in_array($_SERVER['HTTP_HOST'], array('localhost', 'local.shakeonitapp.com')) ? TRUE : FALSE;

      if( ! $live) {
        foreach($assets['css'] as $file) {
          echo '<link href="'.$file.'" rel="stylesheet" text="text/css">';
        }
      }
      else {
          echo join('', file('assets/css/latest-css.bundle'));
      }

    ?>

  </head>

  <body>
    
    <div id="fb-root"></div>

    <div id="login-page" class="container">
      <div class="title row">
        <h1>FooBar App Example Login</h1>
      </div>

      <div class="row">
        <div><fb:login-button data-scope="user_about_me,email">Login with Facebook</fb:login-button></div>
      </div>
    </div>

    <div id="matrix">

      <div class="topbar">
        <div class="topbar-inner">
          <div class="container">
            <a href="#!/" class="brand">FooBar App</a>
            <ul class="nav">
              <li id="nav-home"><a href="#!/">Home</a></li>
            </ul>

            <ul class="nav secondary-nav">
              <li class="dropdown" id="member-container">
                <a href="#" class="dropdown-toggle"><img src="/assets/img/default_avatar.png"><span></span></a>
                <ul class="dropdown-menu">
                  <li><a href="#!/logout" class="logout">Logout</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </div>

      </div>

      <!-- ######## -->

      <div id="home" class="page container">

        <div class="title row">
          <h1>Home</h1>
        </div>

        <div class="row">
          <div class="left-column span5">
           LEFT COLUMN
          </div>
          <div class="right-column span11">
           RIGHT COLUMN
          </div>
        </div>

      </div>

    </div>

    <?php

      if( ! $live) {
        foreach($assets['js'] as $file) {
          echo '<script src="'.$file.'"></script>';
        }
      }
      else {
          echo join('', file('assets/js/latest-js.bundle'));
      }

    ?>

    <script type="text/javascript">$(function() { App.Start() })</script>

  </body>
</html>
