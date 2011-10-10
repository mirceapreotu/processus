<?php
/**
 * @var $this App_View_Facebook_Canvas
 */

?>






$(document).ready(function()
{

	MeetIdaaa.console.log("document.onReady()");


    MeetIdaaa.social.init(
        function(response) {
            MeetIdaaa.runApp();
        }
        ,this
    );


});




// ++++++++++++++++++ application view +++++++++++++++++++

MeetIdaaa.runApp=function()
{

	//MeetIdaaa.console.log(" MeetIdaaa.runApp() scope="+this,this);
	MeetIdaaa.console.log("MeetIdaaa=",MeetIdaaa);

	var env = MeetIdaaa.getEnv();
	//MeetIdaaa.console.log("env=",env);

	try {
		if (MeetIdaaa.getEnv().application.isVzApplication) {
			gadgets.window.adjustHeight(720);
		}
	} catch(e) {
		MeetIdaaa.console.log("NOTICE: ",e);
	}

	MeetIdaaa.swf.load();

    MeetIdaaa.callRPC("Canvas.getInitialData",[],
                    function(response) {
                        MeetIdaaa.console.log("resp="+response, response);
                        MeetIdaaa.console.log("scope="+this, this);

                        if (response.error) {
                            // do sth.
                            //throw new Exception("RPC ERROR");

                           
                            MeetIdaaa.console.log("canvas get initial data failed! response=",response);



                        } else {

                            if (response.result.viewer) {
                                MeetIdaaa.model.viewer = response.result.viewer;
                            }


                        }

                    },
                    this
            );





	<?php if(Bootstrap::getRegistry()->isDebugMode()):?>
		/*
		this.foo = "bar";

		MeetIdaaa.rpc.describeApi(
			["flash", null],
			function(response) {
					MeetIdaaa.console.log("MeetIdaaa.rpc.describeApi() response="+response, response);
					MeetIdaaa.console.log("scope="+this, this);

					if (response.error) {
						// do sth.
						//throw new Exception("RPC ERROR");
					}

			},
			this
		);

		*/


	<?php endif;?>

}