ALTER TABLE `oa_doclib` ADD `project` mediumint(8) unsigned NOT NULL AFTER `id`,
ADD `main` enum('0', '1') NOT NULL default '0' AFTER `groups`,
ADD `order` tinyint(5) unsigned NOT NULL AFTER `main`;

ALTER TABLE `oa_doc` ADD `version` smallint unsigned NOT NULL DEFAULT '1' AFTER `editedDate`;

UPDATE `sys_lang` SET `app`='sys' WHERE `app`='crm' AND `module`='product';
UPDATE `sys_lang` SET `app`='sys' WHERE `app`='crm' AND `module`='customer';

UPDATE `oa_doc` SET `type` = 'text' WHERE `type` = 'file';
UPDATE `sys_category` SET `type`='doc' WHERE `type`='customdoc';
UPDATE `sys_block` SET `app`='proj',`source`='proj' WHERE `app`='oa' AND `source`='oa' AND `block` in ('project','task');

DELETE FROM `sys_grouppriv` WHERE `module`='leave' AND `method`='reviewBack';

INSERT INTO `sys_entry` (`name`, `abbr`, `code`, `buildin`, `integration`, `open`, `key`, `ip`, `logo`, `login`, `control`, `size`, `position`, `visible`, `order`) VALUES
('项目', '项目', 'proj', 1, 1, 'iframe', 'b1fbfec042ee3daaee1edfb0bb59d036', '*', 'theme/default/images/ips/app-proj.png', '../proj', 'simple', 'max', 'default', 1, 21),
('文档', '文档', 'doc', 1, 1, 'iframe', '76ff605479df34f1d239730efa68d562', '*', 'theme/default/images/ips/app-doc.png', '../doc', 'simple', 'max', 'default', 1, 22);

-- DROP TABLE IF EXISTS `oa_doccontent`;
CREATE TABLE IF NOT EXISTS `oa_doccontent` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `doc` mediumint(8) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `digest` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `files` text NOT NULL,
  `type` varchar(10) NOT NULL,
  `version` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `docVersion` (`doc`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `cash_trade` ADD INDEX `depositor` (`depositor`);
ALTER TABLE `cash_trade` ADD INDEX `parent` (`parent`);
ALTER TABLE `cash_trade` ADD INDEX `product` (`product`);
ALTER TABLE `cash_trade` ADD INDEX `trader` (`trader`);
ALTER TABLE `cash_trade` ADD INDEX `order` (`order`);
ALTER TABLE `cash_trade` ADD INDEX `contract` (`contract`);
ALTER TABLE `cash_trade` ADD INDEX `investID` (`investID`);
ALTER TABLE `cash_trade` ADD INDEX `loanID` (`loanID`);
ALTER TABLE `cash_trade` ADD INDEX `dept` (`dept`);
