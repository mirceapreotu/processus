<?php

/**
 * @var $this App_View_Facebook_Abstract
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraphprotocol.org/schema/">
	<head>
	<!-- BEGIN page.html.head.php -->

        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-script-type" content="text/javascript" />
		<meta http-equiv="content-style-type" content="text/css" />
		<meta http-equiv="content-language" content="DE" />

		<meta name="keywords" content="<?php echo($this->getController()->getFacebook()->getConfig()->getOgSiteKeywords()); ?>" />
		<meta name="publisher" content="<?php echo($this->getController()->getFacebook()->getConfig()->getOgSitePublisher()); ?>" />
		<meta name="author" content="<?php echo($this->getController()->getFacebook()->getConfig()->getOgSiteAuthor()); ?>" />
		<meta name="robots" content="index, follow" />

		<meta name="title" content="<?php echo($this->getController()->getFacebook()->getConfig()->getOgSiteTitle()); ?>" />
		<meta name="description" content="<?php echo($this->getController()->getFacebook()->getConfig()->getOgSiteDescription()); ?>" />

		<title><?php echo($this->getController()->getFacebook()->getConfig()->getOgSiteTitle()); ?></title>

        <meta property="og:title" content="<?php echo($this->getController()->getFacebook()->getConfig()->getOgSiteTitle()); ?>"/>
		<meta property="og:description" content="<?php echo($this->getController()->getFacebook()->getConfig()->getOgSiteDescription()); ?>"/>
		<meta property="og:image" content="<?php echo($this->getController()->getFacebook()->getConfig()->getOgSiteImage()); ?>"/>
		<meta property="og:type" content="<?php echo($this->getController()->getFacebook()->getConfig()->getOgSiteType()); ?>"/>
		<meta property="og:url" content="<?php echo($this->getController()->getFacebook()->getConfig()->getOgSiteUrl()); ?>"/>
		<meta property="og:site_name" content="<?php echo($this->getController()->getFacebook()->getConfig()->getOgSiteTitle()); ?>"/>
		<meta property="fb:app_id" content="<?php echo($this->getController()->getFacebook()->getConfig()->getAppId()); ?>"/>

		<link rel="shortcut icon" href="<?php echo($this->getController()->getUrl("fb/og/assets/favicon.ico")); ?>" type="image/x-icon" />

        <!-- +++++++++++++++++++++++ css +++++++++++++++++++ -->

		<link rel="stylesheet" href="<?= $this->getController()->getUrl("fb/css/reset.css") ?>" />
		<link rel="stylesheet" href="<?= $this->getController()->getUrl("fb/css/clearfix.css") ?>" />

		<!-- libs: external -->

		<script type="text/javascript" src="<?php echo($this->getController()->getUrlGlobal("js/jquery-1.6.2.min.js")); ?>"></script>
		<script type="text/javascript" src="<?php echo($this->getController()->getUrlGlobal("js/swfobject.js")); ?>"></script>
		<script type="text/javascript" src="<?php echo($this->getController()->getUrlGlobal("js/JSON2.js")); ?>"></script>
		<script type="text/javascript" src="<?php echo($this->getController()->getUrlGlobal("js/jquery.tools.full.min.js")); ?>"></script>
		<script type="text/javascript" src="<?php echo($this->getController()->getUrlGlobal("js/jquery.formtips.js")); ?>"></script>

		<title><?php echo($this->getController()->getFacebook()->getConfig()->getOgSiteTitle()); ?></title>