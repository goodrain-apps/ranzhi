<?php
$lang->sys->menuOrder[5]  = 'company';
$lang->sys->menuOrder[10] = 'user';
$lang->sys->menuOrder[15] = 'group';
$lang->sys->menuOrder[20] = 'entry';
$lang->sys->menuOrder[25] = 'system';
$lang->sys->menuOrder[30] = 'package';

$lang->entry->menuOrder[5]  = 'admin';
$lang->entry->menuOrder[10] = 'create';
$lang->entry->menuOrder[15] = 'webapp';
$lang->entry->menuOrder[20] = 'category';

$lang->system->menuOrder[5]  = 'mail';
$lang->system->menuOrder[10] = 'trash';
$lang->system->menuOrder[15] = 'cron';
$lang->system->menuOrder[20] = 'backup';

$lang->sys->dashboard = new stdclass();
$lang->sys->dashboard->menuOrder[5]  = 'todo';
$lang->sys->dashboard->menuOrder[10] = 'task'; 
$lang->sys->dashboard->menuOrder[15] = 'project';
$lang->sys->dashboard->menuOrder[20] = 'order';
$lang->sys->dashboard->menuOrder[25] = 'contract';
$lang->sys->dashboard->menuOrder[30] = 'review';
$lang->sys->dashboard->menuOrder[35] = 'company';
$lang->sys->dashboard->menuOrder[40] = 'dynamic';

$lang->customer->menuOrder[5]  = 'browse'; 
$lang->customer->menuOrder[10] = 'assignedTo';
$lang->customer->menuOrder[15] = 'past';
$lang->customer->menuOrder[20] = 'today';
$lang->customer->menuOrder[25] = 'tomorrow';
$lang->customer->menuOrder[30] = 'thisweek';
$lang->customer->menuOrder[35] = 'thismonth';
$lang->customer->menuOrder[40] = 'public';
$lang->customer->menuOrder[45] = 'report';

$lang->product->menuOrder[5]  = 'browse';
$lang->product->menuOrder[10] = 'normal';
$lang->product->menuOrder[15] = 'developing';
$lang->product->menuOrder[20] = 'offline';

$lang->todo->menuOrder[5]  = 'calendar';
$lang->todo->menuOrder[10] = 'all';
$lang->todo->menuOrder[15] = 'assignedToOther';
$lang->todo->menuOrder[20] = 'assignedToMe';
$lang->todo->menuOrder[25] = 'undone';
$lang->todo->menuOrder[30] = 'future';

$lang->mail->menuOrder   = $lang->system->menuOrder;
$lang->action->menuOrder = $lang->system->menuOrder;
$lang->cron->menuOrder   = $lang->system->menuOrder;
$lang->backup->menuOrder = $lang->system->menuOrder;

$lang->my->review->menuOrder[5]  = 'attend';
$lang->my->review->menuOrder[10] = 'leave';
$lang->my->review->menuOrder[15] = 'overtime';
$lang->my->review->menuOrder[20] = 'lieu';
$lang->my->review->menuOrder[25] = 'refund';

$lang->my->order->menuOrder[5]  = 'past';
$lang->my->order->menuOrder[10] = 'today';
$lang->my->order->menuOrder[15] = 'tomorrow';
$lang->my->order->menuOrder[20] = 'assignedTo';
$lang->my->order->menuOrder[25] = 'createdBy';
$lang->my->order->menuOrder[30] = 'signedBy';
$lang->my->order->menuOrder[35] = 'all';
 
$lang->my->contract->menuOrder[5]  = 'unfinished';
$lang->my->contract->menuOrder[10] = 'finished';
$lang->my->contract->menuOrder[15] = 'canceled';
$lang->my->contract->menuOrder[20] = 'returnedBy';
$lang->my->contract->menuOrder[25] = 'deliveredBy';

$lang->my->task->menuOrder[5]  = 'assignedToMe';
$lang->my->task->menuOrder[10] = 'createdByMe';
$lang->my->task->menuOrder[15] = 'finishedByMe';
$lang->my->task->menuOrder[20] = 'closedByMe';
$lang->my->task->menuOrder[25] = 'canceledByMe';
$lang->my->task->menuOrder[30] = 'unclosed';

$lang->my->dynamic->menuOrder[5]  = 'today';
$lang->my->dynamic->menuOrder[10] = 'yesterday';
$lang->my->dynamic->menuOrder[15] = 'twodaysago';
$lang->my->dynamic->menuOrder[20] = 'thisweek';
$lang->my->dynamic->menuOrder[25] = 'lastweek';
$lang->my->dynamic->menuOrder[30] = 'thismonth';
$lang->my->dynamic->menuOrder[35] = 'lastmonth';
$lang->my->dynamic->menuOrder[40] = 'all';
