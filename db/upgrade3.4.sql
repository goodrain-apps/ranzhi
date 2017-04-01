ALTER TABLE `oa_refund` CHANGE `status` `status` enum('draft','wait','doing','pass','reject','finish') NOT NULL DEFAULT 'wait';
ALTER TABLE `cash_trade` CHANGE `type` `type` enum('in','out','transferin','transferout','invest','redeem') NOT NULL;
ALTER TABLE `sys_category` CHANGE `major` `major` enum('0','1','2','3','4') NOT NULL DEFAULT '0';

UPDATE `cash_trade` SET `type` = 'invest' WHERE `type` = '';
UPDATE `sys_grouppriv` SET `method` = 'invest' WHERE `module` = 'trade' and `method` = 'inveset';

REPLACE INTO `sys_grouppriv` (`group`, `module`, `method`) VALUES
(1,'trade','compare'),
(1,'trade','export2Excel'),
(1,'attend','detail'),
(1,'attend','exportDetail'),
(1,'leave','export'),
(1,'overtime','export'),
(1,'refund','export'),
(2,'trade','compare'),
(2,'trade','export2Excel');
