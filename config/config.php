<?php
/**
 * The config file of RanZhi.
 *
 * Don't modify this file directly, copy the item to my.php and change it.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     config
 * @version     $Id: config.php 3642 2016-02-24 06:17:54Z daitingting $
 * @link        http://www.ranzhi.org
 */
/* Judge class config and function getWebRoot exists or not, make sure php shells can work. */
if(!class_exists('config')){class config{}}
if(!function_exists('getWebRoot')){function getWebRoot(){}}

/* Basic settings. */
$config = new config();
$config->version      = '3.2.1';           // The version of ranzhi. Don't change it.
$config->debug        = true;              // Turn debug on or off.
$config->charset      = 'UTF-8';           // The charset of ranzhi.
$config->cookieLife   = time() + 2592000;  // The cookie life time.
$config->timezone     = 'Asia/Shanghai';   // The time zone setting, for more see http://www.php.net/manual/en/timezones.php
$config->cookiePath   = '/';               // The path of cookies.
$config->webRoot      = getWebRoot();      // The web root.
$config->checkVersion = true;              // Auto check for new version or not.
$config->timeout      = 30 * 1000;         // The timeout of ajax request.
$config->pingInterval = 60;                // The interval of ping request, seconds.

/* The request settings. */
$config->requestType = 'PATH_INFO';       // The request type: PATH_INFO|GET, if PATH_INFO, must use url rewrite.
$config->pathType    = 'clean';           // If the request type is PATH_INFO, the path type.
$config->requestFix  = '-';               // The divider in the url when PATH_INFO.
$config->moduleVar   = 'm';               // requestType=GET: the module var name.
$config->methodVar   = 'f';               // requestType=GET: the method var name.
$config->viewVar     = 't';               // requestType=GET: the view var name.
$config->sessionVar  = 'rid';             // requestType=GET: the session var name.

/* Supported views. */
$config->views = ',html,json,mhtml,'; 

/* Supported languages. */
$config->langs['zh-cn'] = '简体';
$config->langs['zh-tw'] = '繁体';
$config->langs['en']    = 'English';

/* Supported charsets. */
$config->charsets['zh-cn']['utf-8'] = 'UTF-8';
$config->charsets['zh-cn']['gbk']   = 'GBK';
$config->charsets['zh-tw']['utf-8'] = 'UTF-8';
$config->charsets['zh-tw']['big5']  = 'BIG5';
$config->charsets['en']['utf-8']    = 'UTF-8';

/* Default settings. */
$config->default = new stdclass();
$config->default->view   = 'html';        // Default view.
$config->default->lang   = 'en';          // Default language.
$config->default->theme  = 'default';     // Default theme.
$config->default->module = 'index';       // Default module.
$config->default->method = 'index';       // Default method.

/* Upload settings: danger files and max upload size. */
$config->file = new stdclass();
$config->file->dangers = 'php,php3,php4,phtml,php5,jsp,py,rb,asp,aspx,ashx,asa,cer,cdx,aspl,shtm,shtml,html,htm';
$config->file->maxSize = 1024 * 1024;

/* Set the allowed tags.  */
$config->allowedTags = new stdclass();
$config->allowedTags->front = '<p><span><h1><h2><h3><h4><h5><em><u><strong><br><ol><ul><li><img><a><b><font><hr><pre>';    // For front mode.
$config->allowedTags->admin = $config->allowedTags->front . '<div><table><td><th><tr><tbody>';                             // For admin users.

/* Master database settings. */
$config->db = new stdclass();
$config->db->persistant     = false;     // Pconnect or not.
$config->db->driver         = 'mysql';   // Must be MySQL. Don't support other database server yet.
$config->db->encoding       = 'UTF8';    // Encoding of database.
$config->db->strictMode     = false;     // Turn off the strict mode of MySQL.
//$config->db->emulatePrepare = true;    // PDO::ATTR_EMULATE_PREPARES
//$config->db->bufferQuery    = true;    // PDO::MYSQL_ATTR_USE_BUFFERED_QUERY

/* Slave database settings. */
$config->slaveDB = new stdclass();
$config->slaveDB->persistant = false;      
$config->slaveDB->driver     = 'mysql';    
$config->slaveDB->encoding   = 'UTF8';     
$config->slaveDB->strictMode = false;      
$config->slaveDB->checkCentOS= true;       

/* Include the custom config file. */
$configRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$myConfig   = $configRoot . 'my.php';
if(file_exists($myConfig)) include $myConfig;
$rightsConfig = $configRoot . 'rights.php';
if(file_exists($rightsConfig)) include $rightsConfig;

