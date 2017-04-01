<?php
/**
 * The browse view file of makeup module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     makeup
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../../sys/common/view/treeview.html.php';?>
<?php js::set('confirmReview', $lang->makeup->confirmReview)?>
<div id='menuActions'>
  <?php commonModel::printLink('makeup', 'export', "mode=all&orderBy={$orderBy}", $lang->exportIcon . $lang->export, "class='btn btn-primary iframe' data-width='700'");?>
  <?php commonModel::printLink('makeup', 'create', "", "<i class='icon icon-plus'></i> {$lang->makeup->create}", "data-toggle='modal' class='btn btn-primary'")?>
</div>
<div class='with-side'>
  <div class='side'>
    <div class='panel panel-sm'>
      <div class='panel-body'>
        <ul class='tree' data-collapsed='true'>
          <?php foreach($yearList as $year):?>
          <li class='<?php echo $year == $currentYear ? 'active' : ''?>'>
            <?php commonModel::printLink('makeup', $type, "date=$year", $year);?>
            <ul>
              <?php foreach($monthList[$year] as $month):?>
              <li class='<?php echo ($year == $currentYear and $month == $currentMonth) ? 'active' : ''?>'>
                <?php commonModel::printLink('makeup', $type, "date=$year$month", $year . $month);?>
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
      <table class='table table-data table-hover text-center table-fixed tablesorter' id='makeupTable'>
        <thead>
          <tr class='text-center'>
            <?php $vars = "&date={$date}&orderBy=%s";?>
            <th class='w-80px'><?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->makeup->id);?></th>
            <th class='w-80px'><?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->makeup->createdBy);?></th>
            <th class='w-80px visible-lg'><?php echo $lang->user->dept;?></th>
            <th class='w-150px'><?php commonModel::printOrderLink('begin', $orderBy, $vars, $lang->makeup->begin);?></th>
            <th class='w-150px'><?php commonModel::printOrderLink('end', $orderBy, $vars, $lang->makeup->end);?></th>
            <th class='w-50px visible-lg'><?php commonModel::printOrderLink('hours', $orderBy, $vars, $lang->makeup->hours);?></th>
            <th><?php echo $lang->makeup->desc;?></th>
            <th class='w-80px'><?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->makeup->status);?></th>
            <?php if($type != 'browseReview'):?>
            <th class='w-80px'><?php commonModel::printOrderLink('reviewedBy', $orderBy, $vars, $lang->makeup->reviewedBy);?></th>
            <?php endif;?>
            <?php $class = $type == 'personal' ? 'w-130px' : ($type == 'browseReview' ? 'w-100px' : 'w-40px');?>
            <th class='<?php echo $class;?>'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <?php foreach($makeupList as $makeup):?>
        <tr>
          <td><?php echo $makeup->id;?></td>
          <td><?php echo zget($users, $makeup->createdBy);?></td>
          <td class='visible-lg'><?php echo zget($deptList, $makeup->dept, ' ');?></td>
          <td><?php echo $makeup->begin . ' ' . $makeup->start;?></td>
          <td><?php echo $makeup->end . ' ' . $makeup->finish;?></td>
          <td class='visible-lg'><?php echo $makeup->hours == 0 ? '' : $makeup->hours;?></td>
          <td title='<?php echo $makeup->desc?>'><?php echo $makeup->desc;?></td>
          <td class='makeup-<?php echo $makeup->status?>'><?php echo zget($this->lang->makeup->statusList, $makeup->status);?></td>
          <?php if($type != 'browseReview'):?>
          <td><?php echo zget($users, $makeup->reviewedBy);?></td>
          <?php endif;?>
          <td class='actionTD'>
            <?php commonModel::printLink('oa.makeup', 'view', "id=$makeup->id&type=$type", $lang->detail, "data-toggle='modal'");?>
            <?php if($type != 'company'):?>
            <?php if($type == 'browseReview' and $makeup->status == 'wait'):?>
            <?php commonModel::printLink('oa.makeup', 'review', "id=$makeup->id&status=pass", $lang->makeup->statusList['pass'], "class='reviewPass'");?>
            <?php commonModel::printLink('oa.makeup', 'review', "id=$makeup->id&status=reject", $lang->makeup->statusList['reject'], "data-toggle='modal'");?>
            <?php endif;?>
            <?php if($type == 'personal' and ($makeup->status == 'wait' or $makeup->status == 'draft')):?>
            <?php if($makeup->status == 'wait' or $makeup->status == 'draft') commonModel::printLink('oa.makeup', 'switchstatus', "id=$makeup->id", $makeup->status == 'wait' ? $lang->makeup->cancel : $lang->makeup->commit, "class='reload'");?>
            <?php commonModel::printLink('oa.makeup', 'edit', "id=$makeup->id", $lang->edit, "data-toggle='modal'");?>
            <?php commonModel::printLink('oa.makeup', 'delete', "id=$makeup->id", $lang->delete, "class='deleter'");?>
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
