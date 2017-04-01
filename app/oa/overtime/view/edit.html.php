<?php
/**
 * The edit view file of overtime module of Ranzhi.
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
<?php include '../../../sys/common/view/datepicker.html.php';?>
<?php js::set('signIn', $config->attend->signInLimit)?>
<?php js::set('signOut', $config->attend->signOutLimit)?>
<?php js::set('workingHours', $config->attend->workingHours)?>
<div class='panel-body'>
  <form id='ajaxForm' method='post' action="<?php echo $this->createLink('oa.overtime', 'edit', "id=$overtime->id")?>">
    <table class='table table-form table-condensed'>
      <tr>
        <th><?php echo $lang->overtime->status;?></th>
        <td class='text-warning'><?php echo $lang->overtime->statusList[$overtime->status];?></td>
        <td></td>
      </tr> 
      <tr>
        <th class='w-80px'><?php echo $lang->overtime->type?></th>
        <td><?php echo html::radio('type', $lang->overtime->typeList, $overtime->type, "class=''")?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->overtime->begin?></th>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->overtime->date?></span>
            <?php echo html::input('begin', $overtime->begin, "class='form-control form-date'")?>
            <span class='input-group-addon fix-border'><?php echo $lang->overtime->time?></span>
            <?php echo html::input('start', $overtime->start, "class='form-control form-time'")?>
          </div>
        </td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->overtime->end?></th>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->overtime->date?></span>
            <?php echo html::input('end', $overtime->end, "class='form-control form-date'")?>
            <span class='input-group-addon fix-border'><?php echo $lang->overtime->time?></span>
            <?php echo html::input('finish', $overtime->finish, "class='form-control form-time'")?>
          </div>
        </td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->overtime->hours?></th>
        <td>
          <div class='input-group'>
            <?php echo html::input('hours', $overtime->hours, "class='form-control'")?>
            <span class='input-group-addon'><?php echo $lang->overtime->hoursTip?></span>
          </div>
        </td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->overtime->desc?></th>
        <td><?php echo html::textarea('desc', $overtime->desc, "class='form-control'")?></td>
        <td></td>
      </tr> 
      <tr><th></th><td clospan='2'><?php echo html::submitButton();?></td></tr>
    </table>
  </form>
</div>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
