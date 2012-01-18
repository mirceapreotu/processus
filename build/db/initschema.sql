CREATE TABLE `fbusers` (
  `id` bigint(20) unsigned NOT NULL,
  `created` int(11) NOT NULL,
  KEY `by_fbuser` (`id`,`created`),
  KEY `by_created` (`created`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;