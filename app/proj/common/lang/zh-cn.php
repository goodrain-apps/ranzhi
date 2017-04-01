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
$lang->app->name = 'PROJ';

$lang->menu->proj = new stdclass();
$lang->menu->proj->dashboard = '首页|dashboard|index|';
$lang->menu->proj->project   = '项目|project|index|status=involved';
$lang->menu->proj->task      = '任务|task|browse|projectID=&mode=assignedTo';

$lang->dashboard = new stdclass();

if(!isset($lang->project)) $lang->project = new stdclass();
$lang->project->menu = new stdclass();
$lang->project->menu->involved  = '我参与的|project|index|status=involved';
$lang->project->menu->doing     = '进行中|project|index|status=doing';
$lang->project->menu->finished  = '已完成|project|index|ststus=finished';
$lang->project->menu->suspend   = '已挂起|project|index|ststus=suspend';

$lang->task->menu = new stdclass();
$lang->task->menu->assignedTo = '指派给我|task|browse|projectID=&mode=assignedTo';
$lang->task->menu->createdBy  = '由我创建|task|browse|projectID=&mode=createdBy';
$lang->task->menu->finishedBy = '由我完成|task|browse|projectID=&mode=finishedBy';

include (dirname(__FILE__) . '/menuOrder.php');
