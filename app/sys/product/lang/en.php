<?php
/**
 * The product module en file of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
if(!isset($lang->product)) $lang->product = new stdclass();
$lang->product->common      = 'Product';
$lang->product->id          = 'ID';
$lang->product->name        = 'Name';
$lang->product->code        = 'Alias';
$lang->product->type        = 'Type';
$lang->product->status      = 'Status';
$lang->product->line        = 'Product Line';
$lang->product->desc        = 'Introductions';
$lang->product->roles       = 'Roles';
$lang->product->createdBy   = 'Created By';
$lang->product->createdDate = 'Created on';
$lang->product->editedBy    = 'Edited by';
$lang->product->editedDate  = 'Edited on';

$lang->product->index       = 'Products';
$lang->product->delete      = 'Delete';
$lang->product->list        = 'List';
$lang->product->browse      = 'Browse';
$lang->product->create      = 'Add a Product';
$lang->product->edit        = 'Edit';
$lang->product->view        = 'View';
$lang->product->basicInfo   = 'Basic Information';
$lang->product->setline     = 'Settings';

$lang->product->typeList['real']    = 'Physical';
$lang->product->typeList['service'] = 'Service';
$lang->product->typeList['virtual'] = 'Virtual';

$lang->product->statusList['developing'] = 'Developing';
$lang->product->statusList['normal']     = 'Normal';
$lang->product->statusList['offline']    = 'Offline';

$lang->product->lineList['default'] = '';

$lang->product->placeholder = new stdclass();
$lang->product->placeholder->code = 'Product alias should be letters, digits or underline.';
