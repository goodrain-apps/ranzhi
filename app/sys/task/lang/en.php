<?php
/**
 * The en file of task module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     task 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
$lang->task->common = 'Task';
$lang->task->list   = 'Tasks';

$lang->task->browse    = 'Browse';
$lang->task->view      = 'Info';
$lang->task->viewChild = 'Child Info';
$lang->task->create    = 'Create';
$lang->task->edit      = 'Update';
$lang->task->finish    = 'Finish';
$lang->task->start     = 'Start';
$lang->task->activate  = 'Activate';
$lang->task->cancel    = 'Cancel';
$lang->task->close     = 'Close';
$lang->task->assignTo  = 'Assign to';
$lang->task->delete    = 'Delete';
$lang->task->export    = 'Export';

$lang->task->batchCreate    = 'Batch Create';
$lang->task->backToProjects = 'Back to project list'; 
$lang->task->viewAll        = "View all project's task";
$lang->task->editAll        = "Edit all project's task";
$lang->task->deleteAll      = "Delete all project's task";

$lang->task->id             = 'ID';
$lang->task->project        = 'Project';
$lang->task->customer       = 'Customer';
$lang->task->order          = 'Order';
$lang->task->category       = 'Category';
$lang->task->name           = 'Name';
$lang->task->type           = 'Type';
$lang->task->pri            = 'Priority';
$lang->task->estimate       = 'Man-Hour Estimated';
$lang->task->estimateAB     = 'Est.';
$lang->task->consumed       = 'Consumed';
$lang->task->consumedAB     = 'Use';
$lang->task->left           = 'Remained';
$lang->task->leftAB         = 'Remianed';
$lang->task->deadline       = 'Deadline';
$lang->task->deadlineAB     = 'Deadline';
$lang->task->status         = 'Status';
$lang->task->statusAB       = 'Status';
$lang->task->statusCustom   = 'Status Order';
$lang->task->mailto         = 'Mailto';
$lang->task->desc           = 'Description';
$lang->task->createdBy      = 'Created By';
$lang->task->createdByAB    = 'Create';
$lang->task->createdDate    = 'Create On';
$lang->task->createdDateAB  = 'Create';
$lang->task->editedBy       = 'Edited By';
$lang->task->editedDate     = 'Edited On';
$lang->task->assignedTo     = 'Assign';
$lang->task->assignedDate   = 'Assigned On';
$lang->task->estStarted     = 'Est. Start';
$lang->task->realStarted    = 'Actual Start';
$lang->task->finishedBy     = 'Finished By';
$lang->task->finishedByAB   = 'Finish';
$lang->task->finishedDate   = 'Finished On';
$lang->task->finishedDateAB = 'Date';
$lang->task->canceledBy     = 'CancelLed By';
$lang->task->canceledDate   = 'CancelLed On';
$lang->task->closedBy       = 'Closed By';
$lang->task->closedDate     = 'Closed On';
$lang->task->closedReason   = 'Closed Reason';
$lang->task->lastEditedBy   = 'Last Edited By';
$lang->task->lastEditedDate = 'Last Edited On';
$lang->task->lastEdited     = 'Last Edited';
$lang->task->hour           = 'Hour';
$lang->task->leftThisTime   = 'Remained';
$lang->task->date           = 'Date';
$lang->task->multiple       = 'Multiple Tasks';
$lang->task->multipleAB     = 'Multiple';
$lang->task->team           = 'Team';
$lang->task->transmit       = 'Transfer';
$lang->task->transmitTo     = 'Transfer To';
$lang->task->children       = 'Child';
$lang->task->childrenAB     = 'Child';
$lang->task->parent         = 'Parent Task';
$lang->task->unfinished     = 'Unfinished';
$lang->task->end            = 'End';
$lang->task->myConsumption  = 'Man-Hour Consumed';
$lang->task->recordEstimate = 'Man-Hour';

$lang->task->confirmFinish     = '"Remained" is zero, so the status of this task will be "done". Do you want to finish it?';
$lang->task->consumedBefore    = 'consumed before';
$lang->task->mailtoPlaceholder = 'Choose users to mail...';

$lang->task->lblPri  = 'P';
$lang->task->lblHour = '(h)';

$lang->task->statusList['']        = '';
$lang->task->statusList['wait']    = 'Wait';
$lang->task->statusList['doing']   = 'Doing';
$lang->task->statusList['done']    = 'Done';
$lang->task->statusList['cancel']  = 'Cancelled';
$lang->task->statusList['closed']  = 'Closed';

$lang->task->typeList['']        = '';
$lang->task->typeList['discuss'] = 'Discuss';
$lang->task->typeList['affair']  = 'Affair';
$lang->task->typeList['misc']    = 'Misc';

$lang->task->priList[0]  = '';
$lang->task->priList[1]  = '1';
$lang->task->priList[2]  = '2';
$lang->task->priList[3]  = '3';
$lang->task->priList[4]  = '4';

$lang->task->reasonList['']       = '';
$lang->task->reasonList['done']   = 'Done';
$lang->task->reasonList['cancel'] = 'Cancelled';

$lang->task->createdByMe  = 'Created By Me';
$lang->task->assignedToMe = 'Assigned To Me';
$lang->task->finishedByMe = 'Finished By Me';
$lang->task->untilToday   = 'Until Today';
$lang->task->expired      = 'Expired';
$lang->task->all          = 'All';

$lang->task->basicInfo = 'Basic Info';
$lang->task->life      = 'Task Life';

$lang->task->kanban  = 'Kanban';
$lang->task->mind    = 'Mind Map';
$lang->task->list    = 'List';
$lang->task->outline = 'Outline';

$lang->task->kanbanGroup['']           = 'Groups';
$lang->task->kanbanGroup['status']     = 'By Status';
$lang->task->kanbanGroup['assignedTo'] = 'By Assigned';

$lang->task->groups['']           = 'Choose group';
$lang->task->groups['status']     = 'by Status';
$lang->task->groups['assignedTo'] = 'Assigned To';
$lang->task->groups['createdBy']  = 'Created By';
$lang->task->groups['finishedBy'] = 'Finished By';
$lang->task->groups['closedBy']   = 'Closed By';

$lang->task->unkown     = 'Unkown';
$lang->task->unAssigned = 'Unassigned';

$lang->task->mindMoveTip = 'You should move a task to a sub node.';
$lang->task->notAllowed  = 'Not allowed.';
$lang->task->skipClose   = 'The status of Tasks : %s are unfinished or cancelled. Not allowed to close.';

$lang->task->groupinfo = "<div class='text-muted'>Total <strong>%s</strong>, Wait <strong>%s</strong>, Doing <strong>%s</strong>, Finished <strong>%s</strong>, Closed <strong>%s</strong></div>";
