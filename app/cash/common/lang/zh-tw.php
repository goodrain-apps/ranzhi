<?php
/**
 * The zh-tw file of common module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng wang <chunsheng@cnezsoft.com>
 * @package     common 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$lang->app = new stdclass();
$lang->app->name = 'CASH';

$lang->menu->cash = new stdclass();
$lang->menu->cash->dashboard = '首頁|dashboard|index|';
$lang->menu->cash->all       = '所有|trade|browse|mode=all';
$lang->menu->cash->in        = '收入|trade|browse|mode=in';
$lang->menu->cash->out       = '支出|trade|browse|mode=out';
$lang->menu->cash->transfer  = '轉賬|trade|browse|mode=transfer';
$lang->menu->cash->invest    = '投資|trade|browse|mode=invest';
$lang->menu->cash->loan      = '借貸|trade|browse|mode=loan';
$lang->menu->cash->check     = '對賬|depositor|check|';
$lang->menu->cash->report    = '報表|trade|report|';
$lang->menu->cash->depositor = '賬戶|depositor|browse|';
$lang->menu->cash->provider  = '供應商|provider|browse|';
//$lang->menu->cash->contact   = '聯繫人|contact|browse|';
$lang->menu->cash->setting   = '設置|tree|browse|type=in|';

/* Menu of depositor module. */
if(!isset($lang->depositor)) $lang->depositor = new stdclass();

/* Menu of trade module. */
if(!isset($lang->trade)) $lang->trade = new stdclass();
$lang->trade->menu = new stdclass();

/* Menu of contact module. */
if(!isset($lang->contact)) $lang->contact = new stdclass();
$lang->contact->menu = new stdclass();
$lang->contact->menu->browse = array('link' => '聯繫人列表|contact|browse|', 'alias' => 'create,edit,view');

/* Menu of report module. */
if(!isset($lang->report)) $lang->report = new stdclass();
$lang->report->menu = new stdclass();
$lang->report->menu->annual  = '年度收支表|trade|report|';
$lang->report->menu->compare = '年度對比表|trade|compare|';
$lang->report->menu->export  = '賬號盈虧表|trade|export2Excel|mode=depositor';
$lang->report->menu->setting = '報表單位|trade|setReportUnit|';

/* Menu of setting module. */
$lang->setting = new stdclass();
$lang->setting->menu = new stdclass();
$lang->setting->menu->income    = '收入科目|tree|browse|type=in|';
$lang->setting->menu->expend    = '支出科目|tree|browse|type=out|';
$lang->setting->menu->currency  = '貨幣類型|setting|lang|module=common&field=currencyList';
$lang->setting->menu->schema    = '導入模板設置|schema|browse|';
$lang->setting->menu->tradePriv = '支出瀏覽權限設置|group|managetradepriv|';
include(dirname(__FILE__) . '/menuOrder.php');
