<?php
/**
 * The mail file of customer module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chu Jilu <chujilu@cnezsoft.com>
 * @package     customer
 * @version     $Id: sendmail.html.php 867 2010-06-17 09:32:58Z yuren_@126.com $
 * @link        http://www.ranzhico.com
 */
?>
<?php
$onlybody = isonlybody() ? true : false;
if($onlybody) $_GET['onlybody'] = 'no';
?>
<table width='98%' align='center'>
  <tr class='header'>
    <td>
      CUSTOMER #<?php echo $customer->id . " => " . zget($users, $customer->assignedTo) . html::a(commonModel::getSysURL() . $this->createLink('crm.customer', 'view', "customerID=$customer->id"), $customer->name);?>
    </td>
  </tr>
  <tr>
    <td>
    <fieldset>
      <legend><?php echo $lang->customer->view;?></legend>
      <div class='content'>
        <p><?php echo $lang->customer->nextDate . ':' . $customer->nextDate?></p>
        <p><?php echo $lang->customer->status . ':' . zget($lang->customer->statusList, $customer->status)?></p>
        <p><?php echo $lang->customer->assignedTo . ':' . zget($users, $customer->assignedTo)?></p>
        <p><?php echo $lang->customer->desc?></p>
        <p><?php echo $customer->desc?></p>
      </div>
    </fieldset>
    </td>
  </tr>
  <tr>
    <td><?php include '../../../sys/common/view/mail.html.php';?></td>
  </tr>
</table>
<?php if($onlybody) $_GET['onlybody'] = 'yes';?>
