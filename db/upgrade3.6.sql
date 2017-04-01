ALTER TABLE `oa_trip` ADD `type` enum('trip', 'egress') NOT NULL DEFAULT 'trip' AFTER `id`;
ALTER TABLE `oa_trip` ADD `customers` varchar(20) NOT NULL AFTER `type`;
ALTER TABLE `oa_attendstat` ADD `egress` decimal(12,2) NOT NULL DEFAULT 0.00 AFTER `trip`;
ALTER TABLE `oa_attendstat` ADD `lieu` decimal(12,2) NOT NULL DEFAULT 0.00 AFTER `egress`;
ALTER TABLE `oa_overtime` ADD `rejectReason` varchar(100) NOT NULL AFTER `status`;
ALTER TABLE `oa_leave` ADD `backDate` datetime NOT NULL AFTER `hours`;
ALTER TABLE `crm_customer` ADD `depositor` varchar(100) NOT NULL AFTER `category`;

ALTER TABLE `crm_contact`  ADD INDEX `origin` (`origin`);
ALTER TABLE `crm_delivery` ADD INDEX `contract` (`contract`);
ALTER TABLE `crm_plan`     ADD INDEX `contract` (`contract`);

UPDATE `sys_entry` SET `category` = 0 WHERE `buildin` = '1';
UPDATE `oa_overtime` SET `type` = 'compensate' WHERE `type` = 'lieu';

-- DROP TABLE IF EXISTS `oa_lieu`;
CREATE TABLE `oa_lieu` (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `year` char(4) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `start` time NOT NULL,
  `finish` time NOT NULL,
  `hours` float(4,1) unsigned NOT NULL DEFAULT '0.0',
  `overtime` char(255) NOT NULL,
  `desc` text NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT '',
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `reviewedBy` char(30) NOT NULL,
  `reviewedDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `year` (`year`),
  KEY `status` (`status`),
  KEY `createdBy` (`createdBy`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
