<?php
/**
 * The browse view file of overtime module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     overtime
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../../sys/common/view/treeview.html.php';?>
<?php js::set('confirmReview', $lang->overtime->confirmReview)?>
<div id='menuActions'>
  <?php commonModel::printLink('overtime', 'export', "mode=all&orderBy={$orderBy}", $lang->exportIcon . $lang->export, "class='btn btn-primary iframe' data-width='700'");?>
  <?php commonModel::printLink('overtime', 'create', "", "<i class='icon icon-plus'></i> {$lang->overtime->create}", "data-toggle='modal' class='btn btn-primary'")?>
</div>
<div class='with-side'>
  <div class='side'>
    <div class='panel panel-sm'>
      <div class='panel-body'>
        <ul class='tree' data-collapsed='true'>
          <?php foreach($yearList as $year):?>
          <li class='<?php echo $year == $currentYear ? 'active' : ''?>'>
            <?php commonModel::printLink('overtime', $type, "date=$year", $year);?>
            <ul>
              <?php foreach($monthList[$year] as $month):?>
              <li class='<?php echo ($year == $currentYear and $month == $currentMonth) ? 'active' : ''?>'>
                <?php commonModel::printLink('overtime', $type, "date=$year$month", $year . $month);?>
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
      <table class='table table-data table-hover text-center table-fixed tablesorter' id='overtimeTable'>
        <thead>
          <tr class='text-center'>
            <?php $vars = "&date={$date}&orderBy=%s";?>
            <th class='w-80px'><?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->overtime->id);?></th>
            <th class='w-80px'><?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->overtime->createdBy);?></th>
            <th class='w-80px visible-lg'><?php echo $lang->user->dept;?></th>
            <th class='w-80px'><?php commonModel::printOrderLink('type', $orderBy, $vars, $lang->overtime->type);?></th>
            <th class='w-150px'><?php commonModel::printOrderLink('begin', $orderBy, $vars, $lang->overtime->begin);?></th>
            <th class='w-150px'><?php commonModel::printOrderLink('end', $orderBy, $vars, $lang->overtime->end);?></th>
            <th class='w-50px visible-lg'><?php commonModel::printOrderLink('hours', $orderBy, $vars, $lang->overtime->hours);?></th>
            <th><?php echo $lang->overtime->desc;?></th>
            <th class='w-80px'><?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->overtime->status);?></th>
            <?php if($type != 'browseReview'):?>
            <th class='w-80px'><?php commonModel::printOrderLink('reviewedBy', $orderBy, $vars, $lang->overtime->reviewedBy);?></th>
            <?php endif;?>
            <?php $class = $type == 'personal' ? 'w-130px' : ($type == 'browseReview' ? 'w-100px' : 'w-40px');?>
            <th class='<?php echo $class;?>'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <?php foreach($overtimeList as $overtime):?>
        <tr>
          <td><?php echo $overtime->id;?></td>
          <td><?php echo zget($users, $overtime->createdBy);?></td>
          <td class='visible-lg'><?php echo zget($deptList, $overtime->dept, ' ');?></td>
          <td><?php echo zget($this->lang->overtime->typeList, $overtime->type);?></td>
          <td><?php echo $overtime->begin . ' ' . $overtime->start;?></td>
          <td><?php echo $overtime->end . ' ' . $overtime->finish;?></td>
          <td class='visible-lg'><?php echo $overtime->hours == 0 ? '' : $overtime->hours;?></td>
          <td title='<?php echo $overtime->desc?>'><?php echo $overtime->desc;?></td>
          <td class='overtime-<?php echo $overtime->status?>'><?php echo zget($this->lang->overtime->statusList, $overtime->status);?></td>
          <?php if($type != 'browseReview'):?>
          <td><?php echo zget($users, $overtime->reviewedBy);?></td>
          <?php endif;?>
          <td class='actionTD'>
            <?php commonModel::printLink('oa.overtime', 'view', "id=$overtime->id&type=$type", $lang->detail, "data-toggle='modal'");?>
            <?php if($type != 'company'):?>
            <?php if($type == 'browseReview' and $overtime->status == 'wait'):?>
            <?php commonModel::printLink('oa.overtime', 'review', "id=$overtime->id&status=pass", $lang->overtime->statusList['pass'], "class='reviewPass'");?>
            <?php commonModel::printLink('oa.overtime', 'review', "id=$overtime->id&status=reject", $lang->overtime->statusList['reject'], "data-toggle='modal'");?>
            <?php endif;?>
            <?php if($type == 'personal' and ($overtime->status == 'wait' or $overtime->status == 'draft')):?>
            <?php if($overtime->status == 'wait' or $overtime->status == 'draft') commonModel::printLink('oa.overtime', 'switchstatus', "id=$overtime->id", $overtime->status == 'wait' ? $lang->overtime->cancel : $lang->overtime->commit, "class='reload'");?>
            <?php commonModel::printLink('oa.overtime', 'edit', "id=$overtime->id", $lang->edit, "data-toggle='modal'");?>
            <?php commonModel::printLink('oa.overtime', 'delete', "id=$overtime->id", $lang->delete, "class='deleter'");?>
            <?php endif;?>
            <?php endif;?>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
