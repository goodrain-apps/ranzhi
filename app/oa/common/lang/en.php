<?php
/**
 * The en file of common module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     common 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$lang->app = new stdclass();
$lang->app->name = 'OA';

$lang->menu->oa = new stdclass();
$lang->menu->oa->dashboard = 'Home|dashboard|index|';
$lang->menu->oa->announce  = 'Announce|announce|index|';
$lang->menu->oa->attend    = 'Attendance|attend|personal|';
$lang->menu->oa->leave     = 'Leave|leave|personal|';
$lang->menu->oa->makeup    = 'Makeup|makeup|personal|';
$lang->menu->oa->overtime  = 'Overtime|overtime|personal|';
$lang->menu->oa->lieu      = 'Leave In Lieu|lieu|personal|';
$lang->menu->oa->trip      = 'Trip|trip|personal|';
$lang->menu->oa->egress    = 'Egress|egress|personal|';
$lang->menu->oa->refund    = 'Reimburse|refund|personal|';
$lang->menu->oa->holiday   = 'Holiday|holiday|browse|';
$lang->menu->oa->setting   = 'Settings|setting|module|app=oa';

$lang->dashboard = new stdclass();

if(!isset($lang->announce)) $lang->announce = new stdclass();
$lang->announce->menu = new stdclass();
$lang->announce->menu->browse   = array('link' => 'Announcement|announce|browse|', 'alias' => 'create,edit,view');
$lang->announce->menu->category = 'Categories|tree|browse|type=announce|';

if(!isset($lang->attend)) $lang->attend = new stdclass();
$lang->attend->menu = new stdclass();
$lang->attend->menu->personal   = 'My attendance|attend|personal|';
$lang->attend->menu->department = 'Department|attend|department|';
$lang->attend->menu->company    = 'Company|attend|company|';
$lang->attend->menu->detail     = 'Details|attend|detail|';
$lang->attend->menu->review     = 'Review|attend|browsereview|';
$lang->attend->menu->stat       = 'Report|attend|stat|';
$lang->attend->menu->settings   = array('link' => 'Settings|attend|settings|', 'alias' => 'setmanager');

if(!isset($lang->leave)) $lang->leave = new stdclass();
$lang->leave->menu = new stdclass();
$lang->leave->menu->personal     = 'My leave|leave|personal|';
$lang->leave->menu->browseReview = 'Reviewed by me|leave|browsereview|';
$lang->leave->menu->company      = 'All|leave|company|';
$lang->leave->menu->settings     = 'Settings|leave|setReviewer|';

if(!isset($lang->makeup)) $lang->makeup = new stdclass();
$lang->makeup->menu = new stdclass();
$lang->makeup->menu->personal     = 'My makeup|makeup|personal|';
$lang->makeup->menu->browseReview = 'Reviewed by me|makeup|browsereview|';
$lang->makeup->menu->company      = 'All|makeup|company|';
$lang->makeup->menu->settings     = 'Settings|makeup|setReviewer|';

if(!isset($lang->overtime)) $lang->overtime = new stdclass();
$lang->overtime->menu = new stdclass();
$lang->overtime->menu->personal     = 'My overtime|overtime|personal|';
$lang->overtime->menu->browseReview = 'Reviewed by me|overtime|browsereview|';
$lang->overtime->menu->company      = 'All|overtime|company|';
$lang->overtime->menu->settings     = 'Settings|overtime|setReviewer|';

if(!isset($lang->lieu)) $lang->lieu = new stdclass();
$lang->lieu->menu = new stdclass();
$lang->lieu->menu->personal     = 'My Leave In Lieu|lieu|personal|';
$lang->lieu->menu->browseReview = 'Reviewed By Me|lieu|browsereview|';
$lang->lieu->menu->company      = 'All|lieu|company|';
$lang->lieu->menu->settings     = 'Settings|lieu|setReviewer|';

if(!isset($lang->trip)) $lang->trip = new stdclass();
$lang->trip->menu = new stdclass();
$lang->trip->menu->personal   = 'My Trip|trip|personal|';
$lang->trip->menu->department = 'Department|trip|department|';
$lang->trip->menu->company    = 'Company|trip|company|';

if(!isset($lang->egress)) $lang->egress = new stdclass();
$lang->egress->menu = new stdclass();
$lang->egress->menu->personal   = 'Mine|egress|personal|';
$lang->egress->menu->department = 'Department|egress|department|';
$lang->egress->menu->company    = 'Company|egress|company|';

if(!isset($lang->refund)) $lang->refund = new stdclass();
$lang->refund->menu = new stdclass();
$lang->refund->menu->personal   = array('link' => 'My reimbursement|refund|personal|', 'alias' => 'create, edit');
$lang->refund->menu->review     = 'Review pending|refund|browsereview|';
$lang->refund->menu->reviewedBy = 'Reviewed by me|refund|browsereview|date=&status=reviewed';
$lang->refund->menu->todo       = 'Reimburse pending|refund|todo|';
$lang->refund->menu->company    = 'All|refund|company|';
$lang->refund->menu->settings   = array('link' => 'Settings|refund|settings|', 'alias' => 'setcategory');

if(!isset($lang->holiday)) $lang->holiday = new stdclass();
$lang->holiday->menu = new stdclass();
$lang->holiday->menu->all = 'All|holiday|browse|';

$lang->setting->menu = new stdclass();
$lang->setting->menu->modules         = 'Modules|setting|modules|app=oa';
$lang->setting->menu->companyAttend   = 'Company Attend Settings|attend|settings|module=setting';
$lang->setting->menu->personalAttend  = 'Personal Attend Settings|attend|personalSettings|module=setting';
$lang->setting->menu->deptManager     = 'Dept Manager Settings|attend|setManager|module=setting';
$lang->setting->menu->leaveReviewer   = 'Leave Reviewer|leave|setReviewer|module=setting';
$lang->setting->menu->makeupReviewer  = 'Makeup Reviewer|makeup|setReviewer|module=setting';
$lang->setting->menu->lieuReviewer    = 'Lieu Reviewer|lieu|setReviewer|module=setting';
$lang->setting->menu->overtimeReviewer= 'Overtime Reviewer|overtime|setReviewer|module=setting';
$lang->setting->menu->refundReviewer  = 'Refund Reviewer|refund|setReviewer|module=setting';
$lang->setting->menu->refundCategory  = 'Refund Category|refund|setCategory|module=setting';
$lang->setting->menu->refundDepositor = 'Refund Depositor|refund|setDepositor|module=setting';
$lang->setting->menu->refundBy        = 'Refund By|refund|setRefundBy|module=setting';
include (dirname(__FILE__) . '/menuOrder.php');
