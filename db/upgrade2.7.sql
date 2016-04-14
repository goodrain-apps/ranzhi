ALTER TABLE `sys_user` add `ping` datetime NOT NULL AFTER `last`;
ALTER TABLE `sys_action` add `read` enum('0', '1') NOT NULL DEFAULT '0' AFTER `extra`;
ALTER TABLE `sys_action` add `reader` varchar(255) NOT NULL DEFAULT '' AFTER `read`;
ALTER TABLE `cash_depositor` add `tags` varchar(255) NOT NULL AFTER `title`;
ALTER TABLE `sys_task` DROP `children`;
ALTER TABLE `oa_leave` add `hours` float(4,1) unsigned NOT NULL DEFAULT '0.0' AFTER `finish`;
