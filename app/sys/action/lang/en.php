<?php
/**
 * The action module English file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     action
 * @version     $Id: zh-cn.php 4955 2013-07-02 01:47:21Z chencongzhi520@gmail.com $
 * @link        http://www.ranzhico.com
 */
if(!isset($lang->action)) $lang->action = new stdclass();

$lang->action->common   = 'Logs';
$lang->action->product  = 'Product';
$lang->action->actor    = 'Account';
$lang->action->contact  = 'Contact';
$lang->action->comment  = 'Comment';
$lang->action->action   = 'Action';
$lang->action->actionID = 'Action ID';
$lang->action->date     = 'Date';
$lang->action->nextDate = 'Next Contact';

$lang->action->trash      = 'Trash';
$lang->action->objectType = 'Type';
$lang->action->objectID   = 'ID';
$lang->action->objectName = 'Details';

$lang->action->createContact = 'Create Contact';
$lang->action->editComment   = 'Edit Comment';
$lang->action->hide          = 'Hide';       
$lang->action->hideOne       = 'Hide';
$lang->action->hideAll       = 'Hide all';
$lang->action->hidden        = 'Hidden';
$lang->action->undelete      = 'Undelete';
$lang->action->trashTips     = 'Tips:The deletions in Ranger are tag deletions.';

$lang->action->textDiff = 'Text Mode';
$lang->action->original = 'Original content';

