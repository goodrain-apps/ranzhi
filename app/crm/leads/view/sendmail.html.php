<?php
/**
 * The mail file of leads module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chu Jilu <chujilu@cnezsoft.com>
 * @package     leads
 * @version     $Id: sendmail.html.php 867 2010-06-17 09:32:58Z yuren_@126.com $
 * @link        http://www.ranzhico.com
 */
?>
<?php $mailTitle = 'LEADS#' . $contact->id . ' ' . $contact->realname;?>
<?php include '../../../sys/common/view/mail.header.html.php';?>
<tr>
  <td>
    <table cellpadding='0' cellspacing='0' width='600' style='border: none; border-collapse: collapse;'>
      <tr>
        <td style='padding: 10px; background-color: #F8FAFE; border: none; font-size: 14px; font-weight: 500; border-bottom: 1px solid #e5e5e5;'>
          <?php echo html::a(commonModel::getSysURL() . $this->createLink('crm.leads', 'view', "contactID={$contact->id}"), $mailTitle, "style='color: #333; text-decoration: none;'");?>
        </td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <td style='padding: 10px; border: none;'>
    <fieldset style='border: 1px solid #e5e5e5'>
      <legend style='color: #114f8e'><?php echo $lang->contact->view;?></legend>
      <div style='padding:5px;'>
        <p><?php echo $lang->contact->nextDate . ':' . $contact->nextDate?></p>
        <p><?php echo $lang->contact->assignedTo . ':' . zget($users, $contact->assignedTo)?></p>
        <p><?php echo $lang->contact->desc?></p>
        <p><?php echo $contact->desc?></p>
      </div>
    </fieldset>
  </td>
</tr>
<?php include '../../../sys/common/view/mail.footer.html.php';?>
