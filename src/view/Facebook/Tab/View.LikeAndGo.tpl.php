<?php
/**
 * @var $this App_View_Facebook_Tab
 */
include $this->getController()->getSrcPath("view/Facebook/header.php");
?>
</head>

<body style="margin:0; padding:0;">
<div id="fb-root"></div>
<!-- View.LikeAndGo -->

<script type="text/javascript">


    // ++++++++++++++++++ application.js +++++++++++++++++++++++++++++++
    <?php include $this->getController()
            ->getSrcPath("view/Facebook/meetidaaa.core.js.php"); ?>
    // ++++++++++++++++++ social.fb.js +++++++++++++++++++++++++++++++
    <?php include $this->getController()
            ->getSrcPath("view/Facebook/meetidaaa.social.fb.js.php"); ?>

    // ++++++++++++++++++ view.js +++++++++++++++++++

    <?php include "View.LikeAndGo.js.php" ?>

</script>



<div id="content">
    <div id="divTeaserLikeAndGo" >
        <img src="<?php echo($this->getController()->getUrl("assets/teaser/fanpage_like_and_go.jpg")); ?>">
    </div>
</div>


<?php
include $this->getController()->getSrcPath("view/Facebook/footer.php");
?>

