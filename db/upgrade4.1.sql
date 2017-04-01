ALTER TABLE `crm_contract` ADD `address` varchar(255) NOT NULL AFTER `contact`;
ALTER TABLE `oa_overtime` ADD `leave` varchar(255) NOT NULL AFTER `id`;

UPDATE `sys_block` SET `app`='sys',`source`='proj' WHERE `app`='sys' AND `source`='oa' AND `block` in ('project','task');
UPDATE `sys_entry` SET `open`='iframe' WHERE `buildin`='1';

INSERT INTO `sys_grouppriv` SELECT `group`, 'makeup', `method` FROM `sys_grouppriv` WHERE `module` = 'overtime';
