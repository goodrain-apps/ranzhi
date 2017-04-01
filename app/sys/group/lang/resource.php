<?php
/**
 * The all avaliabe actions in RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */

/* App module group. */
$lang->appModule = new stdclass();

$lang->appModule->crm = array();
$lang->appModule->crm[] = 'order';
$lang->appModule->crm[] = 'contract';
$lang->appModule->crm[] = 'customer';
$lang->appModule->crm[] = 'provider';
$lang->appModule->crm[] = 'contact';
$lang->appModule->crm[] = 'leads';
$lang->appModule->crm[] = 'product';
$lang->appModule->crm[] = 'address';
$lang->appModule->crm[] = 'resume';
$lang->appModule->crm[] = 'sales';

$lang->appModule->cash = array();
$lang->appModule->cash[] = 'trade';
$lang->appModule->cash[] = 'depositor';
$lang->appModule->cash[] = 'balance';
$lang->appModule->cash[] = 'provider';
$lang->appModule->cash[] = 'schema';

$lang->appModule->oa = array();
$lang->appModule->oa[] = 'announce';
$lang->appModule->oa[] = 'attend';
$lang->appModule->oa[] = 'holiday';
$lang->appModule->oa[] = 'leave';
$lang->appModule->oa[] = 'makeup';
$lang->appModule->oa[] = 'overtime';
$lang->appModule->oa[] = 'lieu';
$lang->appModule->oa[] = 'trip';
$lang->appModule->oa[] = 'egress';
$lang->appModule->oa[] = 'refund';

$lang->appModule->doc = array();
$lang->appModule->doc[] = 'doc';

$lang->appModule->proj = array();
$lang->appModule->proj[] = 'task';

$lang->appModule->team = array();
$lang->appModule->team[] = 'blog';
$lang->appModule->team[] = 'forum';
$lang->appModule->team[] = 'thread';
$lang->appModule->team[] = 'user';
$lang->appModule->team[] = 'company';

$lang->appModule->superadmin = array();
$lang->appModule->superadmin[] = 'adminUser';

$lang->appModule->sys = array();
$lang->appModule->sys[] = 'tree';
$lang->appModule->sys[] = 'setting';
$lang->appModule->sys[] = 'report';
$lang->appModule->sys[] = 'my';
$lang->appModule->sys[] = 'file';

/* Module order. */
$lang->moduleOrder[0]   = 'order';
$lang->moduleOrder[5]   = 'contract';
$lang->moduleOrder[10]  = 'customer';
$lang->moduleOrder[13]  = 'provider';
$lang->moduleOrder[15]  = 'contact';
$lang->moduleOrder[16]  = 'leads';
$lang->moduleOrder[20]  = 'product';
$lang->moduleOrder[25]  = 'address';
$lang->moduleOrder[27]  = 'resume';

$lang->moduleOrder[30]  = 'trade';
$lang->moduleOrder[35]  = 'depositor';
$lang->moduleOrder[40]  = 'balance';
$lang->moduleOrder[42]  = 'provider';
$lang->moduleOrder[43]  = 'schema';

$lang->moduleOrder[50]  = 'announce';
$lang->moduleOrder[57]  = 'task';
$lang->moduleOrder[70]  = 'attend';
$lang->moduleOrder[75]  = 'holiday';
$lang->moduleOrder[80]  = 'leave';
$lang->moduleOrder[81]  = 'makeup';
$lang->moduleOrder[82]  = 'overtime';
$lang->moduleOrder[83]  = 'lieu';
$lang->moduleOrder[85]  = 'trip';
$lang->moduleOrder[90]  = 'egress';
$lang->moduleOrder[91]  = 'refund';

$lang->moduleOrder[93]  = 'doc';

$lang->moduleOrder[95]  = 'blog';
$lang->moduleOrder[100] = 'forum';
$lang->moduleOrder[105] = 'thread';
$lang->moduleOrder[110] = 'user';
$lang->moduleOrder[115] = 'company';

