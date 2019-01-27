CREATE TABLE `logins` (
  `user_id` varchar(32) NOT NULL,
  `username` varchar(128) NOT NULL DEFAULT '',
  `password_hash` varchar(64) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `modules` (
  `module_id` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(128) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `plugin` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `spots` (
  `spot_id` char(32) NOT NULL DEFAULT '',
  `name` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`spot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `identities` (
  `identity_id` char(32) NOT NULL DEFAULT '',
  `name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`identity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
