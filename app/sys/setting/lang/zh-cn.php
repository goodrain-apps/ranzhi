<?php
/**
 * The zh-cn file of setting module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     setting 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$lang->setting->common = '设置';
$lang->setting->reset  = '恢复默认';
$lang->setting->key    = '键';
$lang->setting->value  = '值';

$lang->setting->lang = '产品状态、产品线、客户类型、客户规模、客户等级、客户状态、货币设置、角色';

$lang->setting->customerPool = '客户池设置';
$lang->setting->modules      = '功能模块设置';

$lang->setting->module = new stdClass();
$lang->setting->module->user     = '员工角色';
$lang->setting->module->product  = '产品状态';
$lang->setting->module->customer = '客户级别';

$lang->setting->user = new stdClass();
$lang->setting->user->fields['roleList'] = '角色设置';

$lang->setting->product = new stdClass();
$lang->setting->product->fields['statusList'] = '产品状态';
$lang->setting->product->fields['lineList']   = '产品线';

$lang->setting->product->lineList = new stdclass();
$lang->setting->product->lineList->key   = '代号';
$lang->setting->product->lineList->value = '名称';

$lang->setting->customer = new stdClass();
$lang->setting->customer->fields['typeList']      = '客户类型';
$lang->setting->customer->fields['sizeNameList']  = '客户规模';
$lang->setting->customer->fields['levelNameList'] = '客户等级';
$lang->setting->customer->fields['statusList']    = '客户状态';

$lang->setting->system = new stdclass();
$lang->setting->system->setCurrency            = '使用的货币';
$lang->setting->system->fields['currencyList'] = '货币设置';

$lang->setting->allLang     = '适用所有语言';
$lang->setting->currentLang = '适用当前语言';

$lang->setting->placeholder = new stdclass();
$lang->setting->placeholder->key   = '变量名';
$lang->setting->placeholder->value = '自定义显示值';
$lang->setting->placeholder->info  = '详细描述';

$lang->setting->placeholder->typeList = new stdclass();
$lang->setting->placeholder->typeList->key = '变量名，长度为1~30字符';

$lang->setting->placeholder->sizeNameList = new stdclass();
$lang->setting->placeholder->sizeNameList->key   = '数字和字母组合';
$lang->setting->placeholder->sizeNameList->value = '简短描述';
$lang->setting->placeholder->sizeNameList->info  = '详细描述';

$lang->setting->placeholder->levelNameList = new stdclass();
$lang->setting->placeholder->levelNameList->key   = '数字和字母组合';
$lang->setting->placeholder->levelNameList->value = '简短描述';
$lang->setting->placeholder->levelNameList->info  = '详细描述';

$lang->setting->placeholder->lineList = new stdclass();
$lang->setting->placeholder->lineList->key   = '数字和字母组合';
$lang->setting->placeholder->lineList->value = '简短描述';

$lang->setting->reserveDays    = '进入客户池（天）';
$lang->setting->reserveDaysTip = '在设定天数内没有更新客户信息（未签约客户），该客户将自动进入客户池。值设为0时禁用此功能。';

$lang->setting->moduleList['attend']   = '考勤';
$lang->setting->moduleList['leave']    = '请假';
$lang->setting->moduleList['makeup']   = '补班';
$lang->setting->moduleList['overtime'] = '加班';
$lang->setting->moduleList['lieu']     = '调休';
$lang->setting->moduleList['trip']     = '出差';
$lang->setting->moduleList['egress']   = '外出';
$lang->setting->moduleList['refund']   = '报销';
