<?php
/**
 * The detail view file of leave module of RanZhi.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     leave 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<table class='table table-bordered'>
  <tr>
    <th><?php echo $lang->leave->status;?></th>
    <td class='leave-<?php echo $leave->status;?>'><?php echo $lang->leave->statusList[$leave->status];?></td>
    <th><?php echo $lang->leave->type;?></th>
    <td><?php echo zget($lang->leave->typeList, $leave->type);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->leave->start;?></th>
    <td><?php echo formatTime($leave->begin . ' ' . $leave->start);?></td>
    <th><?php echo $lang->leave->finish;?></th>
    <td><?php echo formatTime($leave->end . ' ' . $leave->finish);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->leave->hours;?></th>
    <td><?php echo $leave->hours;?></td>
    <th><?php echo $lang->leave->backDate;?></th>
    <td><?php echo formatTime($leave->backDate);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->leave->desc;?></th>
    <td colspan='3'><?php echo $leave->desc;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->leave->createdBy;?></th>
    <td><?php echo zget($users, $leave->createdBy);?></th>
    <th><?php echo $lang->leave->reviewedBy;?></th>
    <td><?php echo zget($users, $leave->reviewedBy);?></th>
  </tr>
  <tr>
    <th><?php echo $lang->leave->createdDate;?></th>
    <td><?php echo formatTime($leave->createdDate);?></td>
    <th><?php echo $lang->leave->reviewedDate;?></th>
    <td><?php echo formatTime($leave->reviewedDate);?></td>
  </tr>
</table>
<?php echo $this->fetch('action', 'history', "objectType=leave&objectID=$leave->id");?>
<div class='page-actions'>
  <?php if($type == 'browseReview' and $leave->status == 'wait'):?>
  <?php echo html::a($this->createLink('oa.leave', 'edit', "id={$leave->id}"), $lang->edit, "class='btn loadInModal'");?>
  <?php echo html::a($this->createLink('oa.leave', 'review', "id={$leave->id}&status=pass"), $lang->leave->statusList['pass'], "class='btn reviewPass'");?>
  <?php echo html::a($this->createLink('oa.leave', 'review', "id={$leave->id}&status=reject"), $lang->leave->statusList['reject'], "class='btn reviewReject'");?>
  <?php endif;?>

  <?php if($type == 'personal' and ($leave->status == 'wait' or $leave->status == 'draft')):?>
  <?php if($leave->status == 'wait' or $leave->status == 'draft') echo html::a($this->createLink('oa.leave', 'switchstatus', "id={$leave->id}"), $leave->status == 'wait' ? $lang->leave->cancel : $lang->leave->commit, "class='btn'");?>
  <div class='btn-group'>
    <?php echo html::a($this->createLink('oa.leave', 'edit', "id={$leave->id}"), $lang->edit, "class='btn loadInModal'");?>
    <?php echo html::a($this->createLink('oa.leave', 'delete', "id={$leave->id}"), $lang->delete, "class='btn deleteLeave'");?>
  </div>
  <?php endif;?>

  <?php if($type == 'browseReview' and $leave->status == 'pass' and $leave->backDate != '0000-00-00 00:00:00' and $leave->backDate != "$leave->end $leave->finish"):?>
  <?php echo html::a($this->createLink('oa.leave', 'review', "id={$leave->id}&status=back"), $lang->leave->statusList['pass'] . $lang->leave->back, "class='btn reviewPass'");?>
  <?php endif;?>

  <?php if($type == 'personal' and $leave->status == 'pass' and date('Y-m-d H:i:s') < "$leave->end $leave->finish" && $leave->backDate != "$leave->end $leave->finish") echo html::a($this->createLink('oa.leave', 'back', "id={$leave->id}"), $lang->leave->back, "class='btn loadInModal'");?>
  <?php echo html::a('###', $lang->goback, "class='btn' data-dismiss='modal'");?>
</div>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