$lang->moduleOrder[120] = 'adminUser';

$lang->moduleOrder[125] = 'tree';
$lang->moduleOrder[130] = 'setting';
$lang->moduleOrder[135] = 'report';
$lang->moduleOrder[140] = 'my';
$lang->moduleOrder[145] = 'file';

$lang->resource = new stdclass();

/* Order module. */
$lang->resource->order = new stdclass();
$lang->resource->order->browse   = 'browse';
$lang->resource->order->create   = 'create';
$lang->resource->order->edit     = 'edit';
$lang->resource->order->view     = 'view';
$lang->resource->order->close    = 'close';
$lang->resource->order->activate = 'activate';
$lang->resource->order->contact  = 'contact';
$lang->resource->order->assign   = 'assign';
$lang->resource->order->export   = 'export';
$lang->resource->order->delete   = 'delete';

$lang->order->methodOrder[5]  = 'browse';
$lang->order->methodOrder[10] = 'create';
$lang->order->methodOrder[15] = 'edit';
$lang->order->methodOrder[20] = 'view';
$lang->order->methodOrder[25] = 'close';
$lang->order->methodOrder[30] = 'activate';
$lang->order->methodOrder[40] = 'contact';
$lang->order->methodOrder[45] = 'assign';
$lang->order->methodOrder[50] = 'export';
$lang->order->methodOrder[55] = 'delete';

/* Contract. */
$lang->resource->contract = new stdclass();
$lang->resource->contract->browse         = 'browse';
$lang->resource->contract->create         = 'create';
$lang->resource->contract->edit           = 'edit';
$lang->resource->contract->view           = 'view';
$lang->resource->contract->cancel         = 'cancel';
$lang->resource->contract->finish         = 'finish';
$lang->resource->contract->delete         = 'delete';
$lang->resource->contract->delivery       = 'delivery';
$lang->resource->contract->editDelivery   = 'editDelivery';
$lang->resource->contract->deleteDelivery = 'deleteDelivery';
$lang->resource->contract->receive        = 'receive';
$lang->resource->contract->editReturn     = 'editReturn';
$lang->resource->contract->deleteReturn   = 'deleteReturn';
$lang->resource->contract->export         = 'export';

$lang->contract->methodOrder[5]  = 'browse';
$lang->contract->methodOrder[10] = 'create';
$lang->contract->methodOrder[15] = 'edit';
$lang->contract->methodOrder[20] = 'view';
$lang->contract->methodOrder[25] = 'cancel';
$lang->contract->methodOrder[30] = 'finish';
$lang->contract->methodOrder[35] = 'delete';
$lang->contract->methodOrder[40] = 'delivery';
$lang->contract->methodOrder[45] = 'editDelivery';
$lang->contract->methodOrder[50] = 'deleteDelivery';
$lang->contract->methodOrder[55] = 'receive';
$lang->contract->methodOrder[60] = 'editReturn';
$lang->contract->methodOrder[65] = 'deleteReturn';
$lang->contract->methodOrder[70] = 'export';

/* Customer. */
$lang->resource->customer = new stdclass();
$lang->resource->customer->browse      = 'browse';
$lang->resource->customer->create      = 'create';
$lang->resource->customer->edit        = 'edit';
$lang->resource->customer->view        = 'view';
$lang->resource->customer->assign      = 'assign';
$lang->resource->customer->order       = 'order';
$lang->resource->customer->contact     = 'contact';
$lang->resource->customer->linkContact = 'linkContact';
$lang->resource->customer->contract    = 'contract';
$lang->resource->customer->export      = 'export';
$lang->resource->customer->delete      = 'delete';
$lang->resource->customer->batchAssign = 'batchAssign';
$lang->resource->customer->merge       = 'merge';

