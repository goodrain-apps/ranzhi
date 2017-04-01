<?php
/**
 * The mail file of customer module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chu Jilu <chujilu@cnezsoft.com>
 * @package     customer
 * @version     $Id: sendmail.html.php 867 2010-06-17 09:32:58Z yuren_@126.com $
 * @link        http://www.ranzhico.com
 */
?>
<?php $mailTitle = 'CUSTOMER #' . $customer->id . ' ' . $customer->name;?>
<?php include '../../../sys/common/view/mail.header.html.php';?>
<tr>
  <td>
    <table cellpadding='0' cellspacing='0' width='600' style='border: none; border-collapse: collapse;'>
      <tr>
        <td style='padding: 10px; background-color: #F8FAFE; border: none; font-size: 14px; font-weight: 500; border-bottom: 1px solid #e5e5e5;'>
          <?php echo html::a(commonModel::getSysURL() . $this->createLink('crm.customer', 'view', "customerID={$customer->id}"), $mailTitle, "style='color: #333; text-decoration: none;'");?>
        </td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <td style='padding: 10px; border: none;'>
    <fieldset style='border: 1px solid #e5e5e5'>
      <legend style='color: #114f8e'><?php echo $lang->customer->view;?></legend>
      <div style='padding:5px;'>
        <p><?php echo $lang->customer->nextDate . ':' . $customer->nextDate?></p>
        <p><?php echo $lang->customer->status . ':' . zget($lang->customer->statusList, $customer->status)?></p>
        <p><?php echo $lang->customer->assignedTo . ':' . zget($users, $customer->assignedTo)?></p>
        <p><?php echo $lang->customer->desc?></p>
        <p><?php echo $customer->desc?></p>
      </div>
    </fieldset>
  </td>
</tr>
<?php include '../../../sys/common/view/mail.footer.html.php';?>
