<?php
/**
 * The browse file of todo module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     todo
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include $app->getModuleRoot() . '../sys/my/view/header.html.php';?>
<?php include '../../../sys/common/view/datepicker.html.php';?>
<?php js::set('mode', $mode)?>
<div class='panel'>
  <form id='ajaxForm' method='post'>
    <table class='table table-hover table-striped tablesorter table-data table-fixed' id='todoList'>
      <thead>
        <tr class='text-center'>
          <?php $vars = "mode=$mode&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='w-60px'> <?php commonModel::printOrderLink('id',     $orderBy, $vars, $lang->todo->id);?></th>
          <th class='w-80px'> <?php commonModel::printOrderLink('date',   $orderBy, $vars, $lang->todo->date);?></th>
          <th class='w-100px'><?php commonModel::printOrderLink('type',   $orderBy, $vars, $lang->todo->type);?></th>
          <th class='w-80px'> <?php commonModel::printOrderLink('pri',    $orderBy, $vars, $lang->todo->pri);?></th>
          <th>                <?php commonModel::printOrderLink('name',   $orderBy, $vars, $lang->todo->name);?></th>
          <th class='w-100px'><?php commonModel::printOrderLink('assignedTo', $orderBy, $vars, $lang->todo->assignedTo);?></th>
          <th class='w-100px'><?php commonModel::printOrderLink('begin',  $orderBy, $vars, $lang->todo->begin);?></th>
          <th class='w-100px'><?php commonModel::printOrderLink('end',    $orderBy, $vars, $lang->todo->end);?></th>
          <th class='w-100px'><?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->todo->status);?></th>
          <th class='w-240px'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($todos as $todo):?>
        <tr class='text-center'>
          <td class='text-left'><label class='checkbox-inline'><input type='checkbox' name='todoIDList[]' value='<?php echo $todo->id;?>'/><?php echo $todo->id;?></td>
          <td><?php echo $todo->date;?></td>
          <td><?php echo zget($lang->todo->typeList, $todo->type);?></td>
          <td><?php echo $lang->todo->priList[$todo->pri];?></td>
          <td class='text-left' title='<?php echo $todo->name?>'><?php echo $todo->name;?></td>
          <td><?php echo zget($users, $todo->assignedTo);?></td>
          <td><?php echo $todo->begin;?></td>
          <td><?php echo $todo->end;?></td>
          <td><?php echo zget($lang->todo->statusList, $todo->status);?></td>
          <td class='text-left'>
            <?php 
              echo html::a($this->createLink('todo', 'view', "todoID={$todo->id}"), $lang->view, "data-toggle='modal' data-width='80%'");
              $disabled = ($this->todo->checkPriv($todo, 'finish') && $this->todo->isClickable($todo, 'finish')) ? '' : 'disabled';
              echo html::a($this->createLink('todo', 'finish', "todoID={$todo->id}"), $lang->todo->finish, "class='$disabled' data-toggle='ajax'");
              echo html::a($this->createLink('todo', 'assignTo', "todoID={$todo->id}"), $lang->todo->assignTo, "data-toggle='modal'");
              $disabled = ($this->todo->checkPriv($todo, 'activate') and $this->todo->isClickable($todo, 'activate')) ? '' : 'disabled';
              echo html::a($this->createLink('todo', 'activate', "todoID={$todo->id}"), $lang->activate, "class='$disabled' data-toggle='ajax'");
              $disabled = ($this->todo->checkPriv($todo, 'close') and $this->todo->isClickable($todo, 'close')) ? '' : 'disabled';
              echo html::a($this->createLink('todo', 'close', "todoID={$todo->id}"), $lang->close, "class='$disabled' data-toggle='ajax'");
              echo html::a($this->createLink('todo', 'edit', "todoID={$todo->id}"), $lang->edit, "data-toggle='modal'");
              echo html::a($this->createLink('todo', 'delete', "todoID={$todo->id}"), $lang->delete, "class='deleter'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class='pull-left batch-actions'>
        <?php $closeActionLink = $this->createLink('sys.todo', 'batchClose');?>
        <div class='pull-left close-action'><?php echo html::selectButton() . html::commonButton($lang->close, 'btn btn-primary', "onclick=\"setFormAction('$closeActionLink')\"");?></div>
        <?php
        if(commonModel::hasPriv('todo', 'import2Today'))
        {
            $actionLink = $this->createLink('todo', 'import2Today');
            echo "<div class='input-group import-action'>";
            echo "<div class='datepicker-wrapper datepicker-date'>" . html::input('date', date('Y-m-d'), "class='form-control form-date'") . '</div>';
            echo "<span class='input-group-btn'>";
            echo html::commonButton($lang->todo->import, 'btn btn-default', "onclick=\"setFormAction('$actionLink')\"");
            echo '</span>';
            echo '</div>';
        }
        ?>
      </div>
      <?php $pager->show();?>
    </div>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