/* The desc of actions. */
$lang->action->desc = new stdclass();
$lang->action->desc->common                = '$date, <strong>$action</strong> by <strong>$actor</strong>.';
$lang->action->desc->extra                 = '$date, <strong>$action</strong> as <strong>$extra</strong> by <strong>$actor</strong>.';
$lang->action->desc->opened                = '$date, opened by <strong>$actor</strong>.';
$lang->action->desc->created               = '$date, created by <strong>$actor</strong>.';
$lang->action->desc->edited                = '$date, edited by <strong>$actor</strong>.';
$lang->action->desc->assigned              = '$date, <strong>$actor</strong> assigned task to <strong>$extra</strong>.' . "\n";
$lang->action->desc->merged                = '$date, <strong>$actor</strong> merged customer <strong>$extra</strong>。' . "\n";
$lang->action->desc->transmit              = '$date, <strong>$actor</strong> transfer task to <strong>$extra</strong>.' . "\n";
$lang->action->desc->closed                = '$date, closed by <strong>$actor</strong>, close resion:<strong>$extra</strong>.';
$lang->action->desc->deleted               = '$date, deleted by <strong>$actor</strong>.';
$lang->action->desc->deletedfile           = '$date, deleted file by <strong>$actor</strong>, the file is <strong><i>$extra</i></strong>.';
$lang->action->desc->editfile              = '$date, edit file by <strong>$actor</strong>, the file is <strong><i>$extra</i></strong>.';
$lang->action->desc->erased                = '$date, erased by <strong>$actor</strong>.';
$lang->action->desc->commented             = '$date, commented by <strong>$actor</strong>.';
$lang->action->desc->activated             = '$date, activated by <strong>$actor</strong>.';
$lang->action->desc->moved                 = '$date, moved by <strong>$actor</strong>, previouse is "$extra".';
$lang->action->desc->started               = '$date, started by <strong>$actor</strong>.';
$lang->action->desc->delayed               = '$date, delayed by <strong>$actor</strong>.';
$lang->action->desc->suspended             = '$date, suspended by <strong>$actor</strong>.';
$lang->action->desc->canceled              = '$date, canceled by <strong>$actor</strong>.';
$lang->action->desc->finished              = '$date, finished by <strong>$actor</strong>.';
$lang->action->desc->replied               = '$date, replied by <strong>$actor</strong>.' . "\n";
$lang->action->desc->doubted               = '$date, doubted by <strong>$actor</strong>.' . "\n";
$lang->action->desc->transfered            = '$date, transfered by <strong>$actor</strong>.' . "\n";
$lang->action->desc->reviewed              = '$date, reviewed by <strong>$actor</strong>.' . "\n";
$lang->action->desc->reimburse             = '$date, reimbursed $extra by <strong>$actor</strong>.' . "\n";
$lang->action->desc->revoked               = '$date, revoked by <strong>$actor</strong>.' . "\n";
$lang->action->desc->commited              = '$date, commited by <strong>$actor</strong>.' . "\n";
$lang->action->desc->returned              = '$date, received payments $extra by <strong>$actor</strong>.' . "\n";
$lang->action->desc->editreturned          = '$date, edited payment by <strong>$actor</strong>.' . "\n";
$lang->action->desc->deletereturned        = '$date, deleted $extra by <strong>$actor</strong>.' . "\n";
$lang->action->desc->finishreturned        = '$date, received payments $extra by <strong>$actor</strong>, complete payments' . "\n";
$lang->action->desc->delivered             = '$date, delivered by <strong>$actor</strong>.' . "\n";
$lang->action->desc->editdelivered         = '$date, edited delivery by <strong>$actor</strong>.' . "\n";
$lang->action->desc->deletedelivered       = '$date, deleted delivery $extra by <strong>$actor</strong>.' . "\n";
$lang->action->desc->finishdelivered       = '$date, completed delivery by <strong>$actor</strong>.' . "\n";
$lang->action->desc->createdresume         = '$date, created resume by <strong>$actor</strong>, the resume is： <strong>$extra</strong>.' . "\n";
$lang->action->desc->editedresume          = '$date, edited resume by <strong>$actor</strong>.' . "\n";
$lang->action->desc->deleteresume          = '$date, deleted resume by <strong>$actor</strong>, the resume is： <strong>$extra</strong>.' . "\n";
$lang->action->desc->createaddress         = '$date, created address by <strong>$actor</strong>, the address is： <strong>$extra</strong>.' . "\n";
$lang->action->desc->editaddress           = '$date, edited address by <strong>$actor</strong>.' . "\n";
$lang->action->desc->deleteaddress         = '$date, deleted address by <strong>$actor</strong>, the address is： <strong>$extra</strong>.' . "\n";
$lang->action->desc->diff1                 = 'changed <strong><i>%s</i></strong>, old is "%s", new is "%s".<br />';
$lang->action->desc->diff2                 = 'changed <strong><i>%s</i></strong>, the diff is:' . "\n" . "<blockquote>%s</blockquote>" . "\n<div class='hidden'>%s</div>";
$lang->action->desc->diff3                 = "changed file's name %s to %s.";
$lang->action->desc->record                = '$date, <strong>$actor</strong> created log, the contact is:<strong>$contact</strong>, the date is:$extra.' . "\n";
$lang->action->desc->signed                = '$date, signed by <strong>$actor</strong>，the turnover is： <strong>$extra</strong>.' . "\n";
$lang->action->desc->linkcontact           = '$date, <strong>$actor</strong> added contacts for customer： <strong>$extra</strong>.' . "\n";
$lang->action->desc->createorder           = '$date, <strong>$actor</strong> created order:<strong>$extra</strong>.' . "\n";
$lang->action->desc->editorder             = '$date, <strong>$actor</strong> edited order:<strong>$extra</strong>.' . "\n";
$lang->action->desc->assignorder           = '$date, <strong>$actor</strong> assigned order:<strong>$extra</strong>.' . "\n";
$lang->action->desc->closeorder            = '$date, <strong>$actor</strong> closed order:<strong>$extra</strong>.' . "\n";
$lang->action->desc->activateorder         = '$date, <strong>$actor</strong> activated order:<strong>$extra</strong>.' . "\n";
$lang->action->desc->createcontract        = '$date, <strong>$actor</strong> created contract:<strong>$extra</strong>.' . "\n";
$lang->action->desc->editcontract          = '$date, <strong>$actor</strong> edited contract:<strong>$extra</strong>.' . "\n";
$lang->action->desc->delivercontract       = '$date, <strong>$actor</strong> delivered of <strong>$extra</strong>.' . "\n";
$lang->action->desc->receivecontract       = '$date, $extra by <strong>$actor</strong>.' . "\n";
$lang->action->desc->finishdelivercontract = '$date, <strong>$actor</strong> completed delivery of <strong>$extra</strong>.' . "\n";
$lang->action->desc->finishreceivecontract = '$date, $extra by <strong>$actor</strong>, complete payments.' . "\n";
$lang->action->desc->finishcontract        = '$date, <strong>$actor</strong> finished contract:<strong>$extra</strong>.' . "\n";
$lang->action->desc->cancelcontract        = '$date, <strong>$actor</strong> canceled contract:<strong>$extra</strong>.' . "\n";
$lang->action->desc->hidden                = '$date, hidden by <strong>$actor</strong> .' . "\n";
$lang->action->desc->undeleted             = '$date, restored by <strong>$actor</strong> .' . "\n";
$lang->action->desc->transform             = '$date, transformed by <strong>$actor</strong> .' . "\n";
$lang->action->desc->ignored               = '$date, ignored by <strong>$actor</strong> .' . "\n";
$lang->action->desc->createtrip            = '$date, <strong>$actor</strong> created trip:<strong>$extra</strong>.' . "\n";
$lang->action->desc->createegress          = '$date, <strong>$actor</strong> created egress:<strong>$extra</strong>.' . "\n";

