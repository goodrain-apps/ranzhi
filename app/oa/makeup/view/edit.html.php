<?php
/**
 * The edit view file of makeup module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     makeup
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<?php include '../../../sys/common/view/datepicker.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<?php js::set('signIn', $config->attend->signInLimit)?>
<?php js::set('signOut', $config->attend->signOutLimit)?>
<?php js::set('workingHours', $config->attend->workingHours)?>
<div class='panel-body'>
  <form id='ajaxForm' method='post' action="<?php echo $this->createLink('oa.makeup', 'edit', "id=$makeup->id")?>">
    <table class='table table-form table-condensed'>
      <tr>
        <th><?php echo $lang->makeup->status;?></th>
        <td class='text-warning'><?php echo $lang->makeup->statusList[$makeup->status];?></td>
        <td></td>
      </tr> 
      <tr>
        <th><?php echo $lang->makeup->begin?></th>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->makeup->date?></span>
            <?php echo html::input('begin', $makeup->begin, "class='form-control form-date'")?>
            <span class='input-group-addon fix-border'><?php echo $lang->makeup->time?></span>
            <?php echo html::input('start', $makeup->start, "class='form-control form-time'")?>
          </div>
        </td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->makeup->end?></th>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->makeup->date?></span>
            <?php echo html::input('end', $makeup->end, "class='form-control form-date'")?>
            <span class='input-group-addon fix-border'><?php echo $lang->makeup->time?></span>
            <?php echo html::input('finish', $makeup->finish, "class='form-control form-time'")?>
          </div>
        </td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->makeup->hours?></th>
        <td>
          <div class='input-group'>
            <?php echo html::input('hours', $makeup->hours, "class='form-control'")?>
            <span class='input-group-addon'><?php echo $lang->makeup->hoursTip?></span>
          </div>
        </td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->makeup->leave;?></th>
        <td><?php echo html::select('leave[]', $leavePairs, $makeup->leave, "class='form-control chosen' multiple")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->makeup->desc?></th>
        <td><?php echo html::textarea('desc', $makeup->desc, "class='form-control'")?></td>
        <td></td>
      </tr> 
      <tr><th></th><td clospan='2'><?php echo html::submitButton();?></td></tr>
    </table>
  </form>
</div>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