$lang->customer->methodOrder[5]  = 'browse';
$lang->customer->methodOrder[15] = 'create';
$lang->customer->methodOrder[20] = 'edit';
$lang->customer->methodOrder[25] = 'view';
$lang->customer->methodOrder[30] = 'order';
$lang->customer->methodOrder[35] = 'contact';
$lang->customer->methodOrder[40] = 'linkContact';
$lang->customer->methodOrder[45] = 'contract';
$lang->customer->methodOrder[50] = 'export';
$lang->customer->methodOrder[55] = 'delete';
$lang->customer->methodOrder[60] = 'assign';
$lang->customer->methodOrder[65] = 'batchAssign';
$lang->customer->methodOrder[70] = 'merge';

/* Contact. */
$lang->resource->contact = new stdclass();
$lang->resource->contact->browse         = 'browse';
$lang->resource->contact->create         = 'create';
$lang->resource->contact->edit           = 'edit';
$lang->resource->contact->view           = 'view';
$lang->resource->contact->export         = 'export';
$lang->resource->contact->delete         = 'delete';
$lang->resource->contact->vcard          = 'vcard';
$lang->resource->contact->import         = 'import';
$lang->resource->contact->showImport     = 'showImport';
$lang->resource->contact->exportTemplate = 'exportTemplate';

$lang->contact->methodOrder[10] = 'browse';
$lang->contact->methodOrder[15] = 'create';
$lang->contact->methodOrder[20] = 'edit';
$lang->contact->methodOrder[25] = 'view';
$lang->contact->methodOrder[30] = 'export';
$lang->contact->methodOrder[35] = 'delete';
$lang->contact->methodOrder[40] = 'vcard';
$lang->contact->methodOrder[45] = 'import';
$lang->contact->methodOrder[50] = 'showImport';
$lang->contact->methodOrder[55] = 'exportTemplate';

$lang->resource->leads = new stdclass();
$lang->resource->leads->browse    = 'browse';
$lang->resource->leads->create    = 'create';
$lang->resource->leads->edit      = 'edit';
$lang->resource->leads->delete    = 'delete';
$lang->resource->leads->view      = 'view';
$lang->resource->leads->assign    = 'assign';
$lang->resource->leads->apply     = 'apply';
$lang->resource->leads->transform = 'transform';
$lang->resource->leads->ignore    = 'ignore';
$lang->resource->leads->setting   = 'settings';

$lang->leads->methodOrder[10] = 'browse';
$lang->leads->methodOrder[11] = 'create';
$lang->leads->methodOrder[15] = 'edit';
$lang->leads->methodOrder[16] = 'delete';
$lang->leads->methodOrder[20] = 'view';
$lang->leads->methodOrder[25] = 'assign';
$lang->leads->methodOrder[30] = 'apply';
$lang->leads->methodOrder[35] = 'transform';
$lang->leads->methodOrder[40] = 'ignore';
$lang->leads->methodOrder[45] = 'setting';

/* Product. */
$lang->resource->product = new stdclass();
$lang->resource->product->browse = 'browse';
$lang->resource->product->create = 'create';
$lang->resource->product->edit   = 'edit';
$lang->resource->product->delete = 'delete';
$lang->resource->product->view   = 'view';

$lang->product->methodOrder[5]  = 'browse';
$lang->product->methodOrder[10] = 'create';
$lang->product->methodOrder[20] = 'edit';
$lang->product->methodOrder[35] = 'delete';
$lang->product->methodOrder[40] = 'view';

/* Address. */
$lang->resource->address = new stdclass();
$lang->resource->address->browse = 'browse';
$lang->resource->address->create = 'create';
$lang->resource->address->edit   = 'edit';
$lang->resource->address->delete = 'delete';

$lang->address->methodOrder[5]  = 'browse';
$lang->address->methodOrder[10] = 'create';
$lang->address->methodOrder[15] = 'edit';
$lang->address->methodOrder[20] = 'delete';

