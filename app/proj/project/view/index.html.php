<?php 
/**
 * The browse view file of project module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     project 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('status', $status);?>
<?php js::set('mode', $status);?>
<li id='bysearchTab'><?php echo html::a('#', "<i class='icon-search icon'></i>" . $lang->search->common)?></li>
<div id='menuActions'>
  <?php echo html::a("javascript:;", "<i class='icon icon-th-large'></i>", "data-mode='card' class='mode-toggle btn'");?>
  <?php echo html::a("javascript:;", "<i class='icon icon-list'></i>", "data-mode='list' class='mode-toggle btn'");?>
  <?php commonModel::printLink('project', 'create', '', '<i class="icon-plus"></i> ' . $this->lang->project->create, "id='createButton' class='btn btn-primary'");?>
</div>
<div class='row' id='cardMode'>
  <?php foreach($projects as $project):?>
  <div class='col-md-4 col-sm-6'>
    <div class='panel project-block'>
      <div class='panel-heading'>
        <strong><?php echo $project->name;?></strong>
        <?php if($this->project->hasActionPriv($project)):?>
        <div class="panel-actions pull-right">
          <div class="dropdown">
            <button class="btn btn-mini" data-toggle="dropdown"><span class="caret"></span></button>
            <ul class="dropdown-menu pull-right">
              <?php commonModel::printLink('project', 'edit', "projectID=$project->id", $lang->edit, "data-toggle='modal'", '', '', 'li');?>
              <?php commonModel::printLink('project', 'member', "projectID=$project->id", $lang->project->member, "data-toggle='modal'", '', '', 'li');?>
              <?php if($project->status != 'finished') commonModel::printLink('project','finish', "projectID=$project->id", $lang->finish, "data-toggle='modal'", '', '', 'li');?>
              <?php if($project->status != 'doing') commonModel::printLink('project', 'activate', "projectID=$project->id", $lang->activate, "class='switcher' data-confirm='{$lang->project->confirm->activate}'", '', '', 'li');?>
              <?php if($project->status != 'suspend') commonModel::printLink('project', 'suspend', "projectID=$project->id", $lang->project->suspend, "class='switcher' data-confirm='{$lang->project->confirm->suspend}'", '', '', 'li');?>
              <?php commonModel::printLink('project', 'delete', "projectID=$project->id", $lang->delete, "class='deleter'", '', '', 'li');?>
            </ul>
          </div>
        </div>
        <?php endif;?>
      </div>
      <div class='panel-body'>
        <div class='info'><?php echo $project->desc;?></div>
        <div class='footerbar text-important'>
          <span><?php echo "<i class='icon icon-user'> </i>" . zget($users, $project->PM);?></span>
          <span class=''><i class='icon icon-time'> </i><?php echo formatTime($project->begin, 'm-d') . ' ~ ' .  formatTime($project->end, 'm-d');?></span>
          <?php $browseLink = helper::createLink('task', $this->cookie->taskListType == false ? 'browse' : $this->cookie->taskListType, "projectID=$project->id");?>
          <?php echo html::a($browseLink, $lang->project->enter, "class='btn btn-primary entry'");?>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
  <div class='col-sm-6 col-md-12'><?php echo $pager->show();?></div>
</div>
<div class='panel hide' id='listMode'>
  <table class='table table-hover table-striped tablesorter table-fixed table-data'>
    <thead>
    <?php $vars = "status={$status}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
    <tr class='text-center'>
      <th class='w-60px'>   <?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->project->id);?></th>
      <th class='text-left'><?php commonModel::printOrderLink('name', $orderBy, $vars, $lang->project->name);?></th>
      <th class='w-100px'>  <?php echo $lang->project->manager;?></th>
      <th class='w-100px'>  <?php commonModel::printOrderLink('begin', $orderBy, $vars, $lang->project->begin);?></th>
      <th class='w-100px'>  <?php commonModel::printOrderLink('end', $orderBy, $vars, $lang->project->end);?></th>
      <th class='w-100px'>  <?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->project->createdBy);?></th>
      <th class='w-80px'>   <?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->project->status);?></th>
      <th><?php echo $lang->project->desc;?></th>
      <th class='w-160px'><?php echo $lang->actions;?></th>
    </tr>
    </thead>
    <?php foreach($projects as $project):?>
    <?php $browseLink = helper::createLink('task', $this->cookie->taskListType == false ? 'browse' : $this->cookie->taskListType, "projectID=$project->id");?>
    <tr class='text-center' data-url='<?php echo $browseLink;?>'>
      <td><?php echo $project->id;?></td>
      <td class='text-left'><?php echo $project->name;?></td>
      <td><?php foreach($project->members as $member) if($member->role == 'manager') echo zget($users, $member->account);?></td>
      <td><?php echo $project->begin;?></td>
      <td><?php echo $project->end;?></td>
      <td><?php echo zget($users, $project->createdBy);?></td>
      <td><?php echo $lang->project->statusList[$project->status];?></td>
      <td title='<?php echo strip_tags($project->desc);?>'><?php echo helper::substr(strip_tags($project->desc), 20, '...');?></td>
      <td>
        <?php commonModel::printLink('project', 'edit', "projectID=$project->id", $lang->edit, "data-toggle='modal'");?>
        <?php commonModel::printLink('project', 'member', "projectID=$project->id", $lang->project->member, "data-toggle='modal'");?>
        <?php if($project->status != 'finished') commonModel::printLink('project','finish', "projectID=$project->id", $lang->finish, "data-toggle='modal'");?>
        <?php if($project->status != 'doing') commonModel::printLink('project', 'activate', "projectID=$project->id", $lang->activate, "class='switcher' data-confirm='{$lang->project->confirm->activate}'");?>
        <?php if($project->status != 'suspend') commonModel::printLink('project', 'suspend', "projectID=$project->id", $lang->project->suspend, "class='switcher' data-confirm='{$lang->project->confirm->suspend}'");?>
        <?php commonModel::printLink('project', 'delete', "projectID=$project->id", $lang->delete, "class='deleter'");?>
      </td>
    </tr>
    <?php endforeach;?>
    <tfoot><tr><td colspan='9'><?php echo $pager->show();?></td></tr></tfoot>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
