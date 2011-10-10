<?php
/**
 * @var $this App_View_Facebook_Tab
 */
include $this->getController()->getSrcPath("view/Facebook/header.php");
?>
</head>

<!--<img src="<?php echo($this->getController()->getUrl("assets/teaser/fanpage_authorize_app_and_go.jpg")); ?>">-->

<body>
<div id="fb-root"></div>
<?php
	if ($this->getController()->getDebug()->isDebugMode()) {
        echo '<div>
        <a href="javascript:void(0)" onclick="MeetIdaaa.login()" >login()</a>
         </div>';
    }
?>


<script language="JavaScript" type="text/javascript">

    // ++++++++++++++++++ application.js +++++++++++++++++++++++++++++++
    <?php include $this->getController()
            ->getSrcPath("view/Facebook/meetidaaa.core.js.php"); ?>

    // ++++++++++++++++++ social.fb.js +++++++++++++++++++++++++++++++
    <?php include $this->getController()
            ->getSrcPath("view/Facebook/meetidaaa.social.fb.js.php"); ?>

    // ++++++++++++++++++ view.js +++++++++++++++++++
    <?php include "View.AuthorizeAppAndGo.js.php" ?>

</script>
<?php
include $this->getController()->getSrcPath("view/Facebook/footer.php");
?>