/* The action labels. */
$lang->action->label = new stdclass();
$lang->action->label->created     = 'created';
$lang->action->label->edited      = 'edited';
$lang->action->label->assigned    = 'assigned';
$lang->action->label->transmit    = 'transfer';
$lang->action->label->closed      = 'closed';
$lang->action->label->deleted     = 'deleted';
$lang->action->label->erased      = 'deleted';
$lang->action->label->undeleted   = 'Restore';
$lang->action->label->deletedfile = 'deleted file';
$lang->action->label->editfile    = 'edit file name';
$lang->action->label->commented   = 'commented';
$lang->action->label->activated   = 'activated';
$lang->action->label->resolved    = 'resolved';
$lang->action->label->reviewed    = 'reviewed';
$lang->action->label->moved       = 'moved';
$lang->action->label->marked      = 'edited';
$lang->action->label->started     = 'started';
$lang->action->label->canceled    = 'cancelled';
$lang->action->label->finished    = 'finished';
$lang->action->label->reimbursed  = 'reimbursed';
$lang->action->label->record      = 'record';
$lang->action->label->signed      = 'signed';
$lang->action->label->commited    = 'commited';
$lang->action->label->revoked     = 'revoked';
$lang->action->label->forbidden   = 'Forbidden';
$lang->action->label->transform   = 'transformed';
$lang->action->label->ignore      = 'ignored';
$lang->action->label->login       = 'login';
$lang->action->label->logout      = "logout";

$lang->action->label->createdbalance        = 'create balance';
$lang->action->label->createorder           = 'create order';
$lang->action->label->editorder             = 'edit order';
$lang->action->label->activateorder         = 'activate order';
$lang->action->label->closeorder            = 'close order';
$lang->action->label->linkcontact           = 'link contact';
$lang->action->label->createcontract        = 'create contract';
$lang->action->label->editcontract          = 'edit contract';
$lang->action->label->cancelcontract        = 'cancel contract';
$lang->action->label->finishcontract        = 'finish contract';
$lang->action->label->createdresume         = 'create resume';
$lang->action->label->editedresume          = 'edit resume';
$lang->action->label->deleteresume          = 'delete resume';
$lang->action->label->createaddress         = 'create address';
$lang->action->label->editaddress           = 'edit address';
$lang->action->label->deleteaddress         = 'delete address';
$lang->action->label->finishdelivered       = 'finish delivered';
$lang->action->label->finishdelivercontract = 'finish delivered';
$lang->action->label->delivered             = 'deliver';
$lang->action->label->delivercontract       = 'deliver';
$lang->action->label->returned              = 'return';
$lang->action->label->receivecontract       = 'return';
$lang->action->label->finishreceivecontract = 'finish returned';
$lang->action->label->finishreturned        = 'finish returned';
$lang->action->label->deletereturned        = 'delete rerurned';

/* Display action when search in dynamic view. */
$lang->action->search = new stdclass();
$lang->action->search->label = (array)$lang->action->label;

/* Link of every action. */
$lang->action->label->announce  = 'Announce|announce|view|announceID=%s';
$lang->action->label->balance   = 'Balance|balance|browse|depositorID=%s';
$lang->action->label->doc       = 'Document|doc|view|docID=%s';
$lang->action->label->doclib    = 'Document Library|doc|browse|doclibID=%s';
$lang->action->label->contact   = 'Contact|contact|view|contactID=%s';
$lang->action->label->contract  = 'Contract|contract|view|contractID=%s';
$lang->action->label->customer  = 'Customer|customer|view|customerID=%s';
$lang->action->label->depositor = 'Account|depositor|browse|';
$lang->action->label->holiday   = 'Holiday|hiloday|browse|';
$lang->action->label->order     = 'Order|order|view|orderID=%s';
$lang->action->label->product   = 'Product|product|view|productID=%s';
$lang->action->label->project   = 'Project|task|browse|projectID=%s';
$lang->action->label->provider  = 'Supplier|provider|view|providerID=%s';
$lang->action->label->schema    = 'Trade Template|schema|browse|';
$lang->action->label->space     = ' ';
$lang->action->label->task      = 'Task|task|view|taskID=%s';
$lang->action->label->todo      = 'To-Do|todo|calendar|';
$lang->action->label->trade     = 'Trade|trade|browse|';

