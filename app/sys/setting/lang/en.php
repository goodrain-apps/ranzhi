<?php
/**
 * The setting module english file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     setting 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$lang->setting->common = 'Setting';
$lang->setting->reset  = 'Reset';
$lang->setting->key    = 'Key';
$lang->setting->value  = 'Value';

$lang->setting->lang = 'Product status, Product lines, Customer types, Customer Size, Customer Level, Customer Status, Currency, Roles';

$lang->setting->customerPool = 'Customer settings';
$lang->setting->modules      = 'Modules settings';

$lang->setting->module = new stdClass();
$lang->setting->module->user     = 'User roles';
$lang->setting->module->product  = 'Product status';
$lang->setting->module->customer = 'Customer level';

$lang->setting->user = new stdClass();
$lang->setting->user->fields['roleList'] = 'Roles';

$lang->setting->product = new stdClass();
$lang->setting->product->fields['statusList'] = 'Product status';
$lang->setting->product->fields['lineList']   = 'Product line';

$lang->setting->product->lineList = new stdclass();
$lang->setting->product->lineList->key   = 'Code';
$lang->setting->product->lineList->value = 'Name';

$lang->setting->customer = new stdClass();
$lang->setting->customer->fields['typeList']      = 'Customer type';
$lang->setting->customer->fields['sizeNameList']  = 'Customer Size';
$lang->setting->customer->fields['levelNameList'] = 'Customer Level';
$lang->setting->customer->fields['statusList']    = 'Customer Status';

$lang->setting->system = new stdclass();
$lang->setting->system->setCurrency            = 'Set currency';
$lang->setting->system->fields['currencyList'] = 'Currency list';

$lang->setting->allLang     = 'For all language';
$lang->setting->currentLang = 'For current language';

$lang->setting->placeholder = new stdclass();
$lang->setting->placeholder->key   = 'Key';
$lang->setting->placeholder->value = 'Define the value';
$lang->setting->placeholder->info  = 'Description';

$lang->setting->placeholder->typeList = new stdclass();
$lang->setting->placeholder->typeList->key = 'Key should be 1~30 letters.';

$lang->setting->placeholder->sizeNameList = new stdclass();
$lang->setting->placeholder->sizeNameList->key   = 'Key should be intergers or letters.';
$lang->setting->placeholder->sizeNameList->value = 'Brief description';
$lang->setting->placeholder->sizeNameList->info  = 'Detailed description';

$lang->setting->placeholder->levelNameList = new stdclass();
$lang->setting->placeholder->levelNameList->key   = 'Key should be intergers or letters.';
$lang->setting->placeholder->levelNameList->value = 'Brief description';
$lang->setting->placeholder->levelNameList->info  = 'Detailed description';

$lang->setting->placeholder->lineList = new stdclass();
$lang->setting->placeholder->lineList->key   = 'Key should be intergers or letters.';
$lang->setting->placeholder->lineList->value = 'Brief description';

$lang->setting->reserveDays    = 'Hold days in customer pools.';
$lang->setting->reserveDaysTip = "Customer will be automatically saved in customer pools, if customer info is not updated within certain days. It will be disabled if it is set as'0' days.";

$lang->setting->moduleList['attend']   = 'Attend';
$lang->setting->moduleList['leave']    = 'Leave';
$lang->setting->moduleList['makeup']   = 'Makeup';
$lang->setting->moduleList['overtime'] = 'Overtime';
$lang->setting->moduleList['lieu']     = 'Lieu';
$lang->setting->moduleList['trip']     = 'Trip';
$lang->setting->moduleList['egress']   = 'Egress';
$lang->setting->moduleList['refund']   = 'Reimburse';
