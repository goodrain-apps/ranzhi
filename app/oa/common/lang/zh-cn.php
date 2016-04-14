<?php
/**
 * The zh-cn file of common module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
$lang->menu->oa->project   = '项目|project|index|';
$lang->menu->oa->announce  = '公告|announce|browse|';
$lang->menu->oa->doc       = '文档|doc|browse|';
$lang->menu->oa->attend    = '考勤|attend|personal|';
$lang->menu->oa->leave     = '请假|leave|personal|';
$lang->menu->oa->overtime  = '加班|overtime|personal|';
$lang->menu->oa->trip      = '出差|trip|personal|';
$lang->menu->oa->refund    = '报销|refund|personal|';
$lang->menu->oa->setting   = '设置|setting|modules|app=oa';

$lang->dashboard = new stdclass();

$lang->project   = new stdclass();
$lang->project->menu = new stdclass();
$lang->project->menu->involved = '我参与的|project|index|status=involved';
$lang->project->menu->doing    = '进行中|project|index|status=doing';
$lang->project->menu->finished = '已完成|project|index|ststus=finished';
$lang->project->menu->suspend  = '已挂起|project|index|ststus=suspend';

$lang->announce = new stdclass();
$lang->announce->menu = new stdclass();
$lang->announce->menu->browse   = array('link' => '公告列表|announce|browse|', 'alias' => 'view');
$lang->announce->menu->category = '类目管理|tree|browse|type=announce|';

$lang->doc = new stdclass();
$lang->doc->menu = new stdclass();
$lang->doc->menu->create = '添加文档库|doc|createlib|';

$lang->attend = new stdclass();
$lang->attend->menu = new stdclass();
$lang->attend->menu->personal   = '我的考勤|attend|personal|';
$lang->attend->menu->department = '部门考勤|attend|department|';
$lang->attend->menu->company    = '公司考勤|attend|company|';
$lang->attend->menu->review     = '补录审核|attend|browsereview|';
$lang->attend->menu->stat       = '统计|attend|stat|';
$lang->attend->menu->holiday    = '节假日|holiday|browse|';
$lang->attend->menu->settings   = array('link' => '设置|attend|settings|', 'alias' => 'setmanager');

$lang->holiday = new stdclass();
$lang->holiday->menu = $lang->attend->menu;
$lang->menuGroups->holiday = 'attend';

$lang->leave = new stdclass();
$lang->leave->menu = new stdclass();
$lang->leave->menu->personal     = '我的请假|leave|personal|';
$lang->leave->menu->browseReview = '我的审核|leave|browsereview|';
$lang->leave->menu->company      = '所有请假|leave|company|';

$lang->overtime = new stdclass();
$lang->overtime->menu = new stdclass();
$lang->overtime->menu->personal     = '我的加班|overtime|personal|';
$lang->overtime->menu->browseReview = '我的审核|overtime|browsereview|';
$lang->overtime->menu->company      = '所有加班|overtime|company|';

$lang->trip = new stdclass();
$lang->trip->menu = new stdclass();
$lang->trip->menu->personal   = '我的出差|trip|personal|';
$lang->trip->menu->department = '部门|trip|department|';
$lang->trip->menu->company    = '公司|trip|company|';

$lang->refund = new stdclass();
$lang->refund->menu = new stdclass();
$lang->refund->menu->personal = array('link' => '我的报销|refund|personal|', 'alias' => 'create');
$lang->refund->menu->review   = '待审批|refund|browsereview|';
$lang->refund->menu->todo     = '待报销|refund|todo|';
$lang->refund->menu->company  = '所有报销|refund|company|';
$lang->refund->menu->settings = array('link' => '设置|refund|settings|', 'alias' => 'setcategory');

$lang->setting->menu = new stdclass();
$lang->setting->menu->modules = '功能模块|setting|modules|app=oa';
