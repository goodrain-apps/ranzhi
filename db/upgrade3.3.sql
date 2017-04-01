ALTER TABLE `crm_customer` ADD `category` char(30) NOT NULL AFTER `weixin`;
ALTER TABLE `crm_customer` ADD INDEX `relation` (`relation`);
ALTER TABLE `crm_customer` ADD INDEX `category` (`category`);
ALTER TABLE `crm_customer` ADD INDEX `public` (`public`);
ALTER TABLE `crm_customer` ADD INDEX `assignedTo` (`assignedTo`);
ALTER TABLE `sys_article`  ADD `readers`  text NOT NULL;
ALTER TABLE `oa_todo` CHANGE `type` `type` char(20) NOT NULL;
