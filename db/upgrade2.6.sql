ALTER TABLE `oa_todo` add `assignedTo` varchar(30) NOT NULL DEFAULT '' AFTER `private`;
ALTER TABLE `oa_todo` add `assignedBy` varchar(30) NOT NULL DEFAULT '' AFTER `assignedTo`;
ALTER TABLE `oa_todo` add `assignedDate` datetime NOT NULL AFTER `assignedBy`;
ALTER TABLE `oa_todo` add `finishedBy` varchar(30) NOT NULL DEFAULT '' AFTER `assignedDate`;
ALTER TABLE `oa_todo` add `finishedDate` datetime NOT NULL AFTER `finishedBy`;
ALTER TABLE `oa_todo` add `closedBy` varchar(30) NOT NULL DEFAULT '' AFTER `finishedDate`;
ALTER TABLE `oa_todo` add `closedDate` datetime NOT NULL AFTER `closedBy`;
ALTER TABLE `oa_todo` CHANGE `status` `status` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `sys_entry` add `zentao` enum('0', '1') NOT NULL DEFAULT '0';