/* Resume. */
$lang->resource->resume = new stdclass();
$lang->resource->resume->browse = 'browse';
$lang->resource->resume->create = 'create';
$lang->resource->resume->edit   = 'edit';
$lang->resource->resume->delete = 'delete';
$lang->resource->resume->leave  = 'leave';

$lang->resume->methodOrder[5]  = 'browse';
$lang->resume->methodOrder[10] = 'create';
$lang->resume->methodOrder[15] = 'edit';
$lang->resume->methodOrder[20] = 'delete';
$lang->resume->methodOrder[25] = 'leave';

/* Sales group. */
$lang->resource->sales = new stdclass();
$lang->resource->sales->admin  = 'admin';
$lang->resource->sales->browse = 'browse';
$lang->resource->sales->create = 'create';
$lang->resource->sales->edit   = 'edit';
$lang->resource->sales->delete = 'delete';

$lang->sales->methodOrder[5]  = 'admin';
$lang->sales->methodOrder[10] = 'browse';
$lang->sales->methodOrder[15] = 'create';
$lang->sales->methodOrder[20] = 'edit';
$lang->sales->methodOrder[25] = 'delete';

/* Product plan. */
$lang->resource->trade = new stdclass();
$lang->resource->trade->browse        = 'browse';
$lang->resource->trade->view          = 'view';
$lang->resource->trade->create        = 'create';
$lang->resource->trade->batchCreate   = 'batchCreate';
$lang->resource->trade->batchEdit     = 'batchEdit';
$lang->resource->trade->edit          = 'edit';
$lang->resource->trade->transfer      = 'transfer';
$lang->resource->trade->invest        = 'invest';
$lang->resource->trade->loan          = 'loan';
$lang->resource->trade->detail        = 'detail';
$lang->resource->trade->delete        = 'delete';
$lang->resource->trade->import        = 'import';
$lang->resource->trade->showImport    = 'showImport';
$lang->resource->trade->export        = 'export';
$lang->resource->trade->report        = 'report';
$lang->resource->trade->compare       = 'compare';
$lang->resource->trade->export2Excel  = 'export2Excel';
$lang->resource->trade->setReportUnit = 'setReportUnit';

$lang->trade->methodOrder[10] = 'browse';
$lang->trade->methodOrder[11] = 'view';
$lang->trade->methodOrder[15] = 'create';
$lang->trade->methodOrder[20] = 'batchCreate';
$lang->trade->methodOrder[21] = 'batchEdit';
$lang->trade->methodOrder[25] = 'edit';
$lang->trade->methodOrder[30] = 'transfer';
$lang->trade->methodOrder[31] = 'invest';
$lang->trade->methodOrder[32] = 'loan';
$lang->trade->methodOrder[35] = 'detail';
$lang->trade->methodOrder[40] = 'delete';
$lang->trade->methodOrder[45] = 'import';
$lang->trade->methodOrder[50] = 'showImport';
$lang->trade->methodOrder[55] = 'export';
$lang->trade->methodOrder[60] = 'report';
$lang->trade->methodOrder[61] = 'compare';
$lang->trade->methodOrder[65] = 'export2Excel';
$lang->trade->methodOrder[70] = 'setReportUnit';

/* Depositor. */
$lang->resource->depositor = new stdclass();
$lang->resource->depositor->browse      = 'browse';
$lang->resource->depositor->create      = 'create';
$lang->resource->depositor->edit        = 'edit';
$lang->resource->depositor->forbid      = 'forbid';
$lang->resource->depositor->activate    = 'activate';
$lang->resource->depositor->check       = 'check';
$lang->resource->depositor->export      = 'export';
$lang->resource->depositor->savebalance = 'saveBalance';
$lang->resource->depositor->delete      = 'delete';

