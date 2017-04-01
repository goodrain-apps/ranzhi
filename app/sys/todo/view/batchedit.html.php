<?php
/**
 * The batch edit view of todo module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com> 
 * @package     todo
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include $app->getModuleRoot() . '../sys/my/view/header.html.php';?>
<?php include '../../../sys/common/view/datepicker.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<?php js::set('mode', $mode);?>
<form class='form-condensed' id='ajaxForm' method='post' action='<?php echo $this->createLink('todo', 'batchEdit')?>'>
  <div class='panel'>
    <table class='table table-condensed table-fixed'>
      <thead>
        <tr class='text-center'>
          <th class='w-30px'><?php echo $lang->todo->id;?></th> 
          <th class='w-100px'><?php echo $lang->todo->type;?></th>
          <th class='w-80px'><?php echo $lang->todo->pri;?></th>
          <th class='w-100px'><?php echo $lang->todo->assignedTo;?></th>
          <th class='red'><?php echo $lang->todo->name;?></th>
          <th><?php echo $lang->todo->desc;?></th>
          <th class='w-100px'><?php echo $lang->todo->date;?></th>
          <th class='w-160px'><?php echo $lang->todo->beginAndEnd;?></th>
          <th class='w-70px'><input type='checkbox' name='switchAll' id='switchAll' onclick='switchDateAll(this)'><?php echo $lang->todo->periods['future'];?></th>
        </tr>
      </thead>
      <?php $i = 1;?>
      <?php foreach($todos as $id => $todo):?>
      <tr class='text-center'>
        <td class='text-middle'><?php echo $i++;?></td>
        <td><?php echo html::select("types[$id]", $lang->todo->typeList, $todo->type, "onchange='loadList(this.value, " . ($i + 1) . ")' class='form-control'");?></td>
        <td><?php echo html::select("pris[$id]", $lang->todo->priList, $todo->pri, "class='form-control'");?></td>
        <td><?php echo html::select("assignedTo[$id]", $users, $todo->assignedTo, "class='form-control'");?></td>
        <td class='text-left' style='overflow:visible'>
          <div id='<?php echo "nameBox" . $id;?>' class='hidden'><?php echo html::input("names[$id]", $todo->name, 'class="text-left form-control"');?></div>
          <div class='<?php echo "nameBox" . $id;?>'><?php echo html::input("names[$id]", $todo->name, 'class="text-left form-control"');?></div>
        </td>
        <td><?php echo html::textarea("descs[$id]", $todo->desc, "class='form-control'");?></td>
        <td><?php echo html::input("dates[$id]", $todo->date, "class='form-control form-date'");?></td>
        <td colspan='2'>
          <div class='input-group'>
            <?php echo html::select("begins[$id]", $times, $todo->begin, "onchange=\"setBeginsAndEnds($id, 'begin');\" class='form-control' style='width: 50%'");?>
            <?php echo html::select("ends[$id]", $times, $todo->end, "onchange=\"setBeginsAndEnds($id, 'end');\" class='form-control' style='width: 50%'");?>
            <span class='input-group-addon'><input type='checkbox' name="switchDate[<?php echo $id?>]" id="switchDate<?php echo $id;?>" data-key="<?php echo $id;?>" onclick='switchDateList(<?php echo $id?>);'><?php echo $lang->todo->periods['future'];?></span>
          </div>
        </td>
      </tr>  
      <?php endforeach;?>
      <tfoot>
        <tr>
          <td></td>
          <td colspan='5'><?php echo html::submitButton() . html::backButton();?></td>
        </tr>
      </tfoot>
    </table>
  </div>
</form>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
