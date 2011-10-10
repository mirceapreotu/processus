<?php
/*
ini_set("display_errors",1);
include_once dirname(__FILE__)."/../../../src/Bootstrap.php";
Bootstrap::init();
*/
/**
 * @var $this App_View_Facebook_Abstract
 */
?>

// ++++++++++++++++++++ MeetIdaaa.social +++++++++++++++++

MeetIdaaa.social = {


    init: function(responseHandler, scope)
    {
         MeetIdaaa.console.log("MeetIdaaa.social.initFacebook()...");

        // fbAsyncInit
        (function() {
            var e = document.createElement('script');
            e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
            e.async = true;
            document.getElementById('fb-root').appendChild(e);
        }());


         window.fbAsyncInit = function() {

            MeetIdaaa.console.log("fbAsyncInit()... FB=",FB);

         	if (MeetIdaaa.fbAsyncInitialized == true)
			{
                if (responseHandler)
				{
                    responseHandler.apply(scope, []);
                    return;
                } else {
                    MeetIdaaa.console.log("NOTICE: MeetIdaaa.social.initFacebook() no responseHandler defined.");
                    return;
                }
            } else {
              MeetIdaaa.fbAsyncInitialized = true;
            }

            FB.init({
            	appId   : '<?php echo $this->getController()->getFacebook()->getConfig()->getAppId(); ?>',
             	//session : null, //<?php //echo json_encode($this->getController()->getFacebook()->getSession()); ?>, // don't refetch the session when PHP already has it
            	status  : true, // check login status
				cookie  : true, // enable cookies to allow the server to access the session
            	xfbml   : true // parse XFBML
            });

            FB.XFBML.parse();
            FB.Canvas.setAutoResize(true);

			/*
            // whenever the user logs in, we refresh the page
            FB.Event.subscribe('auth.login', function() {

                MeetIdaaa.console.log("auth.login event args=",arguments);
                //MeetIdaaa.social.login();
            });
			*/

            if (responseHandler) {
                    responseHandler.apply(scope, []);
            } else {
                MeetIdaaa.console.log("NOTICE: MeetIdaaa.social.initFacebook() no responseHandler defined.");
            }
         }
    },
    login : function()
    {
        MeetIdaaa.console.log("LOGIN");
       //alert("Login ...");

        var env = MeetIdaaa.getEnv();
        var loginUrl = env.social.login.loginUrl;

        MeetIdaaa.console.log("MeetIdaaa.login() url="+loginUrl);

        try {
            window.top.location = loginUrl;
            return;
        } catch(e) {
        }

        try {
            window.parent.location = loginUrl;
            return;
        } catch(e) {
        }

        try {
            window.location = loginUrl;
            return;
        } catch(e) {
        }

       <?php
       //$loginUrl = $this->getController()->getFacebookLoginUrl();
       //echo 'window.parent.location="'.html_entity_decode($loginUrl).'";';
       ?>
    },


    resizeView : function(w,h)
    {
        MeetIdaaa.console.log("MeetIdaaa.social.resizeView(args) args=",arguments);
        var obj = {
            width: w,
            height: h
        };
        try {
            if (arguments.length==0) {
                FB.Canvas.setSize(); // dont know if this forces an autoSize
            } else {
                FB.Canvas.setSize(obj);
            }
        } catch(e) {
            MeetIdaaa.console.log("NOTICE resizeView(): e=",e);
        }

    }



}