/* Tables for basic system. */
define('TABLE_CONFIG',    '`sys_config`');
define('TABLE_PACKAGE',   '`sys_package`');
define('TABLE_USER',      '`sys_user`');
define('TABLE_GROUP',     '`sys_group`');
define('TABLE_ACTION',    '`sys_action`');
define('TABLE_FILE',      '`sys_file`');
define('TABLE_HISTORY',   '`sys_history`');
define('TABLE_CATEGORY',  '`sys_category`');
define('TABLE_ARTICLE',   '`sys_article`');
define('TABLE_EXTENSION', '`sys_extension`');
define('TABLE_WEBAPP',    '`sys_webapp`');
define('TABLE_LANG',      '`sys_lang`');
define('TABLE_ENTRY',     '`sys_entry`');
define('TABLE_SSO',       '`sys_sso`');
define('TABLE_TASK',      '`sys_task`');
define('TABLE_TEAM',      '`sys_team`');
define('TABLE_ISSUE',     '`sys_issue`');
define('TABLE_TAG',       '`sys_tag`');
define('TABLE_BLOCK',     '`sys_block`');
define('TABLE_SCHEMA',    '`sys_schema`');
define('TABLE_RELATION',  '`sys_relation`');
define('TABLE_CRON',      '`sys_cron`');
define('TABLE_USERGROUP', '`sys_usergroup`');
define('TABLE_GROUPPRIV', '`sys_grouppriv`');
define('TABLE_USERQUERY', '`sys_userquery`');

/* Tables for crm. */
define('TABLE_ADDRESS',       '`crm_address`');
define('TABLE_PRODUCT',       '`sys_product`');
define('TABLE_ORDER',         '`crm_order`');
define('TABLE_CUSTOMER',      '`crm_customer`');
define('TABLE_RESUME',        '`crm_resume`');
define('TABLE_CONTACT',       '`crm_contact`');
define('TABLE_CONTRACT',      '`crm_contract`');
define('TABLE_CONTRACTORDER', '`crm_contractorder`');
define('TABLE_PLAN',          '`crm_plan`');
define('TABLE_SERVICE',       '`crm_service`');
define('TABLE_DELIVERY',      '`crm_delivery`');
define('TABLE_SALESGROUP',    '`crm_salesgroup`');
define('TABLE_SALESPRIV',     '`crm_salespriv`');

/* Tables for oa. */
define('TABLE_TODO',       '`oa_todo`');
define('TABLE_PROJECT',    '`oa_project`');
define('TABLE_EFFORT',     '`oa_effort`');
define('TABLE_BOOK',       '`oa_book`');
define('TABLE_LAYOUT',     '`oa_layout`');
define('TABLE_DOC',        '`oa_doc`');
define('TABLE_DOCLIB',     '`oa_doclib`');
define('TABLE_ATTEND',     '`oa_attend`');
define('TABLE_ATTENDSTAT', '`oa_attendstat`');
define('TABLE_HOLIDAY',    '`oa_holiday`');
define('TABLE_LEAVE',      '`oa_leave`');
define('TABLE_OVERTIME',   '`oa_overtime`');
define('TABLE_TRIP',       '`oa_trip`');
define('TABLE_REFUND',     '`oa_refund`');

/* Tables for cash. */
define('TABLE_DEPOSITOR', '`cash_depositor`');
define('TABLE_BALANCE',   '`cash_balance`');
define('TABLE_TRADE',     '`cash_trade`');

/* Tables for team. */
define('TABLE_THREAD',  '`team_thread`');
define('TABLE_REPLY',   '`team_reply`');
define('TABLE_MESSAGE', '`sys_message`');

/* The mapping list of object and tables. */
$config->objectTables['product']     = TABLE_PRODUCT;
$config->objectTables['project']     = TABLE_PROJECT;
$config->objectTables['task']        = TABLE_TASK;
$config->objectTables['user']        = TABLE_USER;
$config->objectTables['todo']        = TABLE_TODO;
$config->objectTables['order']       = TABLE_ORDER;
$config->objectTables['contract']    = TABLE_CONTRACT;
$config->objectTables['customer']    = TABLE_CUSTOMER;
$config->objectTables['contact']     = TABLE_CONTACT;
$config->objectTables['thread']      = TABLE_THREAD;
$config->objectTables['article']     = TABLE_ARTICLE;
$config->objectTables['doc']         = TABLE_DOC;
$config->objectTables['cron']        = TABLE_CRON;
$config->objectTables['resume']      = TABLE_RESUME;
$config->objectTables['refund']      = TABLE_REFUND;
$config->objectTables['announce']    = TABLE_ARTICLE;
$config->objectTables['attend']      = TABLE_ATTEND;
$config->objectTables['leave']       = TABLE_LEAVE;
$config->objectTables['depositor']   = TABLE_DEPOSITOR;
$config->objectTables['trade']       = TABLE_TRADE;
$config->objectTables['doclib']      = TABLE_DOCLIB;
$config->objectTables['schema']      = TABLE_SCHEMA;

/* Include extension config files. */
$extConfigFiles = glob($configRoot . 'ext/*.php');
if($extConfigFiles) foreach($extConfigFiles as $extConfigFile) include $extConfigFile;
