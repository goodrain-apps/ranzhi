<?php
/**
 * The browse view file of doc module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../../sys/common/view/treeview.html.php';?>
<?php js::set('libID ', $libID);?>
<?php js::set('libType', $lib->project ? 'project' : 'custom');?>

<?php if($this->cookie->browseType == 'bymenu'):?>
<?php include dirname(__FILE__) . '/browsebymenu.html.php';?>
<?php elseif($this->cookie->browseType == 'bytree'):?>
<?php include dirname(__FILE__) . '/browsebytree.html.php';?>
<?php else:?>
<?php $this->loadModel('project', 'proj')->setMenu($projects, $lib->project);?>
<li id='bysearchTab'><?php echo html::a('#', "<i class='icon-search icon'></i>" . $lang->search->common)?></li>
<div class='row with-menu page-content'>
  <div class='col-sm-3'>
    <div class='panel' id='treebox'>
      <div class='panel-heading'>
        <strong><?php echo $libName;?></strong>
        <?php if(!isset($lang->doc->systemLibs[$libID])):?>
        <div class='panel-actions pull-right'>
        </div>
        <?php endif;?>
      </div>
      <div class='panel-body'>
        <?php echo $moduleTree;?>
        <div class='text-right'>
          <?php commonModel::printLink('tree', 'browse', "type=doc&moduleID=0&rootID=$libID", $lang->doc->manageType, "class='btn'");?>
        </div>
      </div>
    </div>
  </div>
  <div class='col-sm-9'>
    <div class='panel'>
      <div class='panel-heading'>
        <strong><i class='icon-list-ul'></i> <?php echo $lang->doc->browse;?></strong>
        <div class='panel-actions pull-right'>
          <div class='btn-group'>
            <?php echo html::a('javascript:;', "<i class='icon icon-list'> </i>" . $lang->doc->browseTypeList['list'] . "<span class='caret'></span>", "data-toggle='dropdown'");?>
            <ul class='dropdown-menu' role='menu'>
              <li><?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-list'></i> {$lang->doc->browseTypeList['list']}");?></li>
              <li><?php echo html::a('javascript:setBrowseType("bymenu")', "<i class='icon icon-th'></i> {$lang->doc->browseTypeList['menu']}");?></li>
              <li><?php echo html::a('javascript:setBrowseType("bytree")', "<i class='icon icon-branch'></i> {$lang->doc->browseTypeList['tree']}");?></li>
            </ul>
          </div>
          <div class='btn-group'>
            <?php echo html::a('javascript:;', "<i class='icon icon-cog'> </i>" . $lang->actions . "<span class='caret'></span>", "data-toggle='dropdown'");?>
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
      <table class='table table-hover table-striped tablesorter table-fixed' id='docList'>
        <thead>
          <tr class='text-center'>
            <?php $vars = "libID=$libID&module=$moduleID&projectID=$lib->project&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
            <th class='w-100px'>  <?php commonModel::printOrderLink('id',        $orderBy, $vars, $lang->doc->id);?></th>
            <th class='text-left'><?php commonModel::printOrderLink('title',     $orderBy, $vars, $lang->doc->title);?></th>
            <th class='w-100px'>  <?php commonModel::printOrderLink('type',      $orderBy, $vars, $lang->doc->type);?></th>
            <th class='w-100px'>  <?php commonModel::printOrderLink('createdBy', $orderBy, $vars, $lang->doc->createdBy);?></th>
            <th class='w-100px visible-lg'><?php commonModel::printOrderLink('createdDate', $orderBy, $vars, $lang->doc->createdDate);?></th>
            <th class='w-90px {sorter:false}'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($docs as $key => $doc):?>
          <?php
          $viewLink = $this->createLink('doc', 'view', "docID=$doc->id");
          $canView  = commonModel::hasPriv('doc', 'view');
          ?>
          <tr class='text-center'>
            <td><?php if($canView) echo html::a($viewLink, sprintf('%03d', $doc->id)); else printf('%03d', $doc->id);?></td>
            <td class='text-left' title="<?php echo $doc->title?>"><nobr><?php echo $canView ? html::a($viewLink, $doc->title) : $doc->title;?></nobr></td>
            <td><?php echo $lang->doc->types[$doc->type];?></td>
            <td><?php isset($users[$doc->createdBy]) ? print($users[$doc->createdBy]) : print($doc->createdBy);?></td>
            <td class='visible-lg'><?php echo date("m-d H:i", strtotime($doc->createdDate));?></td>
            <td class='actions'>
              <?php 
              commonMOdel::printLink('doc', 'edit', "doc={$doc->id}", $lang->edit);
              commonModel::printLink('doc', 'delete', "docID=$doc->id&confirm=yes", $lang->delete, "class='reloadDeleter'");
              ?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <?php $pager->show();?>
      </div>
    </div>
  </div>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
