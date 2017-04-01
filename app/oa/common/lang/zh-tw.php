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
$lang->app->name = 'OA';

$lang->menu->oa = new stdclass();
$lang->menu->oa->dashboard = '首頁|dashboard|index|';
$lang->menu->oa->announce  = '公告|announce|browse|';
$lang->menu->oa->attend    = '考勤|attend|personal|';
$lang->menu->oa->leave     = '請假|leave|personal|';
$lang->menu->oa->makeup    = '補班|makeup|personal|';
$lang->menu->oa->overtime  = '加班|overtime|personal|';
$lang->menu->oa->lieu      = '調休|lieu|personal|';
$lang->menu->oa->trip      = '出差|trip|personal|';
$lang->menu->oa->egress    = '外出|egress|personal|';
$lang->menu->oa->refund    = '報銷|refund|personal|';
$lang->menu->oa->holiday   = '節假日|holiday|browse|';
$lang->menu->oa->setting   = '設置|setting|modules|app=oa';

$lang->dashboard = new stdclass();

if(!isset($lang->announce)) $lang->announce = new stdclass();
$lang->announce->menu = new stdclass();
$lang->announce->menu->browse   = array('link' => '公告列表|announce|browse|', 'alias' => 'create,edit,view');
$lang->announce->menu->category = '類目管理|tree|browse|type=announce|';

if(!isset($lang->attend)) $lang->attend = new stdclass();
$lang->attend->menu = new stdclass();
$lang->attend->menu->personal   = '我的考勤|attend|personal|';
$lang->attend->menu->department = '部門考勤|attend|department|';
$lang->attend->menu->company    = '公司考勤|attend|company|';
$lang->attend->menu->detail     = '考勤明細|attend|detail|';
$lang->attend->menu->review     = '補錄審核|attend|browsereview|';
$lang->attend->menu->stat       = '統計|attend|stat|';
$lang->attend->menu->settings   = array('link' => '設置|attend|settings|', 'alias' => 'setmanager');

if(!isset($lang->leave)) $lang->leave = new stdclass();
$lang->leave->menu = new stdclass();
$lang->leave->menu->personal     = '我的請假|leave|personal|';
$lang->leave->menu->browseReview = '我的審核|leave|browsereview|';
$lang->leave->menu->company      = '所有請假|leave|company|';
$lang->leave->menu->settings     = '設置|leave|setReviewer|';

if(!isset($lang->makeup)) $lang->makeup = new stdclass();
$lang->makeup->menu = new stdclass();
$lang->makeup->menu->personal     = '我的補班|makeup|personal|';
$lang->makeup->menu->browseReview = '我的審核|makeup|browsereview|';
$lang->makeup->menu->company      = '所有補班|makeup|company|';
$lang->makeup->menu->settings     = '設置|makeup|setReviewer|';

if(!isset($lang->overtime)) $lang->overtime = new stdclass();
$lang->overtime->menu = new stdclass();
$lang->overtime->menu->personal     = '我的加班|overtime|personal|';
$lang->overtime->menu->browseReview = '我的審核|overtime|browsereview|';
$lang->overtime->menu->company      = '所有加班|overtime|company|';
$lang->overtime->menu->settings     = '設置|overtime|setReviewer|';

if(!isset($lang->lieu)) $lang->lieu = new stdclass();
$lang->lieu->menu = new stdclass();
$lang->lieu->menu->personal     = '我的調休|lieu|personal|';
$lang->lieu->menu->browseReview = '我的審核|lieu|browsereview|';
$lang->lieu->menu->company      = '所有調休|lieu|company|';
$lang->lieu->menu->settings     = '設置|lieu|setReviewer|';

if(!isset($lang->trip)) $lang->trip = new stdclass();
$lang->trip->menu = new stdclass();
$lang->trip->menu->personal   = '我的出差|trip|personal|';
$lang->trip->menu->department = '部門|trip|department|';
$lang->trip->menu->company    = '公司|trip|company|';

if(!isset($lang->egress)) $lang->egress = new stdclass();
$lang->egress->menu = new stdclass();
$lang->egress->menu->personal   = '我的外出|egress|personal|';
$lang->egress->menu->department = '部門|egress|department|';
$lang->egress->menu->company    = '公司|egress|company|';

if(!isset($lang->refund)) $lang->refund = new stdclass();
$lang->refund->menu = new stdclass();
$lang->refund->menu->personal = array('link' => '我的報銷|refund|personal|', 'alias' => 'create,edit');
$lang->refund->menu->review     = '待審批|refund|browsereview|';
$lang->refund->menu->reviewedBy = '由我審批|refund|browsereview|date=&status=reviewed';
$lang->refund->menu->todo       = '待報銷|refund|todo|';
$lang->refund->menu->company    = '所有報銷|refund|company|';
$lang->refund->menu->settings   = array('link' => '設置|refund|setreviewer|', 'alias' => 'setcategory,setmoney,setdepositor,setrefundby');

if(!isset($lang->holiday)) $lang->holiday = new stdclass();
$lang->holiday->menu = new stdclass();
$lang->holiday->menu->all = '所有|holiday|browse|';

$lang->setting->menu = new stdclass();
$lang->setting->menu->modules         = '功能模組|setting|modules|app=oa';
$lang->setting->menu->companyAttend   = '公司考勤設置|attend|settings|module=setting';
$lang->setting->menu->personalAttend  = '個人考勤設置|attend|personalSettings|module=setting';
$lang->setting->menu->deptManager     = '部門經理設置|attend|setManager|module=setting';
$lang->setting->menu->leaveReviewer   = '請假審批人|leave|setReviewer|module=setting';
$lang->setting->menu->makeupReviewer  = '補班審批人|makeup|setReviewer|module=setting';
$lang->setting->menu->lieuReviewer    = '調休審批人|lieu|setReviewer|module=setting';
$lang->setting->menu->overtimeReviewer= '加班審批人|overtime|setReviewer|module=setting';
$lang->setting->menu->refundReviewer  = '報銷審批人|refund|setReviewer|module=setting';
$lang->setting->menu->refundCategory  = '報銷科目|refund|setCategory|module=setting';
$lang->setting->menu->refundDepositor = '報銷賬戶|refund|setDepositor|module=setting';
$lang->setting->menu->refundBy        = '由誰報銷|refund|setRefundBy|module=setting';
include (dirname(__FILE__) . '/menuOrder.php');
