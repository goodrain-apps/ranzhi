<?php
/**
 * The config file of action module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     action 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$config->action->require = new stdclass();
$config->action->require->createRecord = 'contact,comment';

$config->action->objectNameFields['announce']  = 'title';
$config->action->objectNameFields['article']   = 'title';
$config->action->objectNameFields['attend']    = 'date';
$config->action->objectNameFields['contact']   = 'realname';
$config->action->objectNameFields['contract']  = 'name';
$config->action->objectNameFields['customer']  = 'name';
$config->action->objectNameFields['depositor'] = 'abbr';
$config->action->objectNameFields['doc']       = 'title';
$config->action->objectNameFields['doclib']    = 'name';
$config->action->objectNameFields['holiday']   = 'name';
$config->action->objectNameFields['leads']     = 'realname';
$config->action->objectNameFields['leave']     = 'begin';
$config->action->objectNameFields['lieu']      = 'begin';
$config->action->objectNameFields['makeup']    = 'begin';
$config->action->objectNameFields['order']     = 'id';
$config->action->objectNameFields['overtime']  = 'begin';
$config->action->objectNameFields['product']   = 'name';
$config->action->objectNameFields['project']   = 'name';
$config->action->objectNameFields['provider']  = 'name';
$config->action->objectNameFields['refund']    = 'name';
$config->action->objectNameFields['resume']    = 'id';
$config->action->objectNameFields['schema']    = 'name';
$config->action->objectNameFields['task']      = 'name';
$config->action->objectNameFields['todo']      = 'name';
$config->action->objectNameFields['trade']     = 'id';
$config->action->objectNameFields['user']      = 'account';

$config->action->objectAppNames['announce']  = 'oa';
$config->action->objectAppNames['article']   = 'oa';
$config->action->objectAppNames['attend']    = 'oa';
$config->action->objectAppNames['contact']   = 'crm';
$config->action->objectAppNames['contract']  = 'crm';
$config->action->objectAppNames['customer']  = 'crm';
$config->action->objectAppNames['depositor'] = 'cash';
$config->action->objectAppNames['doc']       = 'doc';
$config->action->objectAppNames['doclib']    = 'doc';
$config->action->objectAppNames['holiday']   = 'oa';
$config->action->objectAppNames['leads']     = 'crm';
$config->action->objectAppNames['leave']     = 'oa';
$config->action->objectAppNames['lieu']      = 'oa';
$config->action->objectAppNames['makeup']    = 'oa';
$config->action->objectAppNames['order']     = 'crm';
$config->action->objectAppNames['overtime']  = 'oa';
$config->action->objectAppNames['product']   = 'crm';
$config->action->objectAppNames['project']   = 'proj';
$config->action->objectAppNames['provider']  = 'cash';
$config->action->objectAppNames['refund']    = 'oa';
$config->action->objectAppNames['resume']    = 'crm';
$config->action->objectAppNames['schema']    = 'cash';
$config->action->objectAppNames['task']      = 'proj';
$config->action->objectAppNames['todo']      = 'sys';
$config->action->objectAppNames['trade']     = 'cash';
$config->action->objectAppNames['user']      = 'sys';

$config->action->actionModules['createorder']           = 'order';
$config->action->actionModules['editorder']             = 'order';
$config->action->actionModules['assignorder']           = 'order';
$config->action->actionModules['closeorder']            = 'order';
$config->action->actionModules['activateorder']         = 'order';
$config->action->actionModules['createcontract']        = 'contract';
$config->action->actionModules['editcontract']          = 'contract';
$config->action->actionModules['delivercontract']       = 'contract';
$config->action->actionModules['receivecontract']       = 'contract';
$config->action->actionModules['finishdelivercontract'] = 'contract';
$config->action->actionModules['finishreceivecontract'] = 'contract';
$config->action->actionModules['finishcontract']        = 'contract';
$config->action->actionModules['cancelcontract']        = 'contract';
