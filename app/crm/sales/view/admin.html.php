<?php
/**
 * The admin view file of sales module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     sales
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php $count = count($groups);?>
<?php $class = $count < 4 ? 'w-p' . round(30 * $count) : '';?>
<div class='panel <?php echo $class;?>'>
  <div class='panel-heading'>
    <strong><i class='icon-group'></i> <?php echo $lang->sales->admin;?></strong>
    <span class='panel-actions pull-right'>
      <?php commonModel::printLink('sales', 'browse', '', $lang->sales->browse, "class='btn btn-primary'");?>
    </span>
  </div>
  <?php if(!empty($groups)):?>
  <form method='post' id='ajaxForm' class='form-condensed form-inline'>
    <table class='table table-hover table-bordered table-striped' id='salesGroup'>
      <thead>
        <tr class='text-center'>
          <th class='w-120px head'>
            <div class='out'></div>
            <span class='group'><?php echo $lang->sales->group;?></span>
            <span class='user'><?php echo $lang->sales->user;?></span>
          </th>
          <?php foreach($groups as $group):?>
          <th><?php if(!commonModel::printLink('sales', 'edit', "groupID={$group->id}", $group->name, "class='groupname'")) echo $group->name;?></th>
          <?php endforeach;?>
        </tr>
      </thead>
      <tbody>
      <?php foreach($users as $account => $user):?>
      <tr class='text-center'>
        <td><?php echo $user;?></td>
        <?php foreach($groups as $group):?>
        <td>
          <?php echo html::checkbox('privs_view', array("{$account}_{$group->id}" => $lang->sales->viewTip), !empty($privs[$account][$group->id]['view']) ? "{$account}_{$group->id}" : '');?>
          <?php echo html::checkbox('privs_edit', array("{$account}_{$group->id}" => $lang->sales->editTip), !empty($privs[$account][$group->id]['edit']) ? "{$account}_{$group->id}" : '');?>
          <?php if(in_array($account, explode(',', $group->users))):?>
          <label class='member' title='<?php echo $lang->sales->member;?>'><i class='icon-check'></i></label>
          <?php else:?>
          <label style='margin: 6px'></label>
          <?php endif;?>
        </td>
        <?php endforeach;?>
      </tr>
      <?php endforeach;?>
      </tbody>
    </table>
    <div style='margin:5px'><?php echo html::submitButton();?></div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
