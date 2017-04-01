<?php
/**
 * The zh-cn file of common module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng wang <chunsheng@cnezsoft.com>
 * @package     common 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$lang->app = new stdclass();
$lang->app->name = 'CASH';

$lang->menu->cash = new stdclass();
$lang->menu->cash->dashboard = '首页|dashboard|index|';
$lang->menu->cash->all       = '所有|trade|browse|mode=all';
$lang->menu->cash->in        = '收入|trade|browse|mode=in';
$lang->menu->cash->out       = '支出|trade|browse|mode=out';
$lang->menu->cash->transfer  = '转账|trade|browse|mode=transfer';
$lang->menu->cash->invest    = '投资|trade|browse|mode=invest';
$lang->menu->cash->loan      = '借贷|trade|browse|mode=loan';
$lang->menu->cash->check     = '对账|depositor|check|';
$lang->menu->cash->report    = '报表|trade|report|';
$lang->menu->cash->depositor = '账户|depositor|browse|';
$lang->menu->cash->provider  = '供应商|provider|browse|';
//$lang->menu->cash->contact   = '联系人|contact|browse|';
$lang->menu->cash->setting   = '设置|tree|browse|type=in|';

/* Menu of depositor module. */
if(!isset($lang->depositor)) $lang->depositor = new stdclass();

/* Menu of trade module. */
if(!isset($lang->trade)) $lang->trade = new stdclass();
$lang->trade->menu = new stdclass();

/* Menu of contact module. */
if(!isset($lang->contact)) $lang->contact = new stdclass();
$lang->contact->menu = new stdclass();
$lang->contact->menu->browse = array('link' => '联系人列表|contact|browse|', 'alias' => 'create,edit,view');

/* Menu of report module. */
if(!isset($lang->report)) $lang->report = new stdclass();
$lang->report->menu = new stdclass();
$lang->report->menu->annual  = '年度收支表|trade|report|';
$lang->report->menu->compare = '年度对比表|trade|compare|';
$lang->report->menu->export  = '账号盈亏表|trade|export2Excel|mode=depositor';
$lang->report->menu->setting = '报表单位|trade|setReportUnit|';

/* Menu of setting module. */
$lang->setting = new stdclass();
$lang->setting->menu = new stdclass();
$lang->setting->menu->income    = '收入科目|tree|browse|type=in|';
$lang->setting->menu->expend    = '支出科目|tree|browse|type=out|';
$lang->setting->menu->currency  = '货币类型|setting|lang|module=common&field=currencyList';
$lang->setting->menu->schema    = '导入模板设置|schema|browse|';
$lang->setting->menu->tradePriv = '支出浏览权限设置|group|managetradepriv|';
include(dirname(__FILE__) . '/menuOrder.php');
