<?php
/**
 * The mail file of makeup module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     makeup
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/mail.header.html.php';?>
<?php $mailTitle = "{$lang->makeup->common}#{$makeup->id} " . zget($users, $makeup->createdBy) . " {$makeup->begin}~{$makeup->end}";?>
<tr>
  <td>
    <table cellpadding='0' cellspacing='0' width='600' style='border: none; border-collapse: collapse;'>
      <tr>
        <td style='padding: 10px; background-color: #F8FAFE; border: none; font-size: 14px; font-weight: 500; border-bottom: 1px solid #e5e5e5;'>
          <?php echo $mailTitle;?>
        </td>
      </tr>
    </table>
  </td>
</tr>
<tr>
  <td style='padding: 10px; border: none;'>
    <fieldset style='border: 1px solid #e5e5e5'>
      <legend style='color: #114f8e'><?php echo $lang->makeup->common;?></legend>
      <div style='padding:5px;'>
        <p><?php echo $lang->makeup->createdBy . ':' . zget($users, $makeup->createdBy)?></p>
        <p><?php echo $lang->makeup->status . ':' . zget($lang->makeup->statusList, $makeup->status)?></p>
        <p><?php echo "{$lang->makeup->date}: {$makeup->begin} {$makeup->start}~{$makeup->end} {$makeup->finish}"?></p>
        <p><?php echo $lang->makeup->desc?></p>
        <p><?php echo $makeup->desc?></p>
      </div>
    </fieldset>
  </td>
</tr>
<?php include '../../../sys/common/view/mail.footer.html.php';?>
