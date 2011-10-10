<?php
/**
 * @var $this App_View_Facebook_Tab
 */

?>






$(document).ready(function()
{
	// remove vz style sheet that has rendered to the iframe
	//$(document).find('style').remove();

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

	//alert("you must like that page!");
}