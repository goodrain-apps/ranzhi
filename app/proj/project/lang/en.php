<?php
/**
 * The project module en file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     project
 * @version     $Id: zh-cn.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        http://www.ranzhico.com
 */
if(!isset($lang->project)) $lang->project = new stdclass();
$lang->project->common     = 'Project';
$lang->project->browse     = 'Projects List';
$lang->project->index      = 'Projects';
$lang->project->create     = "Create Project";
$lang->project->edit       = 'Edit';
$lang->project->view       = 'Project Detail';
$lang->project->finish     = 'Finish';
$lang->project->delete     = 'Delete';
$lang->project->enter      = 'Enter';
$lang->project->suspend    = 'Suspend';
$lang->project->activate   = 'Activate';
$lang->project->mine       = 'I charge : ';
$lang->project->other      = 'Other : ';
$lang->project->deleted    = 'Deleted';
$lang->project->finished   = 'Finished';
$lang->project->suspended  = 'Suspended';
$lang->project->noMatched  = 'No matched project including "%s"';
$lang->project->search     = 'Search';
$lang->project->import     = 'Import';
$lang->project->importTask = 'Import task';
$lang->project->role       = 'Role';
$lang->project->project    = 'Project';
$lang->project->dateRange  = 'Date Range';

$lang->project->id          = 'ID';
$lang->project->name        = 'Name';
$lang->project->status      = 'Status';
$lang->project->desc        = 'Description';
$lang->project->begin       = 'Start';
$lang->project->manager     = 'Manager';
$lang->project->member      = 'Team';
$lang->project->end         = 'End';
$lang->project->createdBy   = 'Created by';
$lang->project->createdDate = 'Created on';
$lang->project->fromproject = 'From Project';
$lang->project->whitelist   = 'Whitelist';
$lang->project->doc         = 'Document';

$lang->project->confirm = new stdclass();
$lang->project->confirm->activate = 'Do you want to activate this projcet?';
$lang->project->confirm->suspend  = 'Do you want to suspend this projcet?';

$lang->project->activateSuccess = 'Successfully activtated';
$lang->project->suspendSuccess  = 'Successfully suspended';
$lang->project->selectProject   = 'Select Project';

$lang->project->note = new stdclass();
$lang->project->note->rate = 'working hours';
$lang->project->note->task = 'The number of tasks';

$lang->project->statusList['doing']    = 'Doing';
$lang->project->statusList['finished'] = 'Finished';
$lang->project->statusList['suspend']  = 'Suspend';

$lang->project->roleList['member']  = 'Default';
$lang->project->roleList['senior']  = 'Manager';
$lang->project->roleList['limited'] = 'Limited';

$lang->project->whitelistTip        = 'Whitelist members have access to projects and tasks.';
$lang->project->roleTip             = "Managers have all privilages; Default members cannot delete tasks; Limited members can only edit their own tasks.";
$lang->project->roleTips['senior']  = "Managers can view, edit and delete all task.";
$lang->project->roleTips['member']  = "Default: view and edit all tasks and delete their own tasks.";
$lang->project->roleTips['limited'] = "Limited: view and edit their own tasks.";
