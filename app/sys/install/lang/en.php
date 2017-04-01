<?php
/**
 * The English file of install module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install 
 * @version     $Id: en.php 4029 2016-08-26 06:50:41Z liugang $
 * @link        http://www.ranzhico.com
 */
$lang->install = new stdclass();
$lang->install->common  = 'Install';
$lang->install->next    = 'Next';
$lang->install->pre     = 'Back';
$lang->install->reload  = 'Reload';
$lang->install->error   = 'Error ';

$lang->install->start            = 'Start install';
$lang->install->keepInstalling   = 'Keep install this version';
$lang->install->seeLatestRelease = 'See the latest release.';
$lang->install->welcome          = 'Welcome to use RanZhi.';
$lang->install->license          = 'RanZhi use license of Z PUBLIC LICENSE(ZPL) 1.2. ';
$lang->install->desc             = <<<EOT
<blockquote>
  <strong>{$lang->ranzhi}</strong>Developed by<strong><a href='http://www.cnezsoft.com' target='_blank' class='red'>QingDao Nature Easy Soft Network Technology Co,LTD</a></strong>，
  With projects, customers, cash flow, office and communication of the five core functions.
  Tailored specifically for small and medium sized groups, small and medium sized team of information technology tool of choice!

  Website:<a href='http://www.ranzhico.com' target='_blank'>http://www.ranzhico.com</a>
  Suport: <a href='http://www.ranzhico.com/forum/' target='_blank'>http://www.ranzhico.com/forum/</a>
  Current Version: <strong class='red'>%s</strong>。
</blockquote>
EOT;

$lang->install->choice     = 'You can ';
$lang->install->checking   = 'System Checking';
$lang->install->ok         = 'OK(√)';
$lang->install->fail       = 'Failed(×)';
$lang->install->loaded     = 'Loaded';
$lang->install->unloaded   = 'Not loaded';
$lang->install->exists     = 'Exists ';
$lang->install->notExists  = 'Do not exists';
$lang->install->writable   = 'Writable ';
$lang->install->notWritable= 'Not writable ';
$lang->install->phpINI     = 'PHP ini file';
$lang->install->checkItem  = 'Items';
$lang->install->current    = 'Current';
$lang->install->result     = 'Results';
$lang->install->action     = 'Actions';

$lang->install->phpVersion = 'PHP version';
$lang->install->phpFail    = 'Must > 5.2.0';

$lang->install->pdo          = 'PDO extension';
$lang->install->pdoFail      = 'Edit the php.ini file to load PDO extsion.';
$lang->install->pdoMySQL     = 'PDO_MySQL extension';
$lang->install->pdoMySQLFail = 'Edit the php.ini file to load PDO_MySQL extsion.';
$lang->install->tmpRoot      = 'Temp directory';
$lang->install->dataRoot     = 'Upload directory.';
$lang->install->sessionRoot  = 'session directory';
$lang->install->mkdir        = '<p>Should creat the directory %s. <br /> In Linux, can try<br /> mkdir -p %s</p>';
$lang->install->chmod        = 'Should change the permission of "%s".<br /> In Linux, can try<br />chmod o=rwx -R %s';
$lang->install->sessionChmod = 'Should change the permission of "%s".<br /> In Linux, can try<br />sudo chmod o=wtx %s';

$lang->install->settingDB    = 'Set Database';
$lang->install->dbHost     = 'Database Host';
$lang->install->dbHostNote = 'If localhost can connect, try 127.0.0.1';
$lang->install->dbPort     = 'Host Port';
$lang->install->dbUser     = 'Database User';
$lang->install->dbPassword = 'Database Password';
$lang->install->dbName     = 'Database Name';
$lang->install->dbPrefix   = 'Table Prefix';
$lang->install->createDB   = 'Auto Create Database';
$lang->install->clearDB    = 'Clear data if database exists.';

$lang->install->errorDBName        = "'.' are not allowed in database name";
$lang->install->errorConnectDB     = 'Database connect failed.';
$lang->install->errorCreateDB      = 'Database create failed.';
$lang->install->errorDBExists      = 'Database exists. If continue installing, check the Clear Database box.';
$lang->install->errorCreateTable   = 'Table creation failed.';

$lang->install->setConfig  = 'Create config file';
$lang->install->key        = 'Item';
$lang->install->value      = 'Value';
$lang->install->saveConfig = 'Save config';
$lang->install->save2File  = '<div class="text-center"><span class="fail">Try to save the config auto, but failed.</span></div>Copy the text of the textareaand save to "<strong> %s </strong>".';
$lang->install->saved2File = 'The config file has saved to "<strong>%s</strong> ".';
$lang->install->errorNotSaveConfig = 'Do not save the config file.';

$lang->install->domainIP = 'IP of domain is %s';
$lang->install->serverIP = 'IP of LAN is %s';
$lang->install->publicIP = 'IP of WAN is %s';
$lang->install->setAdmin = 'Create an Admin';
$lang->install->account  = 'Account';
$lang->install->password = 'Password';
$lang->install->errorEmptyPassword = "should not be empty";

$lang->install->success    = "Installed!";

$lang->install->buildinEntry = new stdclass();
$lang->install->buildinEntry->crm['name']  = 'CRM';
$lang->install->buildinEntry->crm['abbr']  = '';
$lang->install->buildinEntry->cash['name'] = 'CASH';
$lang->install->buildinEntry->cash['abbr'] = '';
$lang->install->buildinEntry->oa['name']   = 'OA';
$lang->install->buildinEntry->oa['abbr']   = 'OA';
$lang->install->buildinEntry->team['name'] = 'TEAM';
$lang->install->buildinEntry->team['abbr'] = '';
$lang->install->buildinEntry->doc['name']  = 'DOC';
$lang->install->buildinEntry->doc['abbr']  = '';
$lang->install->buildinEntry->proj['name'] = 'PORJ';
$lang->install->buildinEntry->proj['abbr'] = '';
