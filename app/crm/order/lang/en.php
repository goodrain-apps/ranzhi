<?php
/**
 * The order module en lang file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     order
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
if(!isset($lang->order)) $lang->order = new stdclass();
$lang->order->common         = 'Order';
$lang->order->id             = 'ID';
$lang->order->name           = 'Name';
$lang->order->product        = 'Product';
$lang->order->productLine    = 'Product Line';
$lang->order->customer       = 'Customer';
$lang->order->contact        = 'Contact';
$lang->order->team           = 'Team';
$lang->order->currency       = 'Currency';
$lang->order->plan           = 'Budget';
$lang->order->real           = 'Actual';
$lang->order->planShort      = 'Plan';
$lang->order->realShort      = 'Rreal';
$lang->order->amount         = 'Amount';
$lang->order->status         = 'Status';
$lang->order->createdBy      = 'Created By';
$lang->order->createdDate    = 'Created On';
$lang->order->assignedTo     = 'Assignee';
$lang->order->assignedBy     = 'Assigned By';
$lang->order->assignedDate   = 'Assigned On';
$lang->order->signedBy       = 'Signed By';
$lang->order->signedDate     = 'Signed On';
$lang->order->payedDate      = 'Paid On';
$lang->order->closedBy       = 'Closed By';
$lang->order->closedDate     = 'Closed On';
$lang->order->closedReason   = 'Closed Reason';
$lang->order->closedNote     = 'Closed Note';
$lang->order->activatedBy    = 'Activated By';
$lang->order->activatedDate  = 'Activated On';
$lang->order->contactedBy    = 'Contacted By';
$lang->order->contactedDate  = 'Contacted On';
$lang->order->nextDate       = 'Next Contact';
$lang->order->editedBy       = 'Last Edited By';
$lang->order->editedDate     = 'Last Edited On';
$lang->order->createCustomer = 'Create Customer';
$lang->order->createProduct  = 'Create';

$lang->order->list          = 'Orders';
$lang->order->browse        = 'Orders';
$lang->order->create        = 'Create';
$lang->order->record        = 'Record';
$lang->order->edit          = 'Edit';
$lang->order->delete        = 'Delete';
$lang->order->view          = 'View';
$lang->order->close         = 'Close';
$lang->order->sign          = 'Sign';
$lang->order->assign        = 'Assign';
$lang->order->activate      = 'Activate';
$lang->order->export        = 'Export';

$lang->order->statusList['normal'] = 'Normal';
$lang->order->statusList['signed'] = 'Signed';
$lang->order->statusList['closed'] = 'Closed';

$lang->order->closedReasonList['']          = '';
$lang->order->closedReasonList['payed']     = 'Paid';
$lang->order->closedReasonList['failed']    = 'Failed';
$lang->order->closedReasonList['postponed'] = 'Postponed';

$lang->order->titleLBL  = "%s purchases %s (%s)";
$lang->order->basicInfo = "Basic";
$lang->order->lifetime  = "Order Life";

$lang->order->totalAmount   = 'The Budget is %s, the Actual is %s on this page;';
$lang->order->infoBuy       = "%s purchases %s.";
$lang->order->infoContract  = 'Signed Contract: %s.';
$lang->order->infoAmount    = 'Budget is %s, Actual is %s.';
$lang->order->infoContacted = 'Contacted on %s. ';
$lang->order->infoNextDate  = 'Next Contact on %s.';
$lang->order->deny          = 'You has no Privilege to create %s.';
