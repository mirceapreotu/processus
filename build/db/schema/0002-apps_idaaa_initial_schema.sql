SET NAMES 'utf8';




# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.1.57-log)
# Database: apps_idaaa
# Generation Time: 2011-09-12 13:45:23 +0000
# ************************************************************


# Dump of table base_cities
# ------------------------------------------------------------

DROP TABLE IF EXISTS `base_cities`;

CREATE TABLE `base_cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) DEFAULT NULL,
  `currency` char(3) DEFAULT NULL,
  `ra_id` int(11) DEFAULT NULL,
  `live` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `listing` (`id`,`name`,`live`,`ra_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table base_genres
# ------------------------------------------------------------

DROP TABLE IF EXISTS `base_genres`;

CREATE TABLE `base_genres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `description` text,
  `url_source` varchar(255) DEFAULT NULL,
  `ra_id` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `listing` (`id`,`name`,`ra_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table base_user_groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `base_user_groups`;

CREATE TABLE `base_user_groups` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `level` tinyint(1) NOT NULL,
  `type` char(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table base_user_metas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `base_user_metas`;

CREATE TABLE `base_user_metas` (
  `user_id` mediumint(8) unsigned NOT NULL,
  `language` char(2) DEFAULT NULL,
  `username` varchar(20) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `skype` varchar(20) DEFAULT NULL,
  KEY `user_id` (`user_id`),
  KEY `listing` (`user_id`,`firstname`,`lastname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table base_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `base_users`;

CREATE TABLE `base_users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` smallint(5) unsigned NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(40) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `setpassword` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_01` (`email`,`id`,`group_id`,`password`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table dj_event_relations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dj_event_relations`;

CREATE TABLE `dj_event_relations` (
  `dj_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  KEY `by_event` (`event_id`,`dj_id`),
  KEY `by_dj` (`dj_id`,`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table dj_genre_relations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dj_genre_relations`;

CREATE TABLE `dj_genre_relations` (
  `dj_id` int(11) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL,
  KEY `by_dj` (`dj_id`,`genre_id`),
  KEY `by_genre` (`genre_id`,`dj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table dj_label_relations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `dj_label_relations`;

CREATE TABLE `dj_label_relations` (
  `dj_id` int(11) DEFAULT NULL,
  `label_id` int(11) DEFAULT NULL,
  KEY `by_dj` (`dj_id`,`label_id`),
  KEY `by_label` (`label_id`,`dj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table djs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `djs`;

CREATE TABLE `djs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `urlname` varchar(50) DEFAULT NULL,
  `real_name` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `bio` text,
  `url_bio_source` varchar(255) DEFAULT NULL,
  `url_soundcloud` varchar(255) DEFAULT NULL,
  `url_facebook` varchar(255) DEFAULT NULL,
  `url_myspace` varchar(255) DEFAULT NULL,
  `data_source` char(2) DEFAULT NULL,
  `status` enum('review','missing','complete') DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dedupe` (`name`,`id`),
  KEY `update` (`urlname`,`id`),
  KEY `update_name` (`name`,`id`),
  KEY `listing` (`name`,`id`,`status`),
  FULLTEXT KEY `ftname` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table events
# ------------------------------------------------------------

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ra_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `urlname` varchar(200) DEFAULT NULL,
  `description` text,
  `ra_djs` text,
  `ra_date` date DEFAULT NULL,
  `ra_time` varchar(20) DEFAULT NULL,
  `datetime_start` datetime DEFAULT NULL,
  `datetime_end` datetime DEFAULT NULL,
  `ra_cost` varchar(100) DEFAULT NULL,
  `has_costs` enum('yes','no','maybe','soldout') DEFAULT NULL,
  `cost_door` float(9,2) DEFAULT '0.00',
  `cost_presale` float(9,2) DEFAULT '0.00',
  `cost_students` float(9,2) DEFAULT '0.00',
  `status` enum('review','missing','complete') DEFAULT NULL,
  `created` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `listing` (`city_id`,`id`,`name`,`status`),
  KEY `update` (`ra_id`,`id`),
  KEY `fe_all_rating` (`venue_id`,`status`,`id`),
  KEY `urlname` (`urlname`),
  FULLTEXT KEY `ft_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table labels
# ------------------------------------------------------------

DROP TABLE IF EXISTS `labels`;

CREATE TABLE `labels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `ra_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `update` (`ra_id`,`id`),
  KEY `listing` (`id`,`name`),
  FULLTEXT KEY `ftname` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table member_blocks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_blocks`;

CREATE TABLE `member_blocks` (
  `member_id` bigint(20) NOT NULL DEFAULT '0',
  `section` enum('genres','people') NOT NULL DEFAULT 'genres',
  `section_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`section_id`,`section`,`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table member_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_comments`;

CREATE TABLE `member_comments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `section` enum('people','djs','venues','events') NOT NULL,
  `section_id` bigint(20) DEFAULT NULL,
  `member_id` bigint(20) NOT NULL,
  `comment` text,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entries` (`section`,`section_id`,`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table member_dj_ratings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_dj_ratings`;

CREATE TABLE `member_dj_ratings` (
  `member_id` bigint(20) NOT NULL,
  `event_id` int(11) NOT NULL,
  `dj_id` int(11) NOT NULL,
  `rating` tinyint(2) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`member_id`,`event_id`,`dj_id`,`rating`,`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table member_dj_relations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_dj_relations`;

CREATE TABLE `member_dj_relations` (
  `dj_id` int(11) DEFAULT NULL,
  `member_id` bigint(20) DEFAULT NULL,
  KEY `by_dj` (`dj_id`,`member_id`),
  KEY `by_genre` (`member_id`,`dj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table member_event_ratings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_event_ratings`;

CREATE TABLE `member_event_ratings` (
  `member_id` bigint(20) NOT NULL,
  `event_id` int(11) NOT NULL,
  `rating` tinyint(2) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`member_id`,`event_id`,`rating`,`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table member_event_relations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_event_relations`;

CREATE TABLE `member_event_relations` (
  `event_id` int(11) DEFAULT NULL,
  `member_id` bigint(20) DEFAULT NULL,
  KEY `by_dj` (`event_id`,`member_id`),
  KEY `by_genre` (`member_id`,`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table member_genre_relations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_genre_relations`;

CREATE TABLE `member_genre_relations` (
  `genre_id` int(11) DEFAULT NULL,
  `member_id` bigint(20) DEFAULT NULL,
  KEY `by_dj` (`genre_id`,`member_id`),
  KEY `by_genre` (`member_id`,`genre_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table member_logins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_logins`;

CREATE TABLE `member_logins` (
  `member_id` bigint(20) NOT NULL DEFAULT '0',
  `access_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `member_id` (`member_id`,`access_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table member_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_logs`;

CREATE TABLE `member_logs` (
  `member_id` bigint(20) NOT NULL DEFAULT '0',
  `action_type` char(20) NOT NULL DEFAULT '',
  `for_object_id` bigint(20) NOT NULL DEFAULT '0',
  `item_id` bigint(20) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`member_id`,`action_type`,`for_object_id`,`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table member_member_relations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_member_relations`;

CREATE TABLE `member_member_relations` (
  `follow_member_id` bigint(20) DEFAULT NULL,
  `member_id` bigint(20) DEFAULT NULL,
  KEY `by_follow` (`follow_member_id`,`member_id`),
  KEY `by_member` (`member_id`,`follow_member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table member_venue_relations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `member_venue_relations`;

CREATE TABLE `member_venue_relations` (
  `venue_id` int(11) DEFAULT NULL,
  `member_id` bigint(20) DEFAULT NULL,
  KEY `by_dj` (`venue_id`,`member_id`),
  KEY `by_genre` (`member_id`,`venue_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table members
# ------------------------------------------------------------

DROP TABLE IF EXISTS `members`;

CREATE TABLE `members` (
  `id` bigint(20) NOT NULL DEFAULT '0',
  `city_id` int(11) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `gender` enum('m','f') DEFAULT NULL,
  `language` char(2) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `autocomplete` (`fullname`,`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table venues
# ------------------------------------------------------------

DROP TABLE IF EXISTS `venues`;

CREATE TABLE `venues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ra_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `urlname` varchar(100) DEFAULT NULL,
  `address` text,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `url_website` varchar(255) DEFAULT NULL,
  `status` enum('review','missing','complete') DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `listing` (`city_id`,`id`,`name`),
  KEY `dedupe` (`name`,`city_id`,`id`),
  FULLTEXT KEY `autocomplete` (`name`),
  FULLTEXT KEY `ft_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;







--//@UNDO  
