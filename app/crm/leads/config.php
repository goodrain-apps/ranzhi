<?php
/**
 * The config file of leads module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     leads 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$config->leads = new stdclass();
$config->leads->editor = new stdclass();
$config->leads->editor->assign = array('id' => 'comment', 'tools' => 'simple');
$config->leads->editor->ignore = array('id' => 'comment', 'tools' => 'simple');
$config->leads->editor->view   = array('id' => 'comment,lastComment', 'tools' => 'simple');

global $lang, $app;
$app->loadLang('contact', 'crm');
$config->leads->search['module'] = 'leads';

$config->leads->search['fields']['realname']      = $lang->contact->realname;
$config->leads->search['fields']['origin']        = $lang->contact->origin;
$config->leads->search['fields']['phone']         = $lang->contact->phone;
$config->leads->search['fields']['mobile']        = $lang->contact->mobile;
$config->leads->search['fields']['email']         = $lang->contact->email;
$config->leads->search['fields']['qq']            = $lang->contact->qq;
$config->leads->search['fields']['contactedDate'] = $lang->contact->contactedDate;
$config->leads->search['fields']['nextDate']      = $lang->contact->nextDate;
$config->leads->search['fields']['id']            = $lang->contact->id;

$config->leads->search['params']['realname']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->leads->search['params']['origin']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->leads->search['params']['phone']         = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->leads->search['params']['mobile']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->leads->search['params']['email']         = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->leads->search['params']['qq']            = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->leads->search['params']['contactedDate'] = array('operator' => '>=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->leads->search['params']['nextDate']      = array('operator' => '>=', 'control' => 'input',  'values' => '', 'class' => 'date');
$config->leads->search['params']['id']            = array('operator' => '=', 'control' => 'input',  'values' => '');
