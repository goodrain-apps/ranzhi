ALTER TABLE `crm_customer` CHANGE `id`       `id`       mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `crm_service`  CHANGE `customer` `customer` mediumint(8) unsigned NOT NULL;
ALTER TABLE `oa_doc`       CHANGE `views`    `views`    mediumint(8) unsigned NOT NULL;
ALTER TABLE `oa_doclib`    CHANGE `id`       `id`       mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `cash_trade`   CHANGE `trader`   `trader`   mediumint(8) unsigned NOT NULL;
ALTER TABLE `team_thread`  CHANGE `views`    `views`    mediumint(8) unsigned NOT NULL;
ALTER TABLE `team_thread`  CHANGE `replies`  `replies`  mediumint(8) unsigned NOT NULL;
ALTER TABLE `sys_block`    CHANGE `id`       `id`       mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `sys_entry`    CHANGE `id`       `id`       mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `sys_tag`      CHANGE `id`       `id`       mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `sys_user`     CHANGE `id`       `id`       mediumint(8) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `sys_product`  ADD `code` varchar(20) NOT NULL AFTER `name`;
ALTER TABLE `sys_category` ADD `major` enum('0','1') NOT NULL DEFAULT '0';
ALTER TABLE `sys_entry`    ADD `category` mediumint(8) NOT NULL DEFAULT '0';
ALTER TABLE `sys_user` CHANGE `role` `role` char(30) NOT NULL;

UPDATE `oa_todo` set `type` = 'custom' where `type` = 'undone';