$lang->action->label->attend = array();
$lang->action->label->attend['commited'] = 'attend review|attend|browsereview|';
$lang->action->label->attend['reviewed'] = 'attend review|attend|personal|';
$lang->action->label->leave = array();
$lang->action->label->leave['created']  = 'leave review|leave|browsereview|';
$lang->action->label->leave['commited'] = 'leave review|leave|browsereview|';
$lang->action->label->leave['revoked']  = 'leave review|leave|browsereview|';
$lang->action->label->leave['reviewed'] = 'leave review|leave|personal|';
$lang->action->label->lieu = array();
$lang->action->label->lieu['created']  = 'lieu review|lieu|browsereview|';
$lang->action->label->lieu['commited'] = 'lieu review|lieu|browsereview|';
$lang->action->label->lieu['revoked']  = 'lieu review|lieu|browsereview|';
$lang->action->label->lieu['reviewed'] = 'lieu review|lieu|personal|';
$lang->action->label->makeup = array();
$lang->action->label->makeup['created']  = 'makeup review|makeup|browsereview|';
$lang->action->label->makeup['commited'] = 'makeup review|makeup|browsereview|';
$lang->action->label->makeup['revoked']  = 'makeup review|makeup|browsereview|';
$lang->action->label->makeup['reviewed'] = 'makeup review|makeup|personal|';
$lang->action->label->overtime = array();
$lang->action->label->overtime['created']  = 'overtime review|overtime|browsereview|';
$lang->action->label->overtime['commited'] = 'overtime review|overtime|browsereview|';
$lang->action->label->overtime['revoked']  = 'overtime review|overtime|browsereview|';
$lang->action->label->overtime['reviewed'] = 'overtime review|overtime|personal|';
$lang->action->label->refund = array();
$lang->action->label->refund['commited']    = 'refund review|refund|browsereview|';
$lang->action->label->refund['revoked']     = 'refund review|refund|browsereview|';
$lang->action->label->refund['created']     = 'refund review|refund|view|refundID=%s';
$lang->action->label->refund['edited']      = 'refund review|refund|view|refundID=%s';
$lang->action->label->refund['reviewed']    = 'refund review|refund|view|refundID=%s';
$lang->action->label->refund['reimburse']   = 'refund review|refund|view|refundID=%s';
$lang->action->label->refund['deletedfile'] = 'refund review|refund|view|refundID=%s';
$lang->action->label->user = array();
$lang->action->label->user['login']  = 'login|user|login|';
$lang->action->label->user['logout'] = 'logout|user|logout|';

$lang->action->nextContactList[1]      = 'tomorrow';
$lang->action->nextContactList[2]      = 'in 2 days';
$lang->action->nextContactList[3]      = 'in 3 days';
$lang->action->nextContactList[7]      = 'in 1 week';
$lang->action->nextContactList[14]     = 'in 2 weeks';
$lang->action->nextContactList[365000] = 'no contact';

$lang->action->record = new stdclass();
$lang->action->record->common     = 'Communication';
$lang->action->record->create     = 'Add Record';
$lang->action->record->edit       = 'Edit Record';
$lang->action->record->history    = 'Communication History';
$lang->action->record->customer   = 'Customer';
$lang->action->record->provider   = 'Supplier';
$lang->action->record->contract   = 'Contract';
$lang->action->record->order      = 'Order';
$lang->action->record->contact    = 'Contact';
$lang->action->record->actor      = 'Actor';
$lang->action->record->comment    = 'Content';
$lang->action->record->date       = 'Contact on';
$lang->action->record->file       = 'Files';
$lang->action->record->nextDate   = 'Next Contact';
$lang->action->record->uploadFile = 'Upload';

$lang->action->objectTypes['order']     = 'Order';
$lang->action->objectTypes['customer']  = 'Customer';
$lang->action->objectTypes['provider']  = 'Supplier';
$lang->action->objectTypes['doc']       = 'Doc';
$lang->action->objectTypes['task']      = 'Task';
$lang->action->objectTypes['product']   = 'Product';
$lang->action->objectTypes['contact']   = 'Contact';
$lang->action->objectTypes['contract']  = 'Contract';
$lang->action->objectTypes['project']   = 'Project';
$lang->action->objectTypes['user']      = 'User';
$lang->action->objectTypes['resume']    = 'Resume';
$lang->action->objectTypes['leave']     = 'Leave';
$lang->action->objectTypes['lieu']      = 'Lieu';
$lang->action->objectTypes['makeup']    = 'Makeup';
$lang->action->objectTypes['overtime']  = 'Overtime';
$lang->action->objectTypes['refund']    = 'Reimburse';
$lang->action->objectTypes['depositor'] = 'Account';
$lang->action->objectTypes['balance']   = 'Balance';
$lang->action->objectTypes['todo']      = 'Todo';
$lang->action->objectTypes['announce']  = 'Announce';
$lang->action->objectTypes['holiday']   = 'Holiday';
$lang->action->objectTypes['trade']     = 'Trade';
$lang->action->objectTypes['schema']    = 'Trade Template';
$lang->action->objectTypes['doclib']    = 'Document library';
$lang->action->objectTypes['action']    = 'Communication record';

$lang->action->noticeTitle = "%s <a href='%s' data-appid='%s'>%s</a>";
