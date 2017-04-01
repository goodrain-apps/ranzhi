ALTER TABLE `oa_todo` CHANGE `name` `name` varchar(255) NOT NULL;
ALTER TABLE `sys_category` CHANGE `major` `major` enum('0','1','2','3','4','5','6','7','8') NOT NULL DEFAULT '0';
ALTER TABLE `cash_trade` ADD `investID` mediumint(8) unsigned NOT NULL AFTER `contract`;
ALTER TABLE `cash_trade` ADD `loanID` mediumint(8) unsigned NOT NULL AFTER `investID`;
ALTER TABLE `cash_trade` CHANGE `type` `type` enum('in', 'out', 'transferin', 'transferout', 'invest', 'redeem', 'loan', 'repay') NOT NULL;
ALTER TABLE `oa_holiday` ADD `type` enum('holiday', 'working') NOT NULL DEFAULT 'holiday' AFTER `name`;
ALTER TABLE `sys_action` CHANGE `reader` `reader` text NOT NULL;

ALTER TABLE `sys_user` DROP INDEX `account`;
ALTER TABLE `sys_user` ADD UNIQUE KEY `account` (`account`);
ALTER TABLE `sys_user` ADD INDEX `accountPassword` (`account`, `password`);

UPDATE `cash_trade` SET `type`='invest' WHERE `type`='';
