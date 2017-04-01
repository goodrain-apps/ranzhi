<?php
/**
 * The browse file of todo module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     todo
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include $app->getModuleRoot() . 'my/view/header.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<?php include '../../../sys/common/view/datepicker.html.php';?>
<?php js::set('type', $type)?>
<div class='with-side'>
  <div class='panel side'>
    <div class='panel-heading'><?php echo $lang->my->company->common?></div>
    <div class='panel-body'>
      <div class='form-group'>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->my->company->dept?></span>
          <?php echo html::select('dept', $deptList, $dept, "class='form-control chosen'")?>
        </div>
      </div>
      <div class='form-group'>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->my->company->account?></span>
          <?php echo html::select('account', $users, $account, "class='form-control chosen'")?>
        </div>
      </div>
      <div class='form-group'>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->my->company->begin?></span>
          <?php echo html::input('begin', $begin, "class='form-control form-date'")?>
        </div>
      </div>
      <div class='form-group'>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->my->company->end?></span>
          <?php echo html::input('end', $end, "class='form-control form-date'")?>
        </div>
      </div>
      <div class='form-group'>
          <?php echo html::a('javascript:void(0)', $lang->my->company->view, "class='btn btn-primary submit'")?>
      </div>
    </div>
  </div>
  <div class='main'>
    <table class='table table-bordered datatable' data-fixed-Left-Width='200'>
      <thead>
        <tr class='text-center'>
          <th data-width='80' data-flex='false' class='text-center'><?php echo $lang->my->company->dept?></th>
          <th data-width='80' data-flex='false' class='text-center'><?php echo $lang->my->company->account?></th>
          <?php foreach($dateList as $currentDate):?>
          <th data-width='200' data-flex='true' class='text-center'><?php echo date('Y-m-d', $currentDate)?></th>
          <?php endforeach;?>
        </tr>
      </thead>
      <?php foreach($todoList as $user => $todos):?>
      <tr>
        <td class='text-center text-middle'><?php echo zget($deptList, $userDept[$user], ' ')?></td>
        <td class='text-center text-middle'><?php echo zget($users, $user)?></td>
        <?php foreach($dateList as $currentDate):?>
        <td>
          <?php foreach($todos as $todo):?>
            <?php if($todo->date == date('Y-m-d', $currentDate)):?>
              <div class='text-nowrap text-ellipsis w-180px <?php echo $todo->status?>' title='<?php echo $todo->name?>'>
                <?php if(!empty($todo->begin)) echo "{$todo->begin}~{$todo->end}"?>
                <?php if($todo->type != 'leave' and $todo->type != 'trip'):?>
                <?php echo html::a($this->createLink('oa.todo', 'view', "todoID={$todo->id}"), $todo->name, "data-toggle='modal' data-width='80%'")?>
                <?php elseif($todo->type == 'leave'):?>
                <div class='text-danger'><?php echo $todo->name;?>
                <?php else:?>
                <div class='text-warning'><?php echo $todo->name;?>
                <?php endif;?>
              </div>
            <?php endif;?>
          <?php endforeach;?>
        </td>
        <?php endforeach;?>
      </tr>
      <?php endforeach;?>
    </table>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
