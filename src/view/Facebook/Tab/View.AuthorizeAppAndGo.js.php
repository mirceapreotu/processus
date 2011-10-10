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
MeetIdaaa.runApp = function()
{
	//MeetIdaaa.console.log(" MeetIdaaa.runApp() scope="+this,this);
	MeetIdaaa.console.log("MeetIdaaa=",MeetIdaaa);

	//alert("you must authorize the app!");
}