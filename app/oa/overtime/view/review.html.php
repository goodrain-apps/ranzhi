<?php
/**
 * The review view file of overtime module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     overtime
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<div class='panel-body'>
  <form id='ajaxForm' method='post' action="<?php echo $this->createLink('oa.overtime', 'review', "overtimeID=$overtime->id&status=reject")?>">
    <table class='table table-form table-condensed'>
      <tr>
        <th class='w-80px'><?php echo $lang->overtime->rejectReason?></th>
        <td><?php echo html::textarea('rejectReason', '', "rows='5' class='form-control'")?></td>
      </tr>
      <tr>
        <th></th>
        <td><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
