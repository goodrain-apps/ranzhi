ALTER TABLE `crm_contact`
add `assignedTo` char(30) NOT NULL AFTER `nextDate`,
add `ignoredBy` char(30) NOT NULL AFTER `assignedTo`;

ALTER TABLE `crm_contact` change `originID` `originAccount` varchar(255) NOT NULL;

-- DROP TABLE IF EXISTS `oa_attendstat`;
CREATE TABLE `oa_attendstat` (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `account` char(30) NOT NULL,
  `month` char(10) NOT NULL DEFAULT '',
  `normal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `late` decimal(12,2) NOT NULL DEFAULT 0.00,
  `early` decimal(12,2) NOT NULL DEFAULT 0.00,
  `absent` decimal(12,2) NOT NULL DEFAULT 0.00,
  `trip` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paidLeave` decimal(12,2) NOT NULL DEFAULT 0.00,
  `unpaidLeave` decimal(12,2) NOT NULL DEFAULT 0.00,
  `timeOvertime` decimal(12,2) NOT NULL DEFAULT 0.00,
  `restOvertime` decimal(12,2) NOT NULL DEFAULT 0.00,
  `holidayOvertime` decimal(12,2) NOT NULL DEFAULT 0.00,
  `deserve` decimal(12,2) NOT NULL DEFAULT 0.00,
  `actual` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `account` (`account`),
  KEY `month` (`month`),
  KEY `status` (`status`),
  UNIQUE KEY `attend` (`month`,`account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `sys_category` ADD `users` text NOT NULL AFTER `replyID`; 
ALTER TABLE `sys_category` CHANGE `rights` `rights` varchar(255) NOT NULL DEFAULT '';

ALTER TABLE `sys_article` 
ADD `private` enum('0', '1') NOT NULL DEFAULT '0',
ADD `users` text NOT NULL,
ADD `groups` varchar(255) NOT NULL DEFAULT '';

ALTER TABLE `oa_doc` 
ADD `private` enum('0', '1') NOT NULL DEFAULT '0' AFTER `deleted`,
ADD `users` text NOT NULL AFTER `private`,
ADD `groups` varchar(255) NOT NULL DEFAULT '' AFTER `users`;

ALTER TABLE `oa_doclib` 
ADD `private` enum('0', '1') NOT NULL DEFAULT '0' AFTER `name`,
ADD `users` text NOT NULL AFTER `private`,
ADD `groups` varchar(255) NOT NULL DEFAULT '' AFTER `users`,
ADD `createdBy` varchar(30) NOT NULL AFTER `groups`,
ADD `createdDate` datetime NOT NULL AFTER `createdBy`,
ADD `editedBy` varchar(30) NOT NULL AFTER `createdDate`,
ADD `editedDate` datetime NOT NULL AFTER `editedBy`;

-- DROP TABLE IF EXISTS `oa_overtime`;
CREATE TABLE `oa_overtime` (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `year` char(4) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `start` time NOT NULL,
  `finish` time NOT NULL,
  `hours` float(4,1) unsigned NOT NULL DEFAULT '0.0',
  `type` varchar(30) NOT NULL DEFAULT '',
  `desc` text NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT '',
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `reviewedBy` char(30) NOT NULL,
  `reviewedDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `year` (`year`),
  KEY `type` (`type`),
  KEY `status` (`status`),
  KEY `createdBy` (`createdBy`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
