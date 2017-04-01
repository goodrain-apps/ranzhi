<?php
/**
 * The browse view file of trip module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     trip
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../../sys/common/view/treeview.html.php';?>
<?php js::set('type', $type)?>
<div id='menuActions'>
  <?php commonModel::printLink($type, 'create', '', "<i class='icon icon-plus'></i> {$lang->$type->create}", "data-toggle='modal' class='btn btn-primary'")?>
</div>
<div class='with-side'>
  <div class='side'>
    <div class='panel panel-sm'>
      <div class='panel-body'>
        <ul class='tree' data-collapsed='true'>
          <?php foreach($yearList as $year):?>
          <li class='<?php echo $year == $currentYear ? 'active' : ''?>'>
            <?php commonModel::printLink($type, $mode, "date=$year", $year);?>
            <ul>
              <?php foreach($monthList[$year] as $month):?>
              <li class='<?php echo ($year == $currentYear and $month == $currentMonth) ? 'active' : ''?>'>
                <?php commonModel::printLink($type, $mode, "date=$year$month", $year . $month);?>
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
      <table class='table table-data table-hover text-center table-fixed tablesorter'>
        <thead>
          <tr class='text-center'>
            <?php $vars = "date=$date&orderBy=%s";?>
            <th class='w-50px'>  <?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->$type->id);?></th>
            <th class='w-80px'>  <?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->$type->createdBy);?></th>
            <th class='w-180px'> <?php commonModel::printOrderLink('name', $orderBy, $vars, $lang->$type->name);?></th>
            <th class='w-180px'> <?php commonModel::printOrderLink('customer', $orderBy, $vars, $lang->$type->customer);?></th>
            <th class='w-150px'> <?php commonModel::printOrderLink('begin', $orderBy, $vars, $lang->$type->begin);?></th>
            <th class='w-150px'> <?php commonModel::printOrderLink('end', $orderBy, $vars, $lang->$type->end);?></th>
            <?php if($type == 'trip'):?>
            <th class='w-80px'>  <?php commonModel::printOrderLink('from', $orderBy, $vars, $lang->$type->from);?></th>
            <?php endif;?>
            <th class='w-80px'>  <?php commonModel::printOrderLink('to', $orderBy, $vars, $lang->$type->to);?></th>
            <th><?php echo $lang->$type->desc;?></th>
            <th class='w-100px'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <?php foreach($tripList as $trip):?>
        <tr>
          <td><?php echo $trip->id;?></td>
          <td><?php echo zget($users, $trip->createdBy);?></td>
          <td title='<?php echo $trip->name?>'><?php echo $trip->name;?></td>
          <?php $tripCustomer = '';?>
          <?php foreach(explode(',', $trip->customers) as $customer) $tripCustomer .= zget($customers, $customer, '') . '/';?>
          <?php $tripCustomer = trim($tripCustomer, '/');?>
          <td title='<?php echo $tripCustomer;?>'><?php echo $tripCustomer;?></td>
          <td><?php echo $trip->begin . ' ' . $trip->start;?></td>
          <td><?php echo $trip->end . ' ' . $trip->finish;?></td>
          <?php if($type == 'trip'):?>
          <td title='<?php echo $trip->from?>'><?php echo $trip->from;?></td>
          <?php endif;?>
          <td title='<?php echo $trip->to?>'>  <?php echo $trip->to;?></td>
          <td title='<?php echo $trip->desc?>'><?php echo $trip->desc;?></td>
          <td>
            <?php if($trip->createdBy == $this->app->user->account):?>
            <?php echo html::a($this->createLink("oa.$type", 'edit', "id=$trip->id"), $lang->edit, "data-toggle='modal'");?>
            <?php echo html::a($this->createLink("oa.$type", 'delete', "id=$trip->id"), $lang->delete, "class='deleter'");?>
            <?php endif;?>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
