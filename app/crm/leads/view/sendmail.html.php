<?php
/**
 * The mail file of leads module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chu Jilu <chujilu@cnezsoft.com>
 * @package     leads
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
      CONTACT #<?php echo $contact->id . " => " . zget($users, $contact->assignedTo) . html::a(commonModel::getSysURL() . $this->createLink('crm.leads', 'view', "contactID=$contact->id"), $contact->realname);?>
    </td>
  </tr>
  <tr>
    <td>
    <fieldset>
      <legend><?php echo $lang->contact->view;?></legend>
      <div class='content'>
        <p><?php echo $lang->contact->nextDate . ':' . $contact->nextDate?></p>
        <p><?php echo $lang->contact->assignedTo . ':' . zget($users, $contact->assignedTo)?></p>
        <p><?php echo $lang->contact->desc?></p>
        <p><?php echo $contact->desc?></p>
      </div>
    </fieldset>
    </td>
  </tr>
  <tr>
    <td><?php include '../../../sys/common/view/mail.html.php';?></td>
  </tr>
</table>
<?php if($onlybody) $_GET['onlybody'] = 'yes';?>
