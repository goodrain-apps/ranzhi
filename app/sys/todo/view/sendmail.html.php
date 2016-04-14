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
      <?php echo "{$lang->todo->common}#{$todo->id}{$lang->colon}{$todo->name}";?>
    </td>
  </tr>
  <tr>
    <td>
    <fieldset>
      <legend><?php echo $todo->name;?></legend>
      <div class='content'>
        <p><?php echo $lang->todo->status . ':' . zget($lang->todo->statusList, $todo->status)?></p>
        <p><?php echo "{$lang->todo->date}: {$todo->date}"?></p>
        <p><?php echo $lang->todo->desc?></p>
        <p><?php echo $todo->desc?></p>
      </div>
    </fieldset>
    </td>
  </tr>
  <tr>
    <td><?php include '../../../sys/common/view/mail.html.php';?></td>
  </tr>
</table>
<?php if($onlybody) $_GET['onlybody'] = 'yes';?>
