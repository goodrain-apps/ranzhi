<?php
/**
 * The detail view file of trip module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     trip 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<table class='table table-bordered'>
  <tr>
    <th class='w-120px'><?php echo $lang->$type->name;?></th>
    <td><?php echo $trip->name;?></td>
  </tr>
  <tr>
    <th class='text-middle'><?php echo $lang->$type->customer;?></th>
    <td>
      <?php foreach(explode(',', $trip->customers) as $customer) echo "<div>" . zget($customers, $customer) . "</div>";?>
    </td>
  </tr>
  <tr>
    <th><?php echo $lang->$type->begin;?></th>
    <td><?php echo $trip->begin . ' ' . $trip->start;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->$type->end;?></th>
    <td><?php echo $trip->end . ' ' . $trip->finish;?></td>
  </tr>
  <?php if($trip->type == 'trip'):?>
  <tr>
    <th><?php echo $lang->$type->from;?></th>
    <td><?php echo $trip->from;?></td>
  </tr>
  <?php endif;?>
  <tr>
    <th><?php echo $lang->$type->to;?></th>
    <td><?php echo $trip->to;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->$type->desc;?></th>
    <td><?php echo $trip->desc;?></td>
  </tr> 
</table>
<div class='text-center'><?php echo html::a('#', $lang->goback, "class='btn' data-dismiss='modal'");?></div>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
