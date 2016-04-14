<?php
/**
 * The assignTo view file of todo module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cneasoft.com>
 * @package     todo
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<?php include '../../../sys/common/view/datepicker.html.php';?>
<form method='post' id='ajaxForm' action='<?php echo $this->createLink('todo', 'assignTo', "todoID=$todo->id")?>'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px text-right'><?php echo $lang->todo->assignedTo;?></th>
      <td class='w-200px'><?php echo html::select('assignedTo', $users, $todo->assignedTo, "class='form-control chosen'");?></td>
      <td class='w-200px'></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->todo->beginAndEnd?></th>
      <td>
          <div class='input-group'>
            <?php $disabled = $todo->date == '00000000' ? "disabled='disabled'" : ''?>
            <?php echo html::input('date', $todo->date, "class='form-control form-date' $disabled");?>
            <span class='input-group-addon'><input type='checkbox' <?php echo $todo->date == '00000000' ? 'checked' : ''?> id='switchDate' onclick='switchDateTodo(this);'> <?php echo $lang->todo->periods['future'];?></span>
          </div>
      </td>
      <td>
        <div class='input-group'>
          <?php echo html::select('begin', $times, $todo->begin, 'onchange=selectNext(); class="form-control" style="width: 50%"') . html::select('end', $times, $todo->end, 'class="form-control" style="width: 50%"');?>
        </div>
      </td>
      <td>
        <input type='checkbox' id='dateSwitcher' onclick='switchDateFeature(this);' <?php if($todo->begin == 2400) echo 'checked';?> > <?php echo $lang->todo->lblDisableDate;?>
      </td>
    </tr>
    <tr>
      <th></th>
      <td colspan='3' class=''><?php echo html::submitButton();?></td>
    </tr>
  </table>
</form>
<script language='Javascript'>
$(document).ready(function(){switchDateFeature(document.getElementById('dateSwitcher'))});
</script>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
