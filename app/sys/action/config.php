<?php
/**
 * The config file of action module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     action 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$config->action->require = new stdclass();
$config->action->require->createRecord = 'contact,comment';

$config->action->objectNameFields['product']   = 'name';
$config->action->objectNameFields['task']      = 'name';
$config->action->objectNameFields['user']      = 'account';
$config->action->objectNameFields['customer']  = 'name';
$config->action->objectNameFields['contract']  = 'name';
$config->action->objectNameFields['article']   = 'title';
$config->action->objectNameFields['order']     = 'id';
$config->action->objectNameFields['doc']       = 'title';
$config->action->objectNameFields['contact']   = 'realname';
$config->action->objectNameFields['project']   = 'name';
$config->action->objectNameFields['resume']    = 'id';
$config->action->objectNameFields['announce']  = 'title';
$config->action->objectNameFields['todo']      = 'name';
$config->action->objectNameFields['attend']    = 'date';
$config->action->objectNameFields['leave']     = 'begin';
$config->action->objectNameFields['refund']    = 'name';
$config->action->objectNameFields['depositor'] = 'abbr';
$config->action->objectNameFields['trade']     = 'id';
$config->action->objectNameFields['doclib']    = 'name';
$config->action->objectNameFields['schema']    = 'name';

$config->action->objectAppNames['task']      = 'oa';
$config->action->objectAppNames['doc']       = 'oa';
$config->action->objectAppNames['article']   = 'oa';
$config->action->objectAppNames['project']   = 'oa';
$config->action->objectAppNames['announce']  = 'oa';
$config->action->objectAppNames['attend']    = 'oa';
$config->action->objectAppNames['leave']     = 'oa';
$config->action->objectAppNames['refund']    = 'oa';
$config->action->objectAppNames['doclib']    = 'oa';
$config->action->objectAppNames['user']      = 'sys';
$config->action->objectAppNames['todo']      = 'sys';
$config->action->objectAppNames['customer']  = 'crm';
$config->action->objectAppNames['contract']  = 'crm';
$config->action->objectAppNames['order']     = 'crm';
$config->action->objectAppNames['contact']   = 'crm';
$config->action->objectAppNames['product']   = 'crm';
$config->action->objectAppNames['resume']    = 'crm';
$config->action->objectAppNames['depositor'] = 'cash';
$config->action->objectAppNames['trade']     = 'cash';
$config->action->objectAppNames['schema']    = 'cash';
