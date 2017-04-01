<?php
/**
 * The review file of refund module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     refund
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('status', $status);?>
<li id='bysearchTab'><?php echo html::a('#', "<i class='icon-search icon'></i>" . $lang->search->common)?></li>
<div class='with-side'>
  <div class='side'>
    <div class='panel panel-sm'>
      <div class='panel-body'>
        <ul class='tree' data-collapsed='true'>
          <?php foreach($yearList as $year):?>
          <li class='<?php echo $year == $currentYear ? 'active' : ''?>'>
            <?php commonModel::printLink('refund', 'browsereview', "date=$year&status=$status", $year);?>
            <ul>
              <?php foreach($monthList[$year] as $month):?>
              <li class='<?php echo ($year == $currentYear and $month == $currentMonth) ? 'active' : ''?>'>
                <?php commonModel::printLink('refund', 'browsereview', "date=$year$month&status=$status", $year . $month);?>
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
      <table class='table table-hover table-striped table-data table-fixed tablesorter'>
        <thead>
          <tr class='text-center'>
            <?php $vars = "date=$date&status=$status&type=&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
            <th class='w-50px'><?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->refund->id);?></th>
            <th class='w-100px'><?php echo $lang->user->dept;?></th>
            <th class='w-150px'><?php commonModel::printOrderLink('name', $orderBy, $vars, $lang->refund->name);?></th>
            <th class='w-100px'><?php commonModel::printOrderLink('category', $orderBy, $vars, $lang->refund->category);?></th>
            <th class='w-100px'><?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->user->realname);?></th>
            <th class='w-100px text-right'><?php commonModel::printOrderLink('money', $orderBy, $vars, $lang->refund->money);?></th>
            <th class='w-100px'><?php commonModel::printOrderLink('date', $orderBy, $vars, $lang->refund->date);?></th>
            <th class='w-100px'><?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->refund->status);?></th>
            <th><?php echo $lang->refund->desc;?></th>
            <th class='w-80px'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <?php foreach($refunds as $refund):?>
        <?php $account = $refund->createdBy;?>
        <?php $currentDept = $users[$account]->dept;?>
        <tr data-url='<?php echo $this->createLink('refund', 'view', "refundID={$refund->id}&mode=review");?>'>
          <td><?php echo $refund->id;?></td>
          <td><?php echo zget($deptList, $currentDept);?></td>
          <td class='text-left' title='<?php echo $refund->name;?>'><?php echo $refund->name;?></td>
          <td title='<?php echo zget($categories, $refund->category);?>'><?php echo zget($categories, $refund->category, ' ');?></td>
          <td><?php echo isset($users[$account]) ? $users[$account]->realname : '';?></td>
          <td class='text-right'><?php echo zget($currencySign, $refund->currency) . $refund->money;?></td>
          <td><?php echo $refund->date;?></td>
          <td><?php echo zget($lang->refund->statusList, $refund->status);?></td>
          <td><?php echo $refund->desc?></td>
          <td>
            <?php echo html::a($this->createLink('refund', 'view',   "refundID={$refund->id}&mode=review"), $lang->detail, "")?>
            <?php if($refund->status == 'wait' or $refund->status == 'doing') echo html::a($this->createLink('refund', 'review', "refundID={$refund->id}"), $lang->refund->review, "data-toggle='modal'")?>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
      <?php $totalMoney = $this->refund->total($refunds);?>
      <?php if($totalMoney):?>
      <div class='table-footer'>
        <div class='pull-left text-danger'><?php echo $lang->refund->total . $totalMoney;?></div>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
