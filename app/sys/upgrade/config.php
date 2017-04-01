<?php
$config->upgrade = new stdclass();
$config->upgrade->lowerTables = array();
$config->upgrade->lowerTables['oa_docLib']         = 'oa_doclib';
$config->upgrade->lowerTables['sys_groupPriv']     = 'sys_grouppriv';
$config->upgrade->lowerTables['sys_userGroup']     = 'sys_usergroup';
$config->upgrade->lowerTables['sys_userQuery']     = 'sys_userquery';
$config->upgrade->lowerTables['crm_contractOrder'] = 'crm_contractorder';

$config->delete = array();
$config->delete['3_6'][] = 'lib/export2excel/export2excel.class.php';
$config->delete['3_7'][] = 'app/crm/customer/';
$config->delete['3_7'][] = 'app/crm/product/';
$config->delete['3_7'][] = 'app/oa/project/';
$config->delete['3_7'][] = 'app/oa/doc/';
$config->delete['4_0'][] = 'app/sys/error/';
