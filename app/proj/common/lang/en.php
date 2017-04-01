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
$lang->app->name = 'PROJ';

$lang->menu->proj = new stdclass();
$lang->menu->proj->dashboard = 'Home|dashboard|index|';
$lang->menu->proj->project   = 'Project|project|index|status=involved';
$lang->menu->proj->task      = 'Task|task|browse|projectID=&mode=assignedTo';

$lang->dashboard = new stdclass();

if(!isset($lang->project)) $lang->project = new stdclass();
$lang->project->menu = new stdclass();
$lang->project->menu->involved  = 'My Involved|project|index|status=involved';
$lang->project->menu->doing     = 'Projects|project|index|status=doing';
$lang->project->menu->finished  = 'Finished|project|index|ststus=finished';
$lang->project->menu->suspend   = 'Suspended|project|index|ststus=suspend';

$lang->task->menu = new stdclass();
$lang->task->menu->assignedTo = 'Assigned To Me|task|browse|projectID=&mode=assignedTo';
$lang->task->menu->createdBy  = 'Created By Me|task|browse|projectID=&mode=createdBy';
$lang->task->menu->finishedBy = 'Finished By Me|task|browse|projectID=&mode=finishedBy';

include (dirname(__FILE__) . '/menuOrder.php');
