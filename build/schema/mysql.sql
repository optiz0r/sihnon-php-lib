--
-- Table structure for table `settings`
--
DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `type` enum('bool','int','float','string','array(string)','hash') DEFAULT 'string',
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--
INSERT INTO `settings` (`name`, `value`, `type`) VALUES
('debug.display_exceptions', '0', 'bool'),
('cache.base_dir', '/dev/shm/sihnon-php-lib/', 'string'),
('logging.plugins', 'Database', 'array(string)'),
('logging.Database', 'default', 'array(string)'),
('logging.Database.default.table', 'log', 'string'),
('logging.Database.default.severity', 'debug\ninfo\nwarning\ndebug', 'array(string)'),
('logging.Database.default.category', 'default', 'array(string)'),
('templates.tmp_path', '/var/tmp/sihnon-php-lib/', 'string'),
('auth', 'Database', 'string'),
('sessions', 0, 'bool'),
('sessions.path', '/', 'string');

--
-- Table structure for table `log`
--
DROP TABLE IF EXISTS `log`;
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `level` varchar(32) NOT NULL,
  `category` varchar(32) NOT NULL,
  `ctime` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `hostname` varchar(32) NOT NULL,
  `progname` varchar(64) NOT NULL,
  `file` text NOT NULL,
  `line` int(11) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Table structure for table `user`
--
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` char(40) NOT NULL,
  `fullname` varchar(255) NULL,
  `email` varchar(255) NULL,
  `last_login` int(10) NULL,
  `last_password_change` int(10) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Dumping data for table `user`
--
INSERT INTO `user` (`id`, `username`, `password`, `fullname`, `email`, `last_login`, `last_password_change`) VALUES
(1, 'admin', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Administrator', NULL, NULL, 1324211456);

--
-- Table structure for table `group`
--
DROP TABLE IF EXISTS `group`;
CREATE TABLE IF NOT EXISTS `group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Dumping data for table `group`
--
INSERT INTO `group` (`id`, `name`, `description`) VALUES
(1, 'admins', 'Administrative users will full control.');

--
-- Table structure for table `usergroup`
--
DROP TABLE IF EXISTS `usergroup`;
CREATE TABLE IF NOT EXISTS `usergroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL,
  `group` int(10) unsigned NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`,`group`),
  KEY `group` (`group`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Dumping data for table `usergroup`
--
INSERT INTO `usergroup` (`id`, `user`, `group`, `added`) VALUES
(1, 1, 1, 1324211572);

--
-- Table structure for view `groups_by_user`
--
DROP VIEW IF EXISTS `groups_by_user`;
CREATE VIEW `groups_by_user` AS (
  SELECT 
    `u`.`id` AS `user`,
    `g`.*
  FROM
    `usergroup` as `ug`
    LEFT JOIN `user` AS `u` ON `ug`.`user`=`u`.`id`
    LEFT JOIN `group` AS `g` ON `ug`.`group`=`g`.`id`
);

--
-- Table structure for table `permission`
--
DROP TABLE IF EXISTS `permission`;
CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Dumping data for table `permission`
--
INSERT INTO `permission` (`id`, `name`, `description`) VALUES
(1, 'Administrator', 'Full administrative rights.');


--
-- Table structure for table `grouppermission`
--
DROP TABLE IF EXISTS `grouppermission`;
CREATE TABLE IF NOT EXISTS `grouppermission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group` int(10) unsigned NOT NULL,
  `permission` int(10) unsigned NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `group` (`group`,`permission`),
  KEY `permission` (`permission`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

--
-- Dumping data for table `grouppermission`
--
INSERT INTO `grouppermission` (`id`, `group`, `permission`, `added`) VALUES
(1, 1, 1, 1324211935);

--
-- Table structure for view `permissions_by_group`
--
DROP VIEW IF EXISTS `permissions_by_group`;
CREATE VIEW `permissions_by_group` AS (
  SELECT 
    `g`.`id` AS `group`,
    `p`.*
  FROM
    `grouppermission` as `gp`
    LEFT JOIN `group` AS `g` ON `gp`.`group`=`g`.`id`
    LEFT JOIN `permission` AS `p` on `gp`.`permission`=`p`.`id`
);

--
-- Table structure for view `permissions_by_user`
--
DROP VIEW IF EXISTS `permissions_by_user`;
CREATE VIEW `permissions_by_user` AS (
  SELECT 
    `u`.`id` AS `user`,
    `p`.*
  FROM
    `usergroup` as `ug`
    LEFT JOIN `user` AS `u` ON `ug`.`user`=`u`.`id`
    LEFT JOIN `permissions_by_group` AS `p` on `ug`.`group`=`p`.`group`
);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `grouppermission`
--
ALTER TABLE `grouppermission`
  ADD CONSTRAINT `grouppermission_ibfk_2` FOREIGN KEY (`permission`) REFERENCES `permission` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grouppermission_ibfk_1` FOREIGN KEY (`group`) REFERENCES `group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usergroup`
--
ALTER TABLE `usergroup`
  ADD CONSTRAINT `usergroup_ibfk_2` FOREIGN KEY (`group`) REFERENCES `group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usergroup_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;



