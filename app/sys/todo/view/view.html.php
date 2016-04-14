<?php
/**
 * The view file of view method of todo module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: view.html.php 4955 2013-07-02 01:47:21Z chencongzhi520@gmail.com $
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<?php include '../../../sys/common/view/kindeditor.html.php';?>
<?php if(!$todo->private or ($todo->private and $todo->account == $app->user->account)):?>
<div class='container mw-700px'>
  <div class='row-table'>
    <div class='col-main'>
      <div class='main'>
        <fieldset>
          <legend>
            <?php echo $lang->todo->desc;?>
          </legend>
          <div>
            <?php echo $todo->desc;?>
            <?php 
            if($todo->type == 'task') echo html::a("javascript:$.openEntry(\"oa\",\"" . $this->createLink('oa.task', 'view', "id={$todo->idvalue}") . "\")", $lang->task->common . '#' . $todo->idvalue, "class='btn'");
            if($todo->type == 'order') echo html::a("javascript:$.openEntry(\"crm\",\"" . $this->createLink('crm.order', 'view', "id={$todo->idvalue}") . "\")", $lang->order->common . '#' . $todo->idvalue, "class='btn'");
            if($todo->type == 'customer') echo html::a("javascript:$.openEntry(\"crm\",\"" . $this->createLink('crm.customer', 'view', "id={$todo->idvalue}") . "\")", $lang->customer->common . '#' . $todo->idvalue, "class='btn'");
            ?>
          </div>
        </fieldset>
        <?php echo $this->fetch('action', 'history', "objectType=todo&objectID={$todo->id}");?>
      </div>
      <div class='text-center actions'>
        <span class='self'>
          <?php
          $disabled = ($this->todo->checkPriv($todo, 'finish') and $this->todo->isClickable($todo, 'finish')) ? '' : 'disabled';
          commonModel::printLink('todo', 'finish', "id=$todo->id", $lang->finish, "data-id='{$todo->id}' class='btn btn-success ajaxFinish $disabled'");
          $disabled = $this->todo->checkPriv($todo, 'assignTo') ? '' : 'disabled';
          commonModel::printLink('todo', 'assignTo', "id=$todo->id", $lang->todo->assignTo, "data-id='{$todo->id}' class='btn ajaxAssign $disabled'");
          $disabled = $this->todo->checkPriv($todo, 'edit') ? '' : 'disabled';
          commonModel::printLink('todo', 'edit',   "todoID=$todo->id", $lang->edit, "class='btn ajaxEdit $disabled'");
          $disabled = ($this->todo->checkPriv($todo, 'activate') and $this->todo->isClickable($todo, 'activate')) ? '' : 'disabled';
          commonModel::printLink('todo', 'activate', "id=$todo->id", $lang->activate, "data-id='{$todo->id}' data-toggle='ajax' class='btn $disabled'");
          $disabled = ($this->todo->checkPriv($todo, 'close') and $this->todo->isClickable($todo, 'close')) ? '' : 'disabled';
          commonModel::printLink('todo', 'close', "id=$todo->id", $lang->close, "data-id='{$todo->id}' data-toggle='ajax' class='btn $disabled'");
          $disabled = $this->todo->checkPriv($todo, 'delete') ? '' : 'disabled';
          commonModel::printLink('todo', 'delete', "todoID=$todo->id", $lang->delete, "class='btn todoDeleter $disabled'");
          ?>
        </span>
        <?php
        $disabled = $this->todo->checkPriv($todo, 'edit') ? '' : 'disabled';
        echo $disabled ? html::a('###', $this->lang->comment, "class='btn disabled' disabled='disabled'") : html::a('#commentBox', $this->lang->comment, "class='btn' onclick=setComment()");
        ?>
      </div>
      <fieldset id='commentBox' class='hide'>
        <legend><?php echo $lang->comment;?></legend>
        <form id='ajaxForm' method='post' action='<?php echo inlink('edit', "todoID=$todo->id&comment=true")?>'>
          <div class='form-group'><?php echo html::textarea('comment', '',"rows='5' class='w-p100'");?></div>
          <?php echo html::submitButton();?>
        </form>
      </fieldset>      
    </div>
    <div class='col-side'>
      <div class='main main-side'>
        <fieldset>
        <legend><?php echo $lang->todo->legendBasic;?></legend>
          <table class='table table-data table-condensed table-borderless'> 
            <tr>
              <th><?php echo $lang->todo->pri;?></th>
              <td><?php echo $lang->todo->priList[$todo->pri];?></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->status;?></th>
              <td class='todo-<?php echo $todo->status?>'><?php echo $lang->todo->statusList[$todo->status];?></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->type;?></th>
              <td><?php echo $lang->todo->typeList[$todo->type];?></td>
            </tr>
            <tr>
              <th class='w-80px'><?php echo $lang->todo->date;?></th>
              <td><?php echo $todo->date == '00000000' ? $lang->todo->periods['future'] : date(DT_DATE1, strtotime($todo->date));?></td>
            </tr>
            <tr>
              <th><?php echo $lang->todo->beginAndEnd;?></th>
              <td><?php if(isset($times[$todo->begin])) echo $times[$todo->begin]; if(isset($times[$todo->end])) echo ' ~ ' . $times[$todo->end];?></td>
            </tr>
            <tr>
              <th class='w-80px'><?php echo $lang->todo->account;?></th>
              <td><?php echo zget($users, $todo->account);?></td>
            </tr>
            <tr>
              <th class='w-80px'><?php echo $lang->todo->assignedBy;?></th>
              <td><?php echo zget($users, $todo->assignedBy);?></td>
            </tr>
            <tr>
              <th class='w-80px'><?php echo $lang->todo->assignedTo;?></th>
              <td><?php echo !empty($todo->assignedTo) ? sprintf($lang->todo->assignedTip, zget($users, $todo->assignedTo), $todo->assignedDate) : '';?></td>
            </tr>
            <tr>
              <th class='w-80px'><?php echo $lang->todo->finishedBy;?></th>
              <td><?php echo !empty($todo->finishedBy) ? sprintf($lang->todo->finishedTip, zget($users, $todo->finishedBy), $todo->finishedDate) : '';?></td>
            </tr>
            <tr>
              <th class='w-80px'><?php echo $lang->todo->closedBy;?></th>
              <td><?php echo !empty($todo->closedBy) ? sprintf($lang->todo->closedTip, zget($users, $todo->closedBy), $todo->closedDate) : '';?></td>
            </tr>
          </table>
      </div>
    </div>
  </div>
</div>
<?php else:?>
<?php echo $lang->todo->thisIsPrivate;?>
<?php endif;?>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
