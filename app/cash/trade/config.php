<?php
/**
 * The config file of trade module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     trade 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$config->trade->require = new stdclass();
$config->trade->require->create = 'depositor,money,type,handlers';
$config->trade->require->edit   = 'depositor,money,type,handlers';
$config->trade->require->invest = 'depositor,money,type,handlers';
$config->trade->require->loan   = 'depositor,money,type,handlers';

$config->trade->batchCreateCount = 10;

$config->trade->importField = 'category,dept,trader,type,money,desc,date,fee,product';

global $lang;
$config->trade->search['module'] = 'trade';

$config->trade->search['fields']['depositor'] = $lang->trade->depositor;
$config->trade->search['fields']['product']   = $lang->trade->product;
$config->trade->search['fields']['type']      = $lang->trade->type;
$config->trade->search['fields']['trader']    = $lang->trade->trader;
$config->trade->search['fields']['money']     = $lang->trade->money;
$config->trade->search['fields']['category']  = $lang->trade->category;
$config->trade->search['fields']['handlers']  = $lang->trade->handlers;
$config->trade->search['fields']['date']      = $lang->trade->date;
$config->trade->search['fields']['id']        = $lang->trade->id;
$config->trade->search['fields']['desc']      = $lang->trade->desc;

$config->trade->search['params']['depositor'] = array('operator' => '=',  'control' => 'select', 'values' => 'set in control');
$config->trade->search['params']['product']   = array('operator' => '=',  'control' => 'select', 'values' => 'set in control');
$config->trade->search['params']['type']      = array('operator' => '=',  'control' => 'select', 'values' => array('' => '') + $lang->trade->typeList);
$config->trade->search['params']['trader']    = array('operator' => '=',  'control' => 'select', 'values' => 'set in control');
$config->trade->search['params']['money']     = array('operator' => '>=', 'control' => 'input',  'values' => '');
$config->trade->search['params']['category']  = array('operator' => '=',  'control' => 'select', 'values' => 'set in control');
$config->trade->search['params']['handlers']  = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->trade->search['params']['date']      = array('operator' => '>=', 'control' => 'input', 'values' => '', 'class' => 'date');
$config->trade->search['params']['id']        = array('operator' => '=',  'control' => 'input',  'values' => '');
$config->trade->search['params']['desc']      = array('operator' => 'include', 'control' => 'input', 'values' => '');

$config->trade->report = new stdclass();
$config->trade->report->annual = array('productLine', 'category', 'area', 'industry', 'size', 'dept');

$config->trade->groupBy['productLine'] = 'product|line|product';
$config->trade->groupBy['category']    = 'trade|category|';
$config->trade->groupBy['area']        = 'customer|area|trader';
$config->trade->groupBy['industry']    = 'customer|industry|trader';
$config->trade->groupBy['size']        = 'customer|size|trader';
$config->trade->groupBy['dept']        = 'trade|dept|';

$config->trade->exportFields = '
  id, depositor, type, money, currency, category, trader, date,
  desc, dept, handlers, product, order, contract,
  createdBy, createdDate, editedBy, editedDate, detail';

$config->trade->excel = new stdclass();
$config->trade->excel->numberFields = array('undefined', 'total');
$config->trade->excel->customWidth  = array('undefined' => 15, 'total' => 15);

/* Excel items. */
$config->excel = new stdclass();
$config->excel->titleFields  = array();
$config->excel->centerFields = array();
$config->excel->dateFields   = array();

$config->excel->width = new stdclass();
$config->excel->width->title   = 30;
$config->excel->width->content = 100;

$config->excel->freeze = new stdclass();
$config->excel->freeze->depositor = 'month';