$lang->depositor->methodOrder[5]  = 'browse';
$lang->depositor->methodOrder[10] = 'create';
$lang->depositor->methodOrder[15] = 'edit';
$lang->depositor->methodOrder[20] = 'forbid';
$lang->depositor->methodOrder[25] = 'activate';
$lang->depositor->methodOrder[30] = 'check';
$lang->depositor->methodOrder[35] = 'export';
$lang->depositor->methodOrder[40] = 'saveBalance';
$lang->depositor->methodOrder[45] = 'delete';

/* Balance. */
$lang->resource->balance = new stdclass();
$lang->resource->balance->browse = 'browse';
$lang->resource->balance->create = 'create';
$lang->resource->balance->edit   = 'edit';
$lang->resource->balance->delete = 'delete';

$lang->balance->methodOrder[5]  = 'browse';
$lang->balance->methodOrder[10] = 'create';
$lang->balance->methodOrder[15] = 'edit';
$lang->balance->methodOrder[20] = 'delete';

/* Provider. */
$lang->resource->provider = new stdclass();
$lang->resource->provider->browse      = 'browse';
$lang->resource->provider->create      = 'create';
$lang->resource->provider->edit        = 'edit';
$lang->resource->provider->view        = 'view';
$lang->resource->provider->delete      = 'delete';
$lang->resource->provider->contact     = 'contact';
$lang->resource->provider->linkContact = 'linkContact';

$lang->provider->methodOrder[5]  = 'browse';
$lang->provider->methodOrder[10] = 'create';
$lang->provider->methodOrder[15] = 'edit';
$lang->provider->methodOrder[20] = 'view';
$lang->provider->methodOrder[25] = 'delete';
$lang->provider->methodOrder[30] = 'contact';
$lang->provider->methodOrder[35] = 'linkContact';

/* Schema. */
$lang->resource->schema = new stdclass();
$lang->resource->schema->browse = 'browse';
$lang->resource->schema->view   = 'view';
$lang->resource->schema->create = 'create';
$lang->resource->schema->edit   = 'edit';
$lang->resource->schema->delete = 'delete';

$lang->schema->methodOrder[5]  = 'browse';
$lang->schema->methodOrder[10] = 'create';
$lang->schema->methodOrder[15] = 'edit';
$lang->schema->methodOrder[20] = 'view';
$lang->schema->methodOrder[25] = 'delete';

/* Task. */
$lang->resource->task = new stdclass();
$lang->resource->task->viewAll   = 'viewAll';
$lang->resource->task->editAll   = 'editAll';
$lang->resource->task->deleteAll = 'deleteAll';

$lang->task->methodOrder[5]  = 'viewAll';
$lang->task->methodOrder[10] = 'editAll';
$lang->task->methodOrder[15] = 'deleteAll';

/* Announce. */
$lang->resource->announce = new stdclass();
$lang->resource->announce->browse = 'browse';
$lang->resource->announce->view   = 'view';
$lang->resource->announce->create = 'create';
$lang->resource->announce->edit   = 'edit';
$lang->resource->announce->delete = 'delete';

$lang->announce->methodOrder[5]  = 'browse';
$lang->announce->methodOrder[10] = 'view';
$lang->announce->methodOrder[15] = 'create';
$lang->announce->methodOrder[20] = 'edit';
$lang->announce->methodOrder[25] = 'delete';

/* Doc. */
$lang->resource->doc = new stdclass();
$lang->resource->doc->createLib   = 'createLib';
$lang->resource->doc->editLib     = 'editLib';
$lang->resource->doc->deleteLib   = 'deleteLib';
$lang->resource->doc->index       = 'index';
$lang->resource->doc->browse      = 'browse';
$lang->resource->doc->allLibs     = 'allLibs';
$lang->resource->doc->projectLibs = 'projectLibs';
$lang->resource->doc->showFiles   = 'showFiles';
$lang->resource->doc->create      = 'create';
$lang->resource->doc->edit        = 'edit';
$lang->resource->doc->view        = 'view';
$lang->resource->doc->delete      = 'delete';
$lang->resource->doc->sort        = 'sort';

