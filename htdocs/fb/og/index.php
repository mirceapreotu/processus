<?php

include dirname(__FILE__) . "/../../../src/Bootstrap.php";
Bootstrap::init();

// +++++++++++++++ a simple ugly page controller +++++++++++++++++++++

class FbOgSimplePageController {


    public $redirectToFanpage = true;





    /**
     * @var FbOgSimplePageController
     */
    private static $_instance;

    /**
     * @static
     * @return FbOgSimplePageController
     */
    public static function getInstance() {
        if ((self::$_instance instanceof self)!==true) {
            $instance = new self();
            self::$_instance = $instance;
        }
        return self::$_instance;
    }

    /**
     * @return App_Facebook_Application
     */
    public function getApplication() {
        return App_Facebook_Application::getInstance();
    }


    public function run() {

    }


    /**
     * @return void
     */
    public function redirectToFanpageTabUrl()
    {
       

        $redirectUrl = $this->getApplication()
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

        //exit;
    }



}










// ++++++++++++++ here we go ++++++++++++++++++++++
$controller = FbOgSimplePageController::getInstance();
$application = $controller->getApplication();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/">
	<head>
	<!-- BEGIN fb.og.index -->

        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-script-type" content="text/javascript" />
		<meta http-equiv="content-style-type" content="text/css" />
		<meta http-equiv="content-language" content="DE" />


		<title>I like CITROËN DS5!<?php /*echo($application->getFacebook()->getConfig()->getOgSiteTitle());*/ ?></title>
		<link rel="shortcut icon" href="<?php echo($application->getFacebook()->getConfig()->getOgSiteFavicon()); ?>" type="image/x-icon" />

</head>
<body style="overflow: hidden;">


<div style="position: absolute; left:-1000px;">
	<img src="http://ds5-entdecker.de/assets/siteimage.jpg" width="75" heigth="75" alt="" />
</div>
<p style="color:#ffffff;">Ich habe soeben meine Stimme beim "CITROËN DS5 Entdecker"-Voting abgegeben.</p>

<?php

if ($controller->redirectToFanpage===true) {
    $controller->redirectToFanpageTabUrl();
}

?>
</body>
</html>