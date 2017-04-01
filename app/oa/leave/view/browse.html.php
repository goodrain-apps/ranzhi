<?php
/**
 * The browse view file of leave module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     leave
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../../sys/common/view/treeview.html.php';?>
<?php js::set('confirmReview', $lang->leave->confirmReview)?>
<div id='menuActions'>
  <?php commonModel::printLink('leave', 'export', "mode=all&orderBy={$orderBy}", $lang->exportIcon . $lang->export, "class='btn btn-primary iframe' data-width='700'");?>
  <?php commonModel::printLink('leave', 'create', "", "<i class='icon icon-plus'></i> {$lang->leave->create}", "data-toggle='modal' class='btn btn-primary'")?>
</div>
<div class='with-side'>
  <div class='side'>
    <div class='panel panel-sm'>
      <div class='panel-body'>
        <ul class='tree' data-collapsed='true'>
          <?php foreach($yearList as $year):?>
          <li class='<?php echo $year == $currentYear ? 'active' : ''?>'>
            <?php commonModel::printLink('leave', $type, "date=$year", $year);?>
            <ul>
              <?php foreach($monthList[$year] as $month):?>
              <li class='<?php echo ($year == $currentYear and $month == $currentMonth) ? 'active' : ''?>'>
                <?php commonModel::printLink('leave', $type, "date=$year$month", $year . $month);?>
              </li>
              <?php endforeach;?>
            </ul>
          </li>
          <?php endforeach;?>
        </ul>
      </div>
    </div>
  </div>
  <div class='main'>
    <div class='panel'>
      <table class='table table-data table-hover text-center table-fixed tablesorter' id='leaveTable'>
        <thead>
          <tr class='text-center'>
            <?php $vars = "&date={$date}&orderBy=%s";?>
            <th class='w-50px'><?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->leave->id);?></th>
            <th class='w-70px'><?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->leave->createdBy);?></th>
            <th class='w-70px visible-lg'><?php echo $lang->user->dept;?></th>
            <th class='w-60px'><?php commonModel::printOrderLink('type', $orderBy, $vars, $lang->leave->type);?></th>
            <th class='w-140px'><?php commonModel::printOrderLink('begin', $orderBy, $vars, $lang->leave->start);?></th>
            <th class='w-140px'><?php commonModel::printOrderLink('end', $orderBy, $vars, $lang->leave->finish);?></th>
            <th class='w-140px'><?php commonModel::printOrderLink('backDate', $orderBy, $vars, $lang->leave->backDate);?></th>
            <th class='w-60px visible-lg'><?php commonModel::printOrderLink('hours', $orderBy, $vars, $lang->leave->hours);?></th>
            <th><?php echo $lang->leave->desc;?></th>
            <th class='w-70px'><?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->leave->status);?></th>
            <?php if($type != 'browseReview'):?>
            <th class='w-80px'><?php commonModel::printOrderLink('reviewedBy', $orderBy, $vars, $lang->leave->reviewedBy);?></th>
            <?php endif;?>
            <?php if($type == 'company'):?>
            <th class='w-50px'><?php echo $lang->actions;?></th>
            <?php else:?>
            <th class='w-130px'><?php echo $lang->actions;?></th>
            <?php endif;?>
          </tr>
        </thead>
        <?php foreach($leaveList as $leave):?>
        <tr>
          <td><?php echo $leave->id;?></td>
          <td><?php echo zget($users, $leave->createdBy);?></td>
          <td class='visible-lg'><?php echo zget($deptList, $leave->dept);?></td>
          <td><?php echo zget($this->lang->leave->typeList, $leave->type);?></td>
          <td><?php echo $leave->begin . ' ' . $leave->start;?></td>
          <td><?php echo $leave->end . ' ' . $leave->finish;?></td>
          <td><?php echo formatTime($leave->backDate);?></td>
          <td class='visible-lg'><?php echo $leave->hours == 0 ? '' : $leave->hours;?></td>
          <td title='<?php echo $leave->desc;?>'><?php echo $leave->desc;?></td>
          <?php $status = ($leave->status == 'pass' and $leave->backDate != '0000-00-00 00:00:00' and $leave->backDate != $leave->end . ' ' . $leave->finish) ? 'back' : $leave->status;?>
          <td class='leave-<?php echo $status?>'><?php echo zget($this->lang->leave->statusList, $status);?></td>
          <?php if($type != 'browseReview'):?>
          <td><?php echo zget($users, $leave->reviewedBy);?></td>
          <?php endif;?>
          <td class='actionTD'>
            <?php echo html::a($this->createLink('oa.leave', 'view', "id={$leave->id}&type=$type"), $lang->detail, "data-toggle='modal'");?>
            <?php if($type == 'browseReview' and $leave->status == 'wait'):?>
            <?php echo html::a($this->createLink('oa.leave', 'edit', "id={$leave->id}"), $lang->edit, "data-toggle='modal'");?>
            <?php echo html::a($this->createLink('oa.leave', 'review', "id={$leave->id}&status=pass"), $lang->leave->statusList['pass'], "class='reviewPass'");?>
            <?php echo html::a($this->createLink('oa.leave', 'review', "id={$leave->id}&status=reject"), $lang->leave->statusList['reject'], "class='reviewReject'");?>
            <?php endif;?>

            <?php if($type == 'personal' and ($leave->status == 'wait' or $leave->status == 'draft')):?>
            <?php if($leave->status == 'wait' or $leave->status == 'draft') echo html::a($this->createLink('oa.leave', 'switchstatus', "id={$leave->id}"), $leave->status == 'wait' ? $lang->leave->cancel : $lang->leave->commit, "class='reload'");?>
            <?php echo html::a($this->createLink('oa.leave', 'edit', "id={$leave->id}"), $lang->edit, "data-toggle='modal'");?>
            <?php echo html::a($this->createLink('oa.leave', 'delete', "id={$leave->id}"), $lang->delete, "class='deleter'");?>
            <?php endif;?>

            <?php if($type == 'browseReview' and $leave->status == 'pass' and $leave->backDate != '0000-00-00 00:00:00' and $leave->backDate != "$leave->end $leave->finish"):?>
            <?php echo html::a($this->createLink('oa.leave', 'review', "id={$leave->id}&status=back"), $lang->leave->statusList['pass'] . $lang->leave->back, "class='reviewPass'");?>
            <?php endif;?>

            <?php if($type == 'personal' and $leave->status == 'pass' and date('Y-m-d H:i:s') < "$leave->end $leave->finish" && $leave->backDate != "$leave->end $leave->finish") echo html::a($this->createLink('oa.leave', 'back', "id={$leave->id}"), $lang->leave->back, "data-toggle='modal'");?>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