$lang->doc->methodOrder[0]  = 'createLib';
$lang->doc->methodOrder[5]  = 'editLib';
$lang->doc->methodOrder[10] = 'deleteLib';
$lang->doc->methodOrder[15] = 'index';
$lang->doc->methodOrder[20] = 'browse';
$lang->doc->methodOrder[25] = 'allLibs';
$lang->doc->methodOrder[30] = 'projectLibs';
$lang->doc->methodOrder[35] = 'showFiles';
$lang->doc->methodOrder[40] = 'create';
$lang->doc->methodOrder[45] = 'edit';
$lang->doc->methodOrder[50] = 'view';
$lang->doc->methodOrder[55] = 'delete';
$lang->doc->methodOrder[60] = 'sort';

/* Attend */
$lang->resource->attend = new stdclass();
$lang->resource->attend->department       = 'department';
$lang->resource->attend->company          = 'company';
$lang->resource->attend->browseReview     = 'browseReview';
$lang->resource->attend->review           = 'review';
$lang->resource->attend->export           = 'export';
$lang->resource->attend->stat             = 'stat';
$lang->resource->attend->saveStat         = 'saveStat';
$lang->resource->attend->exportStat       = 'exportStat';
$lang->resource->attend->detail           = 'detail';
$lang->resource->attend->exportDetail     = 'exportDetail';
$lang->resource->attend->settings         = 'settings';
$lang->resource->attend->personalSettings = 'personalSettings';
$lang->resource->attend->setManager       = 'setManager';

$lang->attend->methodOrder[5]  = 'department';
$lang->attend->methodOrder[10] = 'company';
$lang->attend->methodOrder[15] = 'browseReview';
$lang->attend->methodOrder[20] = 'review';
$lang->attend->methodOrder[25] = 'export';
$lang->attend->methodOrder[30] = 'stat';
$lang->attend->methodOrder[35] = 'saveStat';
$lang->attend->methodOrder[40] = 'exportStat';
$lang->attend->methodOrder[45] = 'detail';
$lang->attend->methodOrder[60] = 'exportDetail';
$lang->attend->methodOrder[65] = 'settings';
$lang->attend->methodOrder[70] = 'personalSettings';
$lang->attend->methodOrder[75] = 'setManager';

/* Holiday */
$lang->resource->holiday = new stdclass();
$lang->resource->holiday->create = 'create';
$lang->resource->holiday->edit   = 'edit';
$lang->resource->holiday->delete = 'delete';

$lang->holiday->methodOrder[0]  = 'create';
$lang->holiday->methodOrder[5]  = 'edit';
$lang->holiday->methodOrder[10] = 'delete';

/* Leave */
$lang->resource->leave = new stdclass();
$lang->resource->leave->browseReview = 'browseReview';
$lang->resource->leave->company      = 'company';
$lang->resource->leave->review       = 'review';
$lang->resource->leave->export       = 'export';
$lang->resource->leave->setReviewer  = 'setReviewer';

$lang->leave->methodOrder[0]  = 'browseReview';
$lang->leave->methodOrder[5]  = 'company';
$lang->leave->methodOrder[10] = 'review';
$lang->leave->methodOrder[15] = 'export';
$lang->leave->methodOrder[20] = 'setReviewer';

/* Overtime */
$lang->resource->makeup = new stdclass();
$lang->resource->makeup->browseReview = 'browseReview';
$lang->resource->makeup->company      = 'company';
$lang->resource->makeup->review       = 'review';
$lang->resource->makeup->export       = 'export';
$lang->resource->makeup->setReviewer  = 'setReviewer';

$lang->makeup->methodOrder[0]  = 'browseReview';
$lang->makeup->methodOrder[5]  = 'company';
$lang->makeup->methodOrder[10] = 'review';
$lang->makeup->methodOrder[15] = 'export';
$lang->makeup->methodOrder[15] = 'setReviewer';

