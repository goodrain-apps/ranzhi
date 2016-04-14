<?php
/**
 * The browse view file of contract module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     my
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include './header.html.php';?>
<?php js::set('type', $type);?>
<div id='menuActions' class='actions'>
  <?php commonModel::printLink('crm.contract', 'create', '', '<i class="icon-plus"></i> ' . $lang->contract->create, "class='btn btn-primary'");?>
</div>
<div class='panel'>
  <table class='table table-hover table-striped tablesorter table-data table-fixed' id='contractList'>
    <thead>
      <tr class='text-center'>
        <?php $vars = "type={$type}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
        <th class='w-60px'> <?php commonModel::printOrderLink('id',          $orderBy, $vars, $lang->contract->id);?></th>
        <th class='w-100px'><?php commonModel::printOrderLink('code',        $orderBy, $vars, $lang->contract->code);?></th>
        <th>                <?php commonModel::printOrderLink('name',        $orderBy, $vars, $lang->contract->name);?></th>
        <th class='w-100px'><?php commonModel::printOrderLink('amount',      $orderBy, $vars, $lang->contract->amount);?></th>
        <th class='w-100px visible-lg'><?php commonModel::printOrderLink('createdDate', $orderBy, $vars, $lang->contract->createdDate);?></th>
        <th class='w-100px'><?php commonModel::printOrderLink('begin',       $orderBy, $vars, $lang->contract->begin);?></th>
        <th class='w-100px'><?php commonModel::printOrderLink('end',         $orderBy, $vars, $lang->contract->end);?></th>
        <th class='w-80px'> <?php commonModel::printOrderLink('return',      $orderBy, $vars, $lang->contract->return);?></th>
        <th class='w-80px'> <?php commonModel::printOrderLink('delivery',    $orderBy, $vars, $lang->contract->delivery);?></th>
        <th class='w-60px'> <?php commonModel::printOrderLink('status',      $orderBy, $vars, $lang->contract->status);?></th>
        <th class='w-210px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($contracts as $contract):?>
      <tr class='text-center'>
        <td><?php echo $contract->id;?></td>
        <td><?php echo $contract->code;?></td>
        <td class='text-left actions'><?php echo html::a($this->createLink('crm.contract', 'view', "id=$contract->id"), $contract->name);?></td>
        <td class='text-right'><?php echo zget($currencySign, $contract->currency, '') . formatMoney($contract->amount);?></td>
        <td class='visible-lg'><?php echo substr($contract->createdDate, 0, 10);?></td>
        <td><?php echo substr($contract->begin, 0, 10);?></td>
        <td><?php echo substr($contract->end, 0, 10);?></td>
        <td><?php echo $lang->contract->returnList[$contract->return];?></td>
        <td><?php echo $lang->contract->deliveryList[$contract->delivery];?></td>
        <td class='<?php echo "contract-{$contract->status}";?>'><?php echo $lang->contract->statusList[$contract->status];?></td>
        <td class='actions'><?php echo $this->contract->buildOperateMenu($contract) ?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'>
    <?php $pager->show();?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
