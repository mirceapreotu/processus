<?php
/**
 * @var $this App_View_Facebook_Tab
 */
include $this->getController()->getSrcPath("view/Facebook/header.php");
?>
</head>

<body>
<div id="fb-root"></div>
<script language="JavaScript" type="text/javascript">

    // ++++++++++++++++++ application.js +++++++++++++++++++++++++++++++
    <?php include $this->getController()->getSrcPath("view/Facebook/meetidaaa.core.js.php"); ?>

    // ++++++++++++++++++ social.fb.js +++++++++++++++++++++++++++++++
    <?php include $this->getController()->getSrcPath("view/Facebook/meetidaaa.social.fb.js.php"); ?>

    // ++++++++++++++++++ view.js +++++++++++++++++++
    <?php include "View.js.php" ?>

</script>

<div id="viewport">

	<div id="header"></div>

</div>





<?php
include $this->getController()->getSrcPath("view/Facebook/footer.php");
?>