/* Overtime */
$lang->resource->overtime = new stdclass();
$lang->resource->overtime->browseReview = 'browseReview';
$lang->resource->overtime->company      = 'company';
$lang->resource->overtime->review       = 'review';
$lang->resource->overtime->export       = 'export';
$lang->resource->overtime->setReviewer  = 'setReviewer';

$lang->overtime->methodOrder[0]  = 'browseReview';
$lang->overtime->methodOrder[5]  = 'company';
$lang->overtime->methodOrder[10] = 'review';
$lang->overtime->methodOrder[15] = 'export';
$lang->overtime->methodOrder[20] = 'setReviewer';

/* Lieu */
$lang->resource->lieu = new stdclass();
$lang->resource->lieu->browseReview = 'browseReview';
$lang->resource->lieu->company      = 'company';
$lang->resource->lieu->review       = 'review';
$lang->resource->lieu->setReviewer  = 'setReviewer';

$lang->lieu->methodOrder[0]  = 'browseReview';
$lang->lieu->methodOrder[5]  = 'company';
$lang->lieu->methodOrder[10] = 'review';
$lang->lieu->methodOrder[15] = 'setReviewer';

/* Trip */
$lang->resource->trip = new stdclass();
$lang->resource->trip->department = 'department';
$lang->resource->trip->company    = 'company';

$lang->trip->methodOrder[0]  = 'department';
$lang->trip->methodOrder[5]  = 'company';

/* Trip */
$lang->resource->egress = new stdclass();
$lang->resource->egress->department = 'department';
$lang->resource->egress->company    = 'company';

$lang->egress->methodOrder[0]  = 'department';
$lang->egress->methodOrder[5]  = 'company';

/* Refund */
$lang->resource->refund = new stdclass();
$lang->resource->refund->company      = 'company';
$lang->resource->refund->todo         = 'todo';
$lang->resource->refund->browseReview = 'browseReview';
$lang->resource->refund->review       = 'review';
$lang->resource->refund->reimburse    = 'reimburse';
$lang->resource->refund->setReviewer  = 'setReviewer';
$lang->resource->refund->setCategory  = 'setCategory';
$lang->resource->refund->setDepositor = 'setDepositor';
$lang->resource->refund->setRefundBy  = 'setRefundBy';
$lang->resource->refund->export       = 'export';

$lang->refund->methodOrder[10] = 'company';
$lang->refund->methodOrder[15] = 'todo';
$lang->refund->methodOrder[20] = 'browseReview';
$lang->refund->methodOrder[25] = 'review';
$lang->refund->methodOrder[30] = 'reimburse';
$lang->refund->methodOrder[35] = 'setReviewer';
$lang->refund->methodOrder[40] = 'setCategory';
$lang->refund->methodOrder[45] = 'setDepositor';
$lang->refund->methodOrder[50] = 'setRefundBy';
$lang->refund->methodOrder[55] = 'export';

/* Blog. */
$lang->resource->blog = new stdclass();
$lang->resource->blog->index  = 'index';
$lang->resource->blog->create = 'create';
$lang->resource->blog->edit   = 'edit';
$lang->resource->blog->view   = 'view';
$lang->resource->blog->delete = 'delete';

$lang->blog->methodOrder[0]   = 'index';
$lang->blog->methodOrder[5]   = 'create';
$lang->blog->methodOrder[10]  = 'edit';
$lang->blog->methodOrder[15]  = 'view';
$lang->blog->methodOrder[20]  = 'delete';

/* Forum. */
$lang->resource->forum = new stdclass();
$lang->resource->forum->index  = 'index';
$lang->resource->forum->board  = 'board';
$lang->resource->forum->admin  = 'admin';
$lang->resource->forum->update = 'update';

$lang->forum->methodOrder[0]  = 'index';
$lang->forum->methodOrder[5]  = 'board';
$lang->forum->methodOrder[10] = 'admin';
$lang->forum->methodOrder[15] = 'update';

