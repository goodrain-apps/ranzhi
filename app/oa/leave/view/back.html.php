<?php
/**
 * The back view file of leave module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     leave
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<?php include '../../../sys/common/view/datepicker.html.php';?>
<div class='panel-body'>
  <form id='ajaxForm' method='post' action="<?php echo $this->createLink('oa.leave', 'back', "id=$leave->id")?>">
    <table class='table table-form table-condensed'>
      <tr>
        <th class='w-80px'><?php echo $lang->leave->backDate;?></th>
        <td><?php echo html::input('backDate', $leave->backDate, "class='form-control form-datetime'")?></td>
      </tr>
      <tr>
        <th></th>
        <td><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
