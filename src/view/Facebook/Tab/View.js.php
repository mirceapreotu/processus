<?php
/**
 * @var $this App_View_Facebook_Tab
 */
?>


$(document).ready(function()
{
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

	MeetIdaaa.console.log("MeetIdaaa=",MeetIdaaa);
	MeetIdaaa.console.log("href="+document.location.href);

	var env = MeetIdaaa.getEnv();

    var isViewerRegistered = (MeetIdaaa.model.isViewerRegistered()==true);
    MeetIdaaa.console.log(" isViewerRegistered="+isViewerRegistered);

    if (isViewerRegistered != true) {

        var viewerIsNotRegisteredMessage =
            "viewer is not registered. try reload page!";

        
        MeetIdaaa.console.log("NOTICE: "+viewerIsNotRegisteredMessage);
    }


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
                            if (response.result.appConfig) {
                                MeetIdaaa.model.appConfig = response.result.appConfig;

                                MeetIdaaa.console.log(" appConfig =",MeetIdaaa.model.getAppConfig());
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