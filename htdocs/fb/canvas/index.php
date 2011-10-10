<?php

// IE-FIX: allow crossdomain cookies / iFrames //
// header('p3p: CP="ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV"');
// header("P3P: CP=IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA");
// see: http://anantgarg.com/2010/02/18/cross-domain-cookies-in-safari/

//die("DIED");
ini_set('display_errors', true); error_reporting(E_ALL | E_STRICT); //var_dump(__FILE__);

include_once dirname(__FILE__)."/../../../src/Bootstrap.php";
Bootstrap::init();

// Fucking facebbook:
// wenn man fanpage tabs deeplinken und authorisieren will,
// muss man über die canvas url gehen, weil man nur an diese url parameter
// ranhängen darf
// DIESE IDIOTEN !!!


$dispatcher = new App_Facebook_Dispatcher_Canvas();
$dispatcher->run();









