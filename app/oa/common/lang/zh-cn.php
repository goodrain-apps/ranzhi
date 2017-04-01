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
$lang->app->name = 'OA';

$lang->menu->oa = new stdclass();
$lang->menu->oa->dashboard = '首页|dashboard|index|';
$lang->menu->oa->announce  = '公告|announce|browse|';
$lang->menu->oa->attend    = '考勤|attend|personal|';
$lang->menu->oa->leave     = '请假|leave|personal|';
$lang->menu->oa->makeup    = '补班|makeup|personal|';
$lang->menu->oa->overtime  = '加班|overtime|personal|';
$lang->menu->oa->lieu      = '调休|lieu|personal|';
$lang->menu->oa->trip      = '出差|trip|personal|';
$lang->menu->oa->egress    = '外出|egress|personal|';
$lang->menu->oa->refund    = '报销|refund|personal|';
$lang->menu->oa->holiday   = '节假日|holiday|browse|';
$lang->menu->oa->setting   = '设置|setting|modules|app=oa';

$lang->dashboard = new stdclass();

if(!isset($lang->announce)) $lang->announce = new stdclass();
$lang->announce->menu = new stdclass();
$lang->announce->menu->browse   = array('link' => '公告列表|announce|browse|', 'alias' => 'create,edit,view');
$lang->announce->menu->category = '类目管理|tree|browse|type=announce|';

if(!isset($lang->attend)) $lang->attend = new stdclass();
$lang->attend->menu = new stdclass();
$lang->attend->menu->personal   = '我的考勤|attend|personal|';
$lang->attend->menu->department = '部门考勤|attend|department|';
$lang->attend->menu->company    = '公司考勤|attend|company|';
$lang->attend->menu->detail     = '考勤明细|attend|detail|';
$lang->attend->menu->review     = '补录审核|attend|browsereview|';
$lang->attend->menu->stat       = '统计|attend|stat|';
$lang->attend->menu->settings   = array('link' => '设置|attend|settings|', 'alias' => 'setmanager');

if(!isset($lang->leave)) $lang->leave = new stdclass();
$lang->leave->menu = new stdclass();
$lang->leave->menu->personal     = '我的请假|leave|personal|';
$lang->leave->menu->browseReview = '我的审核|leave|browsereview|';
$lang->leave->menu->company      = '所有请假|leave|company|';
$lang->leave->menu->settings     = '设置|leave|setReviewer|';

if(!isset($lang->makeup)) $lang->makeup = new stdclass();
$lang->makeup->menu = new stdclass();
$lang->makeup->menu->personal     = '我的补班|makeup|personal|';
$lang->makeup->menu->browseReview = '我的审核|makeup|browsereview|';
$lang->makeup->menu->company      = '所有补班|makeup|company|';
$lang->makeup->menu->settings     = '设置|makeup|setReviewer|';

if(!isset($lang->overtime)) $lang->overtime = new stdclass();
$lang->overtime->menu = new stdclass();
$lang->overtime->menu->personal     = '我的加班|overtime|personal|';
$lang->overtime->menu->browseReview = '我的审核|overtime|browsereview|';
$lang->overtime->menu->company      = '所有加班|overtime|company|';
$lang->overtime->menu->settings     = '设置|overtime|setReviewer|';

if(!isset($lang->lieu)) $lang->lieu = new stdclass();
$lang->lieu->menu = new stdclass();
$lang->lieu->menu->personal     = '我的调休|lieu|personal|';
$lang->lieu->menu->browseReview = '我的审核|lieu|browsereview|';
$lang->lieu->menu->company      = '所有调休|lieu|company|';
$lang->lieu->menu->settings     = '设置|lieu|setReviewer|';

if(!isset($lang->trip)) $lang->trip = new stdclass();
$lang->trip->menu = new stdclass();
$lang->trip->menu->personal   = '我的出差|trip|personal|';
$lang->trip->menu->department = '部门|trip|department|';
$lang->trip->menu->company    = '公司|trip|company|';

if(!isset($lang->egress)) $lang->egress = new stdclass();
$lang->egress->menu = new stdclass();
$lang->egress->menu->personal   = '我的外出|egress|personal|';
$lang->egress->menu->department = '部门|egress|department|';
$lang->egress->menu->company    = '公司|egress|company|';

if(!isset($lang->refund)) $lang->refund = new stdclass();
$lang->refund->menu = new stdclass();
$lang->refund->menu->personal = array('link' => '我的报销|refund|personal|', 'alias' => 'create,edit');
$lang->refund->menu->review     = '待审批|refund|browsereview|';
$lang->refund->menu->reviewedBy = '由我审批|refund|browsereview|date=&status=reviewed';
$lang->refund->menu->todo       = '待报销|refund|todo|';
$lang->refund->menu->company    = '所有报销|refund|company|';
$lang->refund->menu->settings   = array('link' => '设置|refund|setreviewer|', 'alias' => 'setcategory,setmoney,setdepositor,setrefundby');

if(!isset($lang->holiday)) $lang->holiday = new stdclass();
$lang->holiday->menu = new stdclass();
$lang->holiday->menu->all = '所有|holiday|browse|';

$lang->setting->menu = new stdclass();
$lang->setting->menu->modules         = '功能模块|setting|modules|app=oa';
$lang->setting->menu->companyAttend   = '公司考勤设置|attend|settings|module=setting';
$lang->setting->menu->personalAttend  = '个人考勤设置|attend|personalSettings|module=setting';
$lang->setting->menu->deptManager     = '部门经理设置|attend|setManager|module=setting';
$lang->setting->menu->leaveReviewer   = '请假审批人|leave|setReviewer|module=setting';
$lang->setting->menu->makeupReviewer  = '补班审批人|makeup|setReviewer|module=setting';
$lang->setting->menu->lieuReviewer    = '调休审批人|lieu|setReviewer|module=setting';
$lang->setting->menu->overtimeReviewer= '加班审批人|overtime|setReviewer|module=setting';
$lang->setting->menu->refundReviewer  = '报销审批人|refund|setReviewer|module=setting';
$lang->setting->menu->refundCategory  = '报销科目|refund|setCategory|module=setting';
$lang->setting->menu->refundDepositor = '报销账户|refund|setDepositor|module=setting';
$lang->setting->menu->refundBy        = '由谁报销|refund|setRefundBy|module=setting';
include (dirname(__FILE__) . '/menuOrder.php');
