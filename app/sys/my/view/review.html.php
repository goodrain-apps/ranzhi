<?php
/**
 * The personal view file of attend module of Ranzhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     attend
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include './header.html.php';?>
<?php js::set('type', $type)?>
<?php js::set('confirmReview', $lang->attend->confirmReview);?>
<?php if($type == 'attend'):?>
<div class='panel'>
  <table class='table table-hover table-striped table-sorter table-data table-fixed text-center'>
    <thead>
      <tr class='text-center'>
        <th class='w-50px'><?php echo $lang->attend->id;?></th>
        <th class='w-100px'><?php echo $lang->attend->account;?></th>
        <th class='w-100px'><?php echo $lang->attend->date;?></th>
        <th class='w-100px'><?php echo $lang->attend->status;?></th>
        <th class='w-80px'><?php echo $lang->attend->manualIn;?></th>
        <th class='w-80px'><?php echo $lang->attend->manualOut;?></th>
        <th class='w-100px'><?php echo $lang->attend->reason;?></th>
        <th><?php echo $lang->attend->desc;?></th>
        <th class='w-150px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <?php foreach($attends as $attend):?>
    <tr>
      <td><?php echo $attend->id;?></td>
      <td><?php echo zget($users, $attend->account);?></td>
      <td><?php echo $attend->date?></td>
      <td><?php echo zget($lang->attend->statusList, $attend->status)?></td>
      <td><?php echo substr($attend->manualIn, 0, 5)?></td>
      <td><?php echo substr($attend->manualOut, 0, 5)?></td>
      <td><?php echo zget($lang->attend->reasonList, $attend->reason)?></td>
      <td><?php echo $attend->desc?></td>
      <td>
        <?php echo html::a($this->createLink('oa.attend', 'review', "attendID={$attend->id}&status=pass"), $lang->attend->reviewStatusList['pass'], "data-status='pass' data-toggle='ajax'")?>
        <?php echo html::a($this->createLink('oa.attend', 'review', "attendID={$attend->id}&status=reject"), $lang->attend->reviewStatusList['reject'], "data-status='reject' data-toggle='ajax'")?>
      </td>
    </tr>
    <?php endforeach;?>
  </table>
</div>
<?php endif;?>
<?php if($type == 'leave'):?>
<div class='panel'>
  <table class='table table-data table-hover text-center table-fixed tablesorter' id='leaveTable'>
    <thead>
      <tr class='text-center'>
        <?php $vars = "type={$type}&orderBy=%s";?>
        <th class='w-80px'><?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->leave->id);?></th>
        <th class='w-80px'><?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->leave->createdBy);?></th>
        <th class='w-80px'><?php echo $lang->user->dept;?></th>
        <th class='w-80px'><?php commonModel::printOrderLink('type', $orderBy, $vars, $lang->leave->type);?></th>
        <th class='w-150px'><?php commonModel::printOrderLink('begin', $orderBy, $vars, $lang->leave->begin);?></th>
        <th class='w-150px'><?php commonModel::printOrderLink('begin', $orderBy, $vars, $lang->leave->end);?></th>
        <th><?php echo $lang->leave->desc;?></th>
        <th class='w-80px'><?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->leave->status);?></th>
        <th class='w-80px'><?php commonModel::printOrderLink('reviewedBy', $orderBy, $vars, $lang->leave->reviewedBy);?></th>
        <th class='w-150px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <?php foreach($leaveList as $leave):?>
    <tr>
      <td><?php echo $leave->id;?></td>
      <td><?php echo zget($users, $leave->createdBy);?></td>
      <td><?php echo zget($deptList, $leave->dept);?></td>
      <td><?php echo zget($this->lang->leave->typeList, $leave->type);?></td>
      <td><?php echo $leave->begin . ' ' . $leave->start;?></td>
      <td><?php echo $leave->end . ' ' . $leave->finish;?></td>
      <td title='<?php echo $leave->desc?>'><?php echo $leave->desc;?></td>
      <td class='leave-<?php echo $leave->status?>'><?php echo zget($this->lang->leave->statusList, $leave->status);?></td>
      <td><?php echo zget($users, $leave->reviewedBy);?></td>
      <td>
        <?php echo html::a($this->createLink('oa.leave', 'review', "id=$leave->id&status=pass"), $lang->leave->statusList['pass'], "data-status='pass' data-toggle='ajax'");?>
        <?php echo html::a($this->createLink('oa.leave', 'review', "id=$leave->id&status=reject"), $lang->leave->statusList['reject'], "data-status='reject' data-toggle='ajax'");?>
      </td>
    </tr>
    <?php endforeach;?>
  </table>
</div>
<?php endif;?>
<?php if($type == 'refund'):?>
<div class='panel'>
  <table class='table table-hover table-striped table-sorter table-data table-fixed text-center'>
    <thead>
      <tr class='text-center'>
        <th class='w-50px'><?php echo $lang->refund->id;?></th>
        <th class='w-150px'><?php echo $lang->refund->name;?></th>
        <th class='w-100px'><?php echo $lang->refund->category;?></th>
        <th class='w-100px'><?php echo $lang->refund->createdBy;?></th>
        <th class='w-100px'><?php echo $lang->refund->money;?></th>
        <th class='w-100px'><?php echo $lang->refund->date;?></th>
        <th class='w-100px'><?php echo $lang->refund->status;?></th>
        <th><?php echo $lang->refund->desc;?></th>
        <th class='w-80px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <?php foreach($refunds as $refund):?>
    <tr>
      <td><?php echo $refund->id;?></td>
      <td class='text-left'><?php echo $refund->name;?></td>
      <td><?php echo $categories[$refund->category];?></td>
      <td><?php echo zget($users, $refund->createdBy);?></td>
      <td class='text-right'><?php echo zget($currencySign, $refund->currency) . $refund->money;?></td>
      <td><?php echo $refund->date;?></td>
      <td><?php echo zget($lang->refund->statusList, $refund->status);?></td>
      <td><?php echo $refund->desc?></td>
      <td><?php echo html::a($this->createLink('oa.refund', 'review', "refundID={$refund->id}"), $lang->refund->review, "data-toggle='modal'")?></td>
    </tr>
    <?php endforeach;?>
  </table>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