/* Thread. */
$lang->resource->thread = new stdclass();
$lang->resource->thread->post         = 'post';
$lang->resource->thread->edit         = 'edit';
$lang->resource->thread->view         = 'view';
$lang->resource->thread->transfer     = 'transfer';
$lang->resource->thread->delete       = 'delete';
$lang->resource->thread->switchStatus = 'switchStatus';
$lang->resource->thread->stick        = 'stick';
$lang->resource->thread->deleteFile   = 'deleteFile';

$lang->thread->methodOrder[0]  = 'post';
$lang->thread->methodOrder[5]  = 'edit';
$lang->thread->methodOrder[10] = 'view';
$lang->thread->methodOrder[15] = 'transfer';
$lang->thread->methodOrder[20] = 'delete';
$lang->thread->methodOrder[25] = 'switchStatus';
$lang->thread->methodOrder[30] = 'stick';
$lang->thread->methodOrder[35] = 'deleteFile';

$lang->resource->user = new stdclass();
$lang->resource->user->colleague = 'colleague';

$lang->user->methodOrder[5] = 'colleague';

$lang->resource->company = new stdclass();
$lang->resource->company->index = 'index';

$lang->company->methodOrder[10] = 'index';

/* Tree. */
$lang->resource->tree = new stdclass();
$lang->resource->tree->browse   = 'browse';
$lang->resource->tree->edit     = 'edit';
$lang->resource->tree->children = 'children';
$lang->resource->tree->delete   = 'delete';
$lang->resource->tree->merge    = 'merge';

$lang->tree->methodOrder[0]  = 'browse';
$lang->tree->methodOrder[5]  = 'edit';
$lang->tree->methodOrder[10] = 'children';
$lang->tree->methodOrder[15] = 'delete';
$lang->tree->methodOrder[20] = 'merge';

/* File. */
$lang->resource->file = new stdclass();
$lang->resource->file->upload   = 'upload';
$lang->resource->file->download = 'download';
$lang->resource->file->edit     = 'edit';
$lang->resource->file->delete   = 'delete';

$lang->file->methodOrder[0]  = 'upload';
$lang->file->methodOrder[5]  = 'download';
$lang->file->methodOrder[10] = 'edit';
$lang->file->methodOrder[15] = 'delete';

/* Setting. */
$lang->resource->setting = new stdclass();
$lang->resource->setting->lang         = 'lang';
$lang->resource->setting->reset        = 'reset';
$lang->resource->setting->customerPool = 'customerPool';
$lang->resource->setting->modules      = 'modules';

$lang->setting->methodOrder[5]  = 'lang';
$lang->setting->methodOrder[10] = 'reset';
$lang->setting->methodOrder[15] = 'customerPool';
$lang->setting->methodOrder[20] = 'modules';

/* Report. */
$lang->resource->report = new stdclass();
$lang->resource->report->browse = 'browse';

$lang->report->methodOrder[5] = 'browse';

/* My. */
$lang->resource->my = new stdclass();
$lang->resource->my->company = 'company';

$lang->my->methodOrder[5] = 'company';

/* User. */
$lang->resource->adminUser = new stdclass();
$lang->resource->adminUser->admin  = 'admin';
$lang->resource->adminUser->create = 'create';
$lang->resource->adminUser->edit   = 'edit';
$lang->resource->adminUser->delete = 'delete';
$lang->resource->adminUser->forbid = 'forbid';

$lang->adminUser->methodOrder[10]  = 'admin';
$lang->adminUser->methodOrder[15] = 'create';
$lang->adminUser->methodOrder[20] = 'edit';
$lang->adminUser->methodOrder[25] = 'delete';
$lang->adminUser->methodOrder[30] = 'forbid';

/* Every version of new privilege. */
$lang->changelog = array();
//$lang->changelog['1.1'][]   = 'search-saveQuery';
