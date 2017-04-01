<style>
.tree .doc-actions {display: none;}
.tree li:hover > .doc-actions {display: inline-block; margin-left:10px;}
</style>
<?php $this->loadModel('project', 'proj')->setMenu($projects, $lib->project);?>
<li id='bysearchTab'><?php echo html::a('#', "<i class='icon-search icon'></i>" . $lang->search->common)?></li>
<div class='row with-menu page-content'>
  <div class='panel'>
    <div class='panel-heading'>
      <strong><i class='icon-list-ul'></i> <?php echo $lang->doc->browse;?></strong>
      <div class='panel-actions pull-right'>
        <div class='btn-group'>
          <?php echo html::a('javascript:;', "<i class='icon icon-branch'> </i>" . $lang->doc->browseTypeList['tree'] . "<span class='caret'></span>", "class='dropdown-toggle' data-toggle='dropdown'");?>
          <ul class='dropdown-menu' role='menu'>
            <li><?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-list'></i> {$lang->doc->browseTypeList['list']}");?></li>
            <li><?php echo html::a('javascript:setBrowseType("bymenu")', "<i class='icon icon-th'></i> {$lang->doc->browseTypeList['menu']}");?></li>
            <li><?php echo html::a('javascript:setBrowseType("bytree")', "<i class='icon icon-branch'></i> {$lang->doc->browseTypeList['tree']}");?></li>
          </ul>
        </div>
        <div class='btn-group'>
          <?php echo html::a('javascript:;', "<i class='icon icon-cog'> </i>" . $lang->actions . "<span class='caret'></span>", "class='dropdown-toggle' data-toggle='dropdown'");?>
          <ul class='dropdown-menu pull-right'>
            <?php
            commonModel::printLink('doc', 'createLib', "type=project&projectID=$lib->project", "{$lang->doc->createLib}", "data-toggle='modal'", '', '', 'li');
            commonModel::printLink('doc', 'editLib',   "libID=$libID", "{$lang->doc->editLib}", "data-toggle='modal'", '', '', 'li');
            commonModel::printLink('doc', 'deleteLib', "libID=$libID", "{$lang->doc->deleteLib}", "class='deleter'", '', '', 'li');
            ?>
          </ul>
        </div>
        <?php commonModel::printLink('doc', 'create', "libID=$libID&moduleID=$moduleID&projectID=$lib->project", '<i class="icon-plus"></i> ' . $lang->doc->create, 'class="btn btn-primary"');?>
      </div>
    </div>
    <div class='panel-body'>
      <?php if($tree):?>
      <?php echo $tree;?>
      <?php else:?>
      <?php echo $lang->pager->noRecord;?>
      <?php commonModel::printLink('doc', 'create', "libID=$libID&moduleID=$moduleID&projectID=$lib->project", $lang->doc->create);?>
      <?php endif;?>
    </div>
  </div>
</div>

<script>
$(function()
{
    $('.hitarea').click(function()
    {
        $(this).parent('li').find('.icon').toggleClass('icon-folder-open-alt');
        $(this).parent('li').find('.icon').toggleClass('icon-folder-close-alt');
    });
});
</script>
