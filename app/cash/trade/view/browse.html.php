<?php 
/**
 * The browse view file of trade module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     trade 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../../sys/common/view/treeview.html.php';?>
<?php js::set('mode', $mode);?>
<?php js::set('date', $date);?>
<?php js::set('currentYear', $currentYear);?>
<?php js::set('treeview', !empty($_COOKIE['treeview']) ? $_COOKIE['treeview'] : '');?>
<li id='bysearchTab'><?php echo html::a('#', "<i class='icon-search icon'></i>" . $lang->search->common)?></li>
<div id='menuActions'>
  <?php commonModel::printLink('trade', 'import', '', "<i class='icon-download-alt'> </i>" . $lang->trade->import, "class='btn btn-primary' data-toggle='modal'")?>
  <?php if(commonModel::hasPriv('trade', 'export')):?>
  <div class='btn-group'>
    <button data-toggle='dropdown' class='btn btn-primary dropdown-toggle' type='button'><?php echo "<i class='icon-upload-alt'> </i>" . $lang->export;?> <span class='caret'></span></button>
    <ul id='exportActionMenu' class='dropdown-menu pull-right'>
      <li><?php commonModel::printLink('trade', 'export', "mode=all&orderBy={$orderBy}", $lang->exportAll, "class='iframe' data-width='700'");?></li>
      <li><?php commonModel::printLink('trade', 'export', "mode=thisPage&orderBy={$orderBy}", $lang->exportThisPage, "class='iframe' data-width='700'");?></li>
    </ul>
  </div>
  <?php endif;?>
  <?php if($mode == 'all'):?>
  <div class='btn-group'>
    <button data-toggle='dropdown' class='btn btn-primary dropdown-toggle' type='button'><?php echo  "<i class='icon-plus'> </i>" . $lang->trade->create;?> <span class='caret'></span></button>
    <ul id='createActionMenu' class='dropdown-menu pull-right'>
      <li><?php commonModel::printLink('trade', 'create',   'type=in',     $lang->trade->createIn)?></li>
      <li><?php commonModel::printLink('trade', 'create',   'type=out',    $lang->trade->createOut)?></li>
      <li><?php commonModel::printLink('trade', 'transfer', '',            $lang->trade->transfer)?></li>
      <li><?php commonModel::printLink('trade', 'invest',   'type=invest', $lang->trade->invest)?></li>
      <li><?php commonModel::printLink('trade', 'loan',     'type=loan',   $lang->trade->loan)?></li>
    </ul>
  </div>
  <?php endif;?>
  <?php if($mode == 'in')       commonModel::printLink('trade', 'create',   'type=in',     "<i class='icon-plus'> </i>" . $lang->trade->createIn,  "class='btn btn-primary'");?>
  <?php if($mode == 'out')      commonModel::printLink('trade', 'create',   'type=out',    "<i class='icon-plus'> </i>" . $lang->trade->createOut, "class='btn btn-primary'");?>
  <?php if($mode == 'transfer') commonModel::printLink('trade', 'transfer', '',            "<i class='icon-plus'> </i>" . $lang->trade->transfer,  "class='btn btn-primary'");?>
  <?php if($mode == 'invest')   commonModel::printLink('trade', 'invest',   'type=invest', "<i class='icon-plus'> </i>" . $lang->trade->invest,    "class='btn btn-primary'");?>
  <?php if($mode == 'invest')   commonModel::printLink('trade', 'invest',   'type=redeem', "<i class='icon-plus'> </i>" . $lang->trade->redeem,    "class='btn btn-primary'");?>
  <?php if($mode == 'loan')     commonModel::printLink('trade', 'loan',     'type=loan',   "<i class='icon-plus'> </i>" . $lang->trade->loan,      "class='btn btn-primary'");?>
  <?php if($mode == 'loan')     commonModel::printLink('trade', 'loan',     'type=repay',  "<i class='icon-plus'> </i>" . $lang->trade->repay,     "class='btn btn-primary'");?>
  <?php if($mode == 'all' || $mode == 'in' || $mode == 'out') commonModel::printLink('trade', 'batchcreate', '', "<i class='icon-sitemap'> </i>" . $lang->trade->batchCreate, "class='btn btn-primary'")?>
</div>
<div class='panel'>
  <form method='post' action='<?php echo inlink('batchedit', 'step=form')?>'>
    <table class='table table-hover table-striped table-bordered tablesorter table-data table-fixed' id='tradeList'>
      <thead>
        <tr class='text-center'>
          <?php $vars = "mode={$mode}&date={$date}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='w-100px'><?php commonModel::printOrderLink('date', $orderBy, $vars, $lang->trade->date);?></th>
          <th class='w-100px'><?php commonModel::printOrderLink('depositor', $orderBy, $vars, $lang->trade->depositor);?></th>
          <th class='w-60px'><?php commonModel::printOrderLink('type', $orderBy, $vars, $lang->trade->type);?></th>
          <th><?php commonModel::printOrderLink('trader', $orderBy, $vars, $lang->trade->trader);?></th>
          <th class='w-120px text-right'><?php commonModel::printOrderLink('money', $orderBy, $vars, $lang->trade->money);?></th>
          <th class='w-100px'><?php commonModel::printOrderLink('handlers', $orderBy, $vars, $lang->trade->handlers);?></th>
          <th class='w-200px'><?php commonModel::printOrderLink('product', $orderBy, $vars, $lang->trade->product . $lang->slash . $lang->trade->category);?></th>
          <th class='w-200px visible-lg'><?php echo $lang->trade->desc;?></th>
          <?php if($mode == 'invest' or $mode == 'loan'):?>
          <th class='w-80px'><?php echo $lang->trade->status;?></th>
          <?php endif;?>
          <?php if($mode == 'invest'):?>
          <th class='w-80px'><?php echo $lang->trade->rate;?></th>
          <?php endif;?>
          <?php if($mode == 'loan'):?>
          <th class='w-80px'><?php echo $lang->trade->loanrate;?></th>
          <?php endif;?>
          <th class='w-130px'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($trades as $trade):?>
        <tr class='text-center'>
          <td class='text-left'>
          <label class='checkbox-inline'><input type='checkbox' name='tradeIDList[]' value='<?php echo $trade->id;?>'/><?php echo formatTime($trade->date, DT_DATE1);?></label>
          </td>
          <td class='text-left'><?php echo zget($depositorList, $trade->depositor, ' ');?></td>
          <td><?php echo $lang->trade->typeList[$trade->type];?></td>
          <td class='text-left' title="<?php echo zget($customerList, $trade->trader, '');?>"><?php if($trade->trader) echo zget($customerList, $trade->trader, ' ');?></td>
          <td class='text-right'><?php echo zget($currencySign, $trade->currency) . formatMoney($trade->money);?></td>
          <td title='<?php foreach(explode(',', $trade->handlers) as $handler) echo zget($users, $handler) . ' ';?>'><?php foreach(explode(',', $trade->handlers) as $handler) echo zget($users, $handler) . ' ';?></td>
          <td class='text-left'><?php echo isset($productList[$trade->product]) ? $productList[$trade->product] . $lang->slash . zget($categories, $trade->category, ' ') : zget($categories, $trade->category, ' ');?></td>
          <td class='text-left visible-lg'><div title="<?php echo $trade->desc;?>" class='w-200px text-ellipsis'><?php echo $trade->desc;?><div></td>
          <?php if($mode == 'invest' or $mode == 'loan'):?>
          <td><?php echo zget($lang->trade->statusList, $trade->status);?></td>
          <?php endif;?>
          <?php if($mode == 'invest'):?>
          <td><?php if($trade->return) echo $trade->return;?></td>
          <?php endif;?>
          <?php if($mode == 'loan'):?>
          <td><?php if($trade->interest) echo $trade->interest;?></td>
          <?php endif;?>
          <td>
            <?php commonModel::printLink('trade', 'view', "tradeID={$trade->id}&mode={$mode}", $lang->view);?>
            <?php commonModel::printLink('trade', 'edit', "tradeID={$trade->id}&mode={$mode}", $lang->edit);?>
            <?php commonModel::printLink('trade', 'detail', "tradeID={$trade->id}&mode={$mode}", $lang->trade->detail, "data-toggle='modal'");?>
            <?php commonModel::printLink('trade', 'delete', "tradeID={$trade->id}", $lang->delete, "class='deleter'");?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class='pull-left'>
        <?php echo html::selectButton() . html::submitButton($lang->edit);?>
        <span class='text-danger'><?php $this->trade->countMoney($trades, $mode);?></span>
      </div>
      <?php echo $pager->get();?>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
