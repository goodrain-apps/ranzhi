<?php
/**
 * The English file of entry module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entry 
 * @version     $Id: en.php 4091 2016-09-30 07:16:50Z daitingting $
 * @link        http://www.ranzhico.com
 */
$lang->entry->common      = 'App';
$lang->entry->admin       = 'Apps';
$lang->entry->create      = 'Create';
$lang->entry->edit        = 'Edit';
$lang->entry->delete      = 'Delete';
$lang->entry->createKey   = 'New';
$lang->entry->order       = 'Sort';
$lang->entry->style       = 'Style';
$lang->entry->setCategory = 'Manange';

$lang->entry->name        = 'App Name';
$lang->entry->abbr        = 'Abbr';
$lang->entry->code        = 'Alias';
$lang->entry->buildin     = 'Build-in';
$lang->entry->integration = 'Integrate';
$lang->entry->key         = 'Key';
$lang->entry->block       = 'Block URL';
$lang->entry->ip          = 'IPs';
$lang->entry->logo        = 'Logo';
$lang->entry->login       = 'Access URL';
$lang->entry->logout      = 'Logout URL';
$lang->entry->nothing     = 'N/A';
$lang->entry->open        = 'Open';
$lang->entry->control     = 'Window Control';
$lang->entry->size        = 'Window Size';
$lang->entry->position    = 'Position';
$lang->entry->width       = 'Width';
$lang->entry->height      = 'Height';
$lang->entry->priv        = 'Privilege';
$lang->entry->category    = 'Category';

$lang->entry->chanzhi          = 'Changer';
$lang->entry->zentao           = 'ZenTao';
$lang->entry->integrateChanzhi = 'Integrate Changer';
$lang->entry->integrateZentao  = 'Integrate ZenTao';

$lang->entry->chanzhiPlaceholder = 'Please enter Admin URL';
$lang->entry->chanzhiURL         = 'Admin URL';
$lang->entry->zentaoPlaceholder  = 'E.g. http://www.zentaopms.com/user-login-Lw==.html';
$lang->entry->zentaoURL          = 'ZenTao URL';

$lang->entry->zentaoAdmin   = 'ZenTao Admin';
$lang->entry->adminAccount  = 'ZenTao Admin';
$lang->entry->adminPassword = 'Password';
$lang->entry->bindUser      = 'Bind User';
$lang->entry->nextStep      = 'Next';
$lang->entry->createUser    = 'Create User';

$lang->entry->confirmDelete = 'Do you want to delete this App?';
$lang->entry->lblBlock      = 'Block';
$lang->entry->editWarnning  = 'This is a system application. Think before you change it.';

$lang->entry->note = new stdClass();
$lang->entry->note->name    = 'Name';
$lang->entry->note->abbr    = 'Abbreviation';
$lang->entry->note->logo    = 'Logo size 64*64. if upload the PNG format, you must keep transparency.';
$lang->entry->note->code    = 'Entry alias should be letters, digits or underline.';
$lang->entry->note->login   = 'Login URL or access App.';
$lang->entry->note->logout  = 'Logout URL ';
$lang->entry->note->visible = 'Display on the left';
$lang->entry->note->api     = 'The URL of getting blocks.';
$lang->entry->note->ip      = "Use comma between two IPs. IP segment is supported, e.g. 192.168.1.*";
$lang->entry->note->allip   = 'All';
$lang->entry->note->scheme  = 'The current scheme is https, and the iframe window can only open the https URL.';

$lang->entry->error = new stdClass();
$lang->entry->error->name  = 'Please enter name';
$lang->entry->error->code  = 'Please enter alias';
$lang->entry->error->key   = 'Please enter key';
$lang->entry->error->ip    = 'Please enter IP';
$lang->entry->error->url   = 'No built-in application login address. /, http:// or https:// must be included.';

$lang->entry->error->admin         = 'Wrong admin account or password.';
$lang->entry->error->zentaoSetting = 'ZenTao PMS config failed. Upgrade Zentao PMS to head.';
$lang->entry->error->version       = 'Your ZenTao PMS version is lower than %s';
$lang->entry->error->zentaoUrl     = 'Wrong ZenTao PMS login url.';
$lang->entry->error->accessDenied  = 'Access denied';

$lang->entry->openList['blank']  = 'Blank';
$lang->entry->openList['iframe'] = 'Iframe';

$lang->entry->sizeList['max']    = 'Maximize';
$lang->entry->sizeList['custom'] = 'Custom';

$lang->entry->positionList['default'] = 'Default';
$lang->entry->positionList['center']  = 'Center';

$lang->entry->controlList['none']   = 'None';
$lang->entry->controlList['full']   = 'Full';
$lang->entry->controlList['simple'] = 'Transparent';

$lang->entry->integrationList[1] = 'Open';
$lang->entry->integrationList[0] = 'Close';
