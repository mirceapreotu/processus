<?php
/**
 * @var $this App_View_Facebook_Canvas
 */
include $this->getController()->getSrcPath("view/Facebook/header.php");
?>
</head>

<body style="margin:0; padding:0;">
<div id="fb-root"></div>
<script type="text/javascript">


    // ++++++++++++++++++ application.js +++++++++++++++++++++++++++++++
    <?php include $this->getController()
            ->getSrcPath("view/Facebook/meetidaaa.core.js.php"); ?>
    // ++++++++++++++++++ social.fb.js +++++++++++++++++++++++++++++++
    <?php include $this->getController()
            ->getSrcPath("view/Facebook/meetidaaa.social.fb.js.php"); ?>

    // ++++++++++++++++++ view.js +++++++++++++++++++

    <?php include "View.js.php" ?>

</script>

<?php

if ($this->getController()->getDebug()->isDebugMode()) {
        echo '<div>
        <a href="javascript:void(0)" onclick="MeetIdaaa.login()" >login()</a>
            </div>';
    }

?>

<div id="content"></div>


<?php
include $this->getController()->getSrcPath("view/Facebook/footer.php");
?>

