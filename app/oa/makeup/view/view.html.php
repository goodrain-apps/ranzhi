<?php
/**
 * The view file of makeup module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     makeup
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<?php js::set('confirmReview', $lang->makeup->confirmReview)?>
<table class='table table-bordered'>
  <tr>
    <th class='w-80px'><?php echo $lang->makeup->status;?></th>
    <td class='text-warning'><?php echo $lang->makeup->statusList[$makeup->status];?></td>
    <th class='w-80px'><?php echo $lang->makeup->type?></th>
    <td><?php echo zget($lang->makeup->typeList, $makeup->type);?></td>
  </tr> 
  <tr>
    <th><?php echo $lang->makeup->begin?></th>
    <td><?php echo $makeup->begin . ' ' . $makeup->start;?></td>
    <th><?php echo $lang->makeup->end?></th>
    <td><?php echo $makeup->end . ' ' . $makeup->finish;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->makeup->hours?></th>
    <td colspan='3'><?php echo $makeup->hours . $lang->makeup->hoursTip;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->makeup->desc?></th>
    <td colspan='3'><?php echo $makeup->desc;?></td>
  </tr> 
  <?php if($makeup->status == 'reject' and $makeup->rejectReason):?>
  <tr>
    <th><?php echo $lang->makeup->rejectReason;?></th>
    <td colspan='3'><?php echo $makeup->rejectReason;?></td>
  </tr>
  <?php endif;?>
  <tr>
    <th><?php echo $lang->makeup->createdBy;?></th>
    <td><?php echo zget($users, $makeup->createdBy);?></td>
    <th><?php echo $lang->makeup->reviewedBy;?></th>
    <td><?php echo zget($users, $makeup->reviewedBy);?></td>
  </tr> 
  <tr>
    <th><?php echo $lang->makeup->createdDate;?></th>
    <td><?php echo $makeup->createdDate;?></td>
    <th><?php echo $lang->makeup->reviewedDate;?></th>
    <td><?php echo $makeup->reviewedDate;?></td>
  </tr> 
</table>
<?php echo $this->fetch('action', 'history', "objectType=makeup&objectID=$makeup->id");?>
<div class='page-actions'>
  <?php
  if($type == 'browseReview' and $makeup->status == 'wait')
  {
      commonModel::printLink('oa.makeup', 'review', "id=$makeup->id&status=pass", $lang->makeup->statusList['pass'], "class='reviewPass btn'");
      commonModel::printLink('oa.makeup', 'review', "id=$makeup->id&status=reject", $lang->makeup->statusList['reject'], "class='reviewReject btn'");
  }

  if($type == 'personal' and ($makeup->status == 'wait' or $makeup->status == 'draft'))
  {
      if($makeup->status == 'wait' or $makeup->status == 'draft') commonModel::printLink('oa.makeup', 'switchstatus', "id=$makeup->id", $makeup->status == 'wait' ? $lang->makeup->cancel : $lang->makeup->commit, "class='switch-status btn'");
      echo "<div class='btn-group'>";
      commonModel::printLink('oa.makeup', 'edit', "id=$makeup->id", $lang->edit, "class='btn loadInModal'");
      commonModel::printLink('oa.makeup', 'delete', "id=$makeup->id", $lang->delete, "class='btn deleteMakeup'");
      echo '</div>';
  }

  echo html::a('#', $lang->goback, "class='btn' data-dismiss='modal'");
  ?>
</div>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
