CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created` int(11) NOT NULL,
  `fb_id` bigint(20) DEFAULT NULL,
  `twitter_id` bigint(20) DEFAULT NULL,
  `last_login` int(11) DEFAULT NULL,
  `use_mobile` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fb_id` (`fb_id`),
  UNIQUE KEY `twitter_id` (`twitter_id`),
  KEY `by_user` (`id`,`created`),
  KEY `by_created` (`created`,`id`),
  KEY `users_query` (`id`,`created`,`fb_id`,`twitter_id`,`last_login`,`use_mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

CREATE TABLE `log_memcached` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mem_key` varchar(255) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `result_code` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

CREATE TABLE `log_json_rpc` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `method` varchar(255) DEFAULT NULL,
  `request` text,
  `meta_data` text,
  `duration` varchar(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;