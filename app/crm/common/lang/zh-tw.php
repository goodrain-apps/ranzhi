<?php
/**
 * The zh-tw file of crm common module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     common 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$lang->app = new stdclass();
$lang->app->name = 'CRM';

$lang->menu->crm = new stdclass();
$lang->menu->crm->dashboard = '首頁|dashboard|index|';
$lang->menu->crm->order     = '訂單|order|browse|';
$lang->menu->crm->contract  = '合同|contract|browse|mode=unfinished';
$lang->menu->crm->customer  = '客戶|customer|browse|';
$lang->menu->crm->provider  = '供應商|provider|browse|';
$lang->menu->crm->contact   = '聯繫人|contact|browse|';
$lang->menu->crm->leads     = '名單|leads|browse|mode=assignedTo';
$lang->menu->crm->product   = '產品|product|browse|';
$lang->menu->crm->setting   = '設置|setting|lang|module=product&field=statusList';

/* Menu of order module. */
if(!isset($lang->order)) $lang->order = new stdclass();
$lang->order->menu = new stdclass();
$lang->order->menu->browse       = '所有訂單|order|browse|mode=all';
$lang->order->menu->assignedTo   = '指派給我|order|browse|mode=assignedTo';
$lang->order->menu->past         = '亟需聯繫|order|browse|mode=past';
$lang->order->menu->today        = '今天聯繫|order|browse|mode=today';
$lang->order->menu->tomorrow     = '明天聯繫|order|browse|mode=tomorrow';
$lang->order->menu->thisweek     = '本週內聯繫|order|browse|mode=thisweek';
$lang->order->menu->thismonth    = '本月內聯繫|order|browse|mode=thismonth';
$lang->order->menu->public       = '公共客戶|order|browse|mode=public';
$lang->order->menu->report       = '報表|report|browse|module=order';

/* Menu of contact module. */
if(!isset($lang->contact)) $lang->contact = new stdclass();
$lang->contact->menu = new stdclass();
$lang->contact->menu->browse    = '所有聯繫人|contact|browse|mode=all';
$lang->contact->menu->past      = '亟需聯繫|contact|browse|mode=past';
$lang->contact->menu->today     = '今天聯繫|contact|browse|mode=today';
$lang->contact->menu->tomorrow  = '明天聯繫|contact|browse|mode=tomorrow';
$lang->contact->menu->thisweek  = '本週內聯繫|contact|browse|mode=thisweek';
$lang->contact->menu->thismonth = '本月內聯繫|contact|browse|mode=thismonth';

if(!isset($lang->leads)) $lang->leads = new stdclass();
$lang->leads->menu = new stdclass();
$lang->leads->menu->assignedTo  = array('link' => '指派給我|leads|browse|mode=assignedTo', 'alias' => 'create');
$lang->leads->menu->next        = '下次聯繫|leads|browse|mode=next';
$lang->leads->menu->ignoredBy   = '由我忽略|leads|browse|mode=ignoredBy&status=ignore';
$lang->leads->menu->public      = '公共名單|leads|browse|mode=all&status=ignore';
$lang->leads->menu->setting     = '設置|leads|setting|';

/* Menu of contract module. */
if(!isset($lang->contract)) $lang->contract = new stdclass();
$lang->contract->menu = new stdclass();
$lang->contract->menu->browse       = '所有合同|contract|browse|mode=all';
$lang->contract->menu->unfinished   = '未完成|contract|browse|mode=unfinished';
$lang->contract->menu->unreceived   = '回款中|contract|browse|mode=unreceived';
$lang->contract->menu->undeliveried = '交付中|contract|browse|mode=undeliveried';
$lang->contract->menu->finished     = '已完成|contract|browse|mode=finished';
$lang->contract->menu->canceled     = '已取消|contract|browse|mode=canceled';
$lang->contract->menu->expired      = '已過期|contract|browse|mode=expired';
$lang->contract->menu->expire       = '即將到期|contract|browse|mode=expire';
$lang->contract->menu->report       = '報表|report|browse|module=contract';

/* Menu of setting module. */
$lang->setting = new stdclass();
$lang->setting->menu = new stdclass();
$lang->setting->menu->product        = '產品狀態|setting|lang|module=product&field=statusList&appName=sys';
$lang->setting->menu->productLine    = '產品綫|setting|lang|module=product&field=lineList&appName=sys';
$lang->setting->menu->customerType   = '客戶類型|setting|lang|module=customer&field=typeList&appName=sys';
$lang->setting->menu->customerSize   = '客戶規模|setting|lang|module=customer&field=sizeNameList&appName=sys';
$lang->setting->menu->customerLevel  = '客戶等級|setting|lang|module=customer&field=levelNameList&appName=sys';
$lang->setting->menu->customerStatus = '客戶狀態|setting|lang|module=customer&field=statusList&appName=sys';
$lang->setting->menu->area           = '區域設置|tree|browse|type=area|';
$lang->setting->menu->industry       = '行業設置|tree|browse|type=industry|';
$lang->setting->menu->currency       = '貨幣設置|setting|lang|module=common&field=currencyList';
$lang->setting->menu->salesGroup     = array('link' => '銷售分組|sales|admin|', 'alias' => 'browse,create,edit');
$lang->setting->menu->customerPool   = '客戶池|setting|customerpool|';

/* Menu of sales module. */
if(!isset($lang->sales)) $lang->sales = new stdclass();
$lang->sales->menu = $lang->setting->menu;

$lang->dashboard = new stdclass();
if(!isset($lang->resume))  $lang->resume  = new stdclass();
if(!isset($lang->address)) $lang->address = new stdclass();
include (dirname(__FILE__) . '/menuOrder.php');
