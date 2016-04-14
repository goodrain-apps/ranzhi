<?php
/**
 * The mail file of overtime module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     overtime
 * @version     $Id$
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
      <?php echo "{$lang->overtime->common}{$lang->overtime->statusList[$overtime->status]}#{$overtime->id}{$lang->colon}{$overtime->begin}~{$overtime->end}";?>
    </td>
  </tr>
  <tr>
    <td>
    <fieldset>
      <legend><?php echo $lang->overtime->common;?></legend>
      <div class='content'>
        <p><?php echo $lang->overtime->createdBy . ':' . zget($users, $overtime->createdBy)?></p>
        <p><?php echo $lang->overtime->status . ':' . zget($lang->overtime->statusList, $overtime->status)?></p>
        <p><?php echo $lang->overtime->type . ':' . zget($lang->overtime->typeList, $overtime->type)?></p>
        <p><?php echo "{$lang->overtime->date}: {$overtime->begin} {$overtime->start}~{$overtime->end} {$overtime->finish}"?></p>
        <p><?php echo $lang->overtime->desc?></p>
        <p><?php echo $overtime->desc?></p>
      </div>
    </fieldset>
    </td>
  </tr>
  <tr>
    <td><?php include '../../../sys/common/view/mail.html.php';?></td>
  </tr>
</table>
<?php if($onlybody) $_GET['onlybody'] = 'yes';?>
