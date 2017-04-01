<?php
/**
 * The customer module en file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     customer
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
if(!isset($lang->customer)) $lang->customer = new stdclass();

$lang->customer->common        = 'Customer';
$lang->customer->id            = 'ID';
$lang->customer->name          = 'Name';
$lang->customer->contact       = 'Contact';
$lang->customer->depositor     = 'Account';
$lang->customer->type          = 'Type';
$lang->customer->size          = 'Size';
$lang->customer->industry      = 'Industry';
$lang->customer->area          = 'Area';
$lang->customer->status        = 'Status';
$lang->customer->level         = 'Class';
$lang->customer->intension     = 'Intention';
$lang->customer->phone         = 'Phone';
$lang->customer->email         = 'Email';
$lang->customer->qq            = 'QQ';
$lang->customer->site          = 'Site';
$lang->customer->weibo         = 'Sina Weibo';
$lang->customer->weixin        = 'Wechat';
$lang->customer->desc          = 'Description';
$lang->customer->public        = 'Public';
$lang->customer->relation      = 'Relation';
$lang->customer->createdBy     = 'Created By';
$lang->customer->createdDate   = 'Created On';
$lang->customer->editedBy      = 'Edited By';
$lang->customer->editedDate    = 'Edited On';
$lang->customer->assignedTo    = 'Assigned to';
$lang->customer->assignedBy    = 'Assigned By';
$lang->customer->assignedDate  = 'Assigned On';
$lang->customer->contactBy     = 'Contact By';
$lang->customer->contactedDate = 'Contact On';
$lang->customer->nextDate      = 'Next Contact';
$lang->customer->selectContact = 'Select Contact';

$lang->customer->browse      = 'Browse Customer';
$lang->customer->view        = 'View';
$lang->customer->create      = 'Add a Customer';
$lang->customer->delete      = 'Delete Customer';
$lang->customer->order       = 'Orders';
$lang->customer->contact     = 'Contact';
$lang->customer->contract    = 'Contracts';
$lang->customer->address     = 'Addresses';
$lang->customer->record      = 'Record';
$lang->customer->assign      = 'Assign Customer';
$lang->customer->batchAssign = 'Batch Assign';
$lang->customer->linkContact = 'Add a Contact';
$lang->customer->list        = 'Customers';
$lang->customer->edit        = 'Edit';
$lang->customer->export      = 'Export';
$lang->customer->merge       = 'Merge';
$lang->customer->basicInfo   = 'Basic Info';
$lang->customer->moreInfo    = 'More Info';

$lang->customer->typeList['']            = '';
$lang->customer->typeList['national']    = 'National';
$lang->customer->typeList['collective']  = 'Collective';
$lang->customer->typeList['corporate']   = 'Corporate';
$lang->customer->typeList['limited']     = 'Limited';
$lang->customer->typeList['partnership'] = 'Partnership';
$lang->customer->typeList['foreign']     = 'Foreign';
$lang->customer->typeList['personal']    = 'Personal';

$lang->customer->statusList['potential'] = 'Potential';
$lang->customer->statusList['intension'] = 'Intended';
$lang->customer->statusList['signed']    = 'Signed';
$lang->customer->statusList['payed']     = 'Paid';
$lang->customer->statusList['failed']    = 'Failed';

$lang->customer->sizeNameList[0] = '';
$lang->customer->sizeNameList[1] = 'Large';
$lang->customer->sizeNameList[2] = 'Medium';
$lang->customer->sizeNameList[3] = 'Small';
$lang->customer->sizeNameList[4] = 'Mini';

$lang->customer->sizeNoteList[0] = '';
$lang->customer->sizeNoteList[1] = ' > 100 employees';
$lang->customer->sizeNoteList[2] = ' 50-100 employees';
$lang->customer->sizeNoteList[3] = ' 10-50 employees';
$lang->customer->sizeNoteList[4] = ' < 10 employees';

$lang->customer->levelNameList[]    = '';
$lang->customer->levelNameList['A'] = 'A';
$lang->customer->levelNameList['B'] = 'B';
$lang->customer->levelNameList['C'] = 'C';
$lang->customer->levelNameList['D'] = 'D';
$lang->customer->levelNameList['E'] = 'E';

$lang->customer->levelNoteList[]    = '';
$lang->customer->levelNoteList['A'] = ' Make a deal in a month.';
$lang->customer->levelNoteList['B'] = ' Make a deal in 3 months';
$lang->customer->levelNoteList['C'] = ' Make a deal in 6 months.';
$lang->customer->levelNoteList['D'] = ' Make a deal after six months.';
$lang->customer->levelNoteList['E'] = ' Make no deal.';

$lang->customer->relationList['client']   = 'Client';
$lang->customer->relationList['provider'] = 'Provider';
$lang->customer->relationList['partner']  = 'Partner';

$lang->customer->mergeTip = 'Merge this customer to selected customer.';
