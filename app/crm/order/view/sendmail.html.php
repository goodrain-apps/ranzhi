<?php
/**
 * The mail file of order module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chu Jilu <chujilu@cnezsoft.com>
 * @package     order
 * @version     $Id: sendmail.html.php 867 2010-06-17 09:32:58Z yuren_@126.com $
 * @link        http://www.ranzhico.com
 */
?>
<?php $mailTitle = 'ORDER#' . $order->id . ' ' . $order->title;?>
<?php include '../../../sys/common/view/mail.header.html.php';?>
<tr>
  <td>
    <table cellpadding='0' cellspacing='0' width='600' style='border: none; border-collapse: collapse;'>
      <tr>
        <td style='padding: 10px; background-color: #F8FAFE; border: none; font-size: 14px; font-weight: 500; border-bottom: 1px solid #e5e5e5;'>
          <?php echo html::a(commonModel::getSysURL() . $this->createLink('crm.order', 'view', "orderID={$order->id}"), $mailTitle, "style='color: #333; text-decoration: none;'");?>
        </td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <td style='padding: 10px; border: none;'>
    <fieldset style='border: 1px solid #e5e5e5'>
      <legend style='color: #114f8e'><?php echo $lang->order->view;?></legend>
      <div style='padding:5px;'>
        <?php $productName = count($order->products) > 1 ? current($order->products) . $lang->etc : current($order->products);?>
        <p><?php printf($lang->order->infoBuy, $order->customerName, $productName);?></p>
        <?php if($order->status == 'signed' and $contract):?>
        <p><?php printf($lang->order->infoContract, $contract->name);?></p>
        <?php endif;?>
        <p><?php printf($lang->order->infoAmount, $order->plan, $order->real)?></p>
        <p>
          <?php if(formatTime($order->contactedDate)) printf($lang->order->infoContacted, $order->contactedDate)?>
          <?php if(formatTime($order->nextDate)) printf($lang->order->infoNextDate, $order->nextDate)?>
        </p>
      </div>
    </fieldset>
  </td>
</tr>
<?php include '../../../sys/common/view/mail.footer.html.php';?>
