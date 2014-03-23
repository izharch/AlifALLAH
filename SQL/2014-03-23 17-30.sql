CREATE TABLE `radio` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `source` text NOT NULL,
  `extension` varchar(10) NOT NULL,
  `status` enum('enabled','disabled') NOT NULL,
  `added_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8