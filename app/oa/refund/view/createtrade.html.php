<?php
/**
 * The reimburse view file of refund module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     refund 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<form method='post' id='ajaxForm' action='<?php echo inlink('createtrade', "refundid={$refundID}")?>'>
  <table class='table table-form form-inline'>
    <tr>
      <th class='w-60px'><?php echo $lang->trade->depositor;?></th>
      <td>
        <div class='input-group'>
          <?php echo html::select('depositor', $depositorList, isset($this->config->refund->depositor) ? $this->config->refund->depositor : '', "class='form-control'");?>
          <div class='input-group-addon'><?php echo html::checkbox('objectType', $lang->trade->objectTypeList);?></div>
        </div>
      </td>
    </tr>
    <tr>
      <th><?php echo $lang->trade->customer;?></th>
      <td><?php echo html::select('customer', $customerList, '', "class='form-control chosen'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->trade->order;?></th>
      <td><?php echo html::select('order', array('') + (array) $orderList, '', "class='form-control chosen'");?></td>
    </tr>
    <tr>
      <th><?php echo $lang->trade->contract;?></th>
      <td>
        <select class='form-control chosen' id='contract' name='contract'>
          <option value=''></option>
          <?php foreach($contractList as $id => $contract):?>
          <option value="<?php echo $id?>" data-amount="<?php echo $contract->amount;?>"><?php echo $contract->name;?></option>
          <?php endforeach;?>
        </select>
      </td>
    </tr>
    <tr>
      <th></th>
      <td><?php echo html::submitButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
