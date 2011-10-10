<?php

// IE-FIX: allow crossdomain cookies / iFrames //
// header('p3p: CP="ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV"');
// header("P3P: CP=IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA");
// see: http://anantgarg.com/2010/02/18/cross-domain-cookies-in-safari/

//die("DIED");
ini_set('display_errors', true); error_reporting(E_ALL | E_STRICT); //var_dump(__FILE__);

include_once dirname(__FILE__)."/../../../src/Bootstrap.php";
Bootstrap::init();

$redirectToFanpage = true;



function redirectToFanpageTabUrl()
{
    $ctrl = new App_Ctrl_Facebook_Canvas();

    $redirectUrl = $ctrl->getApplication()
            ->getFacebook()
            ->getConfig()
            ->getFanPageTabUrl();

     echo("<script>
                    try {
                        top.location.href='" . $redirectUrl . "';
                    } catch(e) {
                        //throw e;
                    }
                    try {
                        parent.location.href='" . $redirectUrl . "';
                    } catch(e) {
                        //throw e;
                    }
                    try {
                        document.location.href='" . $redirectUrl . "';
                    } catch(e) {
                        //throw e;
                    }
                    </script>");

    exit;
}


// ++++++++++++++ here we go ++++++++++++++++++++++

if ($redirectToFanpage===true) {
    redirectToFanpageTabUrl();
}


$ctrl = new App_Ctrl_Facebook_Canvas();
$ctrl->run();
