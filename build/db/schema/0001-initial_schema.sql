SET NAMES 'utf8';

CREATE TABLE SystemLog (
  id                INTEGER UNSIGNED NOT NULL auto_increment,
  priority          TINYINT UNSIGNED NOT NULL DEFAULT 0,
  facility          VARCHAR(32) DEFAULT 'default' NOT NULL,
  message           VARCHAR(255) DEFAULT '' NOT NULL,
  created           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modified          DATETIME NOT NULL,
  status            ENUM('ACTIVE','INACTIVE','DELETED') NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (id)
);



CREATE TABLE IF NOT EXISTS `changelog` (
  `change_number` bigint(20) NOT NULL,
  `delta_set` varchar(10) NOT NULL,
  `start_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `complete_dt` timestamp NULL DEFAULT NULL,
  `applied_by` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  PRIMARY KEY (`change_number`,`delta_set`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;






CREATE TABLE IF NOT EXISTS `AppConfig` (
  `key` varchar(255) NOT NULL,
  `value` longtext,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `FbPerson` (
  `externalKey` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `personId` bigint(20) NOT NULL,
  `accessToken` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`externalKey`),
  UNIQUE KEY `personId` (`personId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `Person` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `displayName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `imageUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profileUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `agb` tinyint(4) NOT NULL DEFAULT '0',
  `isDeleted` tinyint(4) NOT NULL DEFAULT '0',
  `isBlocked` tinyint(4) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;


CREATE TABLE IF NOT EXISTS `PersonExtended` (
  `personId` bigint(20) NOT NULL,
  `isAgeOver18` tinyint(4) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`personId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `PersonImageUpload` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `personId` bigint(20) NOT NULL,
  `folder` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `server` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;




--//@UNDO  
