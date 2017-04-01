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
<?php include '../../../sys/common/view/mail.header.html.php';?>
<?php $mailTitle = "{$lang->lieu->common}#{$lieu->id} " . zget($users, $lieu->createdBy) . " {$lieu->begin}~{$lieu->end}";?>
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
      <legend style='color: #114f8e'><?php echo $lang->lieu->common;?></legend>
      <div style='padding:5px;'>
        <p><?php echo $lang->lieu->createdBy . ':' . zget($users, $lieu->createdBy)?></p>
        <p><?php echo $lang->lieu->status . ':' . zget($lang->lieu->statusList, $lieu->status)?></p>
        <p><?php echo "{$lang->lieu->date}: {$lieu->begin} {$lieu->start}~{$lieu->end} {$lieu->finish}"?></p>
        <p><?php echo $lang->lieu->desc;?></p>
        <p><?php echo $lieu->desc;?></p>
      </div>
    </fieldset>
  </td>
</tr>
<?php include '../../../sys/common/view/mail.footer.html.php';?>
