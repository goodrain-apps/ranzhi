<?php
/**
 * The view file of trade module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     contract
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('mode', $mode);?>
<ul id='menuTitle'>
  <li><?php commonModel::printLink('trade', 'browse', '', $lang->trade->browse);?></li>
  <li class='divider angle'></li>
  <li class='title'><?php echo $lang->trade->view;?></li>
</ul>
<div class='row-table'>
  <div class='col-main'>
    <div class='panel'>
      <div class='panel-heading'><strong><?php echo $lang->trade->desc;?></strong></div>
      <div class='panel-body'>
        <?php echo $trade->desc;?>
        <div><?php echo $this->fetch('file', 'printFiles', array('files' => $trade->files, 'fieldset' => 'false'))?></div>
      </div>
    </div>
    <?php echo $this->fetch('action', 'history', "objectType=trade&objectID={$trade->id}")?>
    <div class='page-actions'>
      <?php
      commonModel::printLink('trade', 'edit', "tradeID={$trade->id}", $lang->edit, "class='btn'");
      commonModel::printLink('trade', 'detail', "tradeID={$trade->id}", $lang->trade->detail, "data-toggle='modal' class='btn'");
      commonModel::printLink('trade', 'delete', "tradeID={$trade->id}", $lang->delete, "class='deleter btn'");
      $browseLink = $this->session->tradeList ? $this->session->tradeList : inlink('browse');
      commonModel::printRPN($browseLink, $preAndNext);
      ?>
    </div>
  </div>
  <div class='col-side'>
    <div class='panel'>
      <div class='panel-heading'>
        <strong><?php echo $lang->basicInfo;?></strong>
      </div>
      <div class='panel-body'>
        <table class='table table-info'>
          <tr>
            <th class='w-60px'><?php echo $lang->trade->date;?></th>
            <td><?php echo formatTime($trade->date, DT_DATE1);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->trade->depositor;?></th>
            <td><?php echo zget($depositorList, $trade->depositor, '');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->trade->type;?></th>
            <td><?php echo $lang->trade->typeList[$trade->type];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->trade->trader;?></th>
            <td title="<?php echo zget($customerList, $trade->trader, '');?>"><?php if($trade->trader) echo zget($customerList, $trade->trader, ' ');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->trade->money;?></th>
            <td><?php echo zget($currencySign, $trade->currency) . formatMoney($trade->money);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->trade->handlers;?></th>
            <td title='<?php foreach(explode(',', $trade->handlers) as $handler) echo zget($users, $handler) . ' ';?>'><?php foreach(explode(',', $trade->handlers) as $handler) echo zget($users, $handler) . ' ';?></td>
          </tr>
          <tr>
            <th><?php echo $lang->trade->product;?></th>
            <td><?php echo zget($productList, $trade->product, ' ');?></td>
          </tr>
          <?php if($trade->type == 'in' or $trade->type == 'out'):?>
          <tr>
            <th><?php echo $lang->trade->category;?></th>
            <td><?php echo zget($categories, $trade->category, ' ');?></td>
          </tr>
          <?php endif;?>
          <?php if($trade->trader and $trade->type == 'out'):?>
          <tr>
            <th><?php echo isset($customerList[$trade->trader]) ? $lang->trade->customer : $lang->trade->trader;?></th>
            <td><?php echo isset($customerList[$trade->trader]) ? $customerList[$trade->trader] : zget($traderList, $trade->trader, '');?></td>
          </tr>
          <?php endif;?>
          <?php if($trade->trader and $trade->type == 'in'):?>
          <tr>
            <th><?php echo $lang->trade->customer;?></th>
            <td><?php echo zget($customerList, $trade->trader, '');?></td>
          </tr>
          <?php endif;?>
          <?php if($trade->order):?>
          <tr>
            <th><?php echo $lang->trade->order;?></th>
            <td><?php echo zget($orderList, $trade->order, '');?></td>
          </tr>
          <?php endif;?>
          <?php if($trade->contract):?>
          <tr>
            <th><?php echo $lang->trade->contract;?></th>
            <td><?php echo zget($contractList, $trade->contract, '');?></td>
          </tr>
          <?php endif;?>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
