<?php
/**
 * The depositor module en file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     depositor
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
if(!isset($lang->depositor)) $lang->depositor = new stdclass();
$lang->depositor->common          = 'Account';
$lang->depositor->id              = 'ID';
$lang->depositor->abbr            = 'Abbreviation';
$lang->depositor->serviceProvider = 'Provider';
$lang->depositor->bankProvider    = 'Account Branch';
$lang->depositor->title           = 'Title';
$lang->depositor->tags            = 'Tags';
$lang->depositor->account         = 'Account';
$lang->depositor->bankcode        = 'Account Number';
$lang->depositor->public          = 'Public';
$lang->depositor->type            = 'Type';
$lang->depositor->currency        = 'Currency';
$lang->depositor->status          = 'Status';
$lang->depositor->createdBy       = 'Created By';
$lang->depositor->createdDate     = 'Created Date';
$lang->depositor->editedBy        = 'Edited By';
$lang->depositor->editedDate      = 'Edited Date';

$lang->depositor->all         = 'All';
$lang->depositor->create      = 'Create';
$lang->depositor->browse      = 'Account';
$lang->depositor->edit        = 'Edit';
$lang->depositor->delete      = 'Delete';
$lang->depositor->view        = 'View';
$lang->depositor->forbid      = 'Disable';
$lang->depositor->activate    = 'Activate';
$lang->depositor->export      = 'Export';
$lang->depositor->balance     = 'Balance';
$lang->depositor->saveBalance = 'Save';
$lang->depositor->detail      = 'Detail';

$lang->depositor->check         = 'Check';
$lang->depositor->start         = 'Begin';
$lang->depositor->end           = 'End';
$lang->depositor->originValue   = 'Initial';
$lang->depositor->actualValue   = 'Real';
$lang->depositor->computedValue = 'Computed';
$lang->depositor->result        = 'Result';
$lang->depositor->success       = "<span class='text-success'>Ok</span>";
$lang->depositor->more          = "<span class='text-danger'>%s</span>";
$lang->depositor->less          = "<span class='text-danger'>%s</span>";

$lang->depositor->createBalance = 'Please add balance first.';

$lang->depositor->typeList['cash']   = 'Cash';
$lang->depositor->typeList['bank']   = 'Debit';
$lang->depositor->typeList['online'] = 'Electronic';

$lang->depositor->publicList['1'] = 'Public';
$lang->depositor->publicList['0'] = 'Personal';

$lang->depositor->providerList['']       = '';
$lang->depositor->providerList['alipay'] = 'Alipay';
$lang->depositor->providerList['paypal'] = 'Paypal';
$lang->depositor->providerList['tenpay'] = 'Tenpay';
$lang->depositor->providerList['wechat'] = 'Wechat Pay';

$lang->depositor->statusList['normal']  = 'Normal';
$lang->depositor->statusList['disable'] = 'Disable';

$lang->depositor->placeholder = new stdclass();
$lang->depositor->placeholder->tags     = 'Please divide tags with commas';
$lang->depositor->placeholder->noBccomp = 'Please install bccmom extension first.';
