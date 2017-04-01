<?php
/**
 * The browse view file of tree module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: browse.html.php 4029 2016-08-26 06:50:41Z liugang $
 * @link        http://www.ranzhico.com
 */
?>
<?php
if(RUN_MODE == 'front')
{
    include $app->getModuleRoot() . 'common/view/header.html.php';
}
else
{
    include '../../common/view/header.html.php';
}
include '../../common/view/kindeditor.html.php';
include '../../common/view/chosen.html.php';
js::set('root', $root);
js::set('type', $type);
js::set('moduleID', $moduleID);
?>
<?php if($type == 'doc'):?>
<?php js::set('project', isset($lib->project) ? $lib->project : 0);?>
<?php js::set('docFrom', $this->session->docFrom);?>
<?php if($this->session->docFrom == 'project'):?>
<?php $this->loadModel('project', 'proj')->setMenu($projects, $lib->project);?>
<?php else:?>
<?php $this->loadModel('doc', 'doc')->setMenu(0, $root, $moduleID);?>
<?php endif;?>
<div class='col-md-12 doc-category'>
<?php else:?>
<div class='col-md-12'>
<?php endif;?>
<?php if(strpos($treeMenu, '<li>') !== false):?>
<div class='row'>
  <?php if($moduleMenu):?>
  <div class='col-md-4'>
  <?php else:?>
  <div class='col-md-3'>
  <?php endif;?>
    <div class='panel'>
      <div class='panel-heading'>
        <strong><i class="icon-sitemap"></i> <?php echo $lang->category->common;?></strong>
        <?php if($type == 'in' or $type == 'out'):?>
        <div class='panel-actions pull-right'><?php echo html::a(helper::createLink('tree', 'merge', "type=$type"), $lang->category->merge, "class='btn btn-primary ajax'")?></div>
        <?php endif;?>
      </div>
      <div class='panel-body'><div id='treeMenuBox'><?php echo $treeMenu;?></div></div>
    </div>
  </div>
  <div class='col-md-8' id='categoryBox'></div>
</div>
<?php else:?>
<div id='categoryBox'></div>
<?php endif;?>
</div>
<?php
include '../../common/view/treeview.html.php';
if(RUN_MODE == 'front' && strpos($app->getModuleRoot(), 'sys') == false)
{
    include $app->getModuleRoot() . 'common/view/footer.html.php';
}
else
{
    include '../../common/view/footer.html.php';
}
?>
