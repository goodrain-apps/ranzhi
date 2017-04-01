<?php
/**
 * The project view file of my of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     my
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include './header.html.php';?>
<div class='panel'>
  <table class='table table-hover table-striped tablesorter table-fixed table-data'>
    <thead>
    <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
    <tr class='text-center'>
      <th class='w-60px'><?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->project->id);?></th>
      <th class='text-left'><?php commonModel::printOrderLink('name', $orderBy, $vars, $lang->project->name);?></th>
      <th class='w-100px'><?php echo $lang->project->manager;?></th>
      <th class='w-100px'><?php commonModel::printOrderLink('begin', $orderBy, $vars, $lang->project->begin);?></th>
      <th class='w-100px'><?php commonModel::printOrderLink('end', $orderBy, $vars, $lang->project->end);?></th>
      <th class='w-100px'><?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->project->createdBy);?></th>
      <th class='w-80px'><?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->project->status);?></th>
      <th><?php echo $lang->project->desc;?></th>
      <th class='w-160px text-right createProductTH'><?php commonModel::printLink('proj.project', 'create', '', '<i class="icon-plus"></i> ' . $this->lang->project->create, "id='createButton' class='btn btn-primary'");?></th>
    </tr>
    </thead>
    <?php foreach($projects as $project):?>
    <?php $browseLink = helper::createLink('proj.task', $this->cookie->taskListType == false ? 'browse' : $this->cookie->taskListType, "projectID=$project->id");?>
    <tr class='text-center'>
      <td><?php echo $project->id;?></td>
      <td class='text-left'><?php echo html::a("javascript:$.openEntry(\"proj\", \"" . $browseLink . "\")", $project->name);?></td>
      <td><?php foreach($project->members as $member) if($member->role == 'manager') echo zget($users, $member->account);?></td>
      <td><?php echo $project->begin;?></td>
      <td><?php echo $project->end;?></td>
      <td><?php echo zget($users, $project->createdBy);?></td>
      <td><?php echo $lang->project->statusList[$project->status];?></td>
      <td title='<?php echo strip_tags($project->desc);?>'><?php echo helper::substr(strip_tags($project->desc), 20, '...');?></td>
      <td class='actions'>
        <?php commonModel::printLink('proj.project', 'edit', "projectID=$project->id", $lang->edit, "data-toggle='modal'");?>
        <?php commonModel::printLink('proj.project', 'member', "projectID=$project->id", $lang->project->member, "data-toggle='modal'");?>
        <?php if($project->status != 'finished') commonModel::printLink('proj.project','finish', "projectID=$project->id", $lang->finish, "data-toggle='modal'");?>
        <?php if($project->status != 'doing') commonModel::printLink('proj.project', 'activate', "projectID=$project->id", $lang->activate, "class='switcher' data-confirm='{$lang->project->confirm->activate}'");?>
        <?php if($project->status != 'suspend') commonModel::printLink('proj.project', 'suspend', "projectID=$project->id", $lang->project->suspend, "class='switcher' data-confirm='{$lang->project->confirm->suspend}'");?>
        <?php commonModel::printLink('proj.project', 'delete', "projectID=$project->id", $lang->delete, "class='deleter'");?>
      </td>
    </tr>
    <?php endforeach;?>
    <tfoot><tr><td colspan='9'><?php echo $pager->show();?></td></tr></tfoot>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
