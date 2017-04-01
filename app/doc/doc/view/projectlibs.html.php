<?php
/**
 * The project libs view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if($this->session->docFrom == 'doc') $this->doc->setMenu($project->id);?>
<?php if($this->session->docFrom == 'project') $this->loadModel('project', 'proj')->setMenu($projects, $project->id);?>
<div id='libs' class='with-menu page-content'>
  <div class='libs-group clearfix' id='libList'>
    <?php foreach($libs as $libID => $libName):?>
    <?php
    $libLink = inlink('browse', "libID=$libID&moduleID=&projectID={$project->id}");
    if($libID == 'project') $libLink = inlink('allLibs', "type=project");
    if($libID == 'files')   $libLink = inlink('showFiles', "projectID=$project->id");
    ?>
    <a class="lib <?php echo $libID == 'files' ? 'files' : '';?>" title='<?php echo $libName?>' href='<?php echo $libLink?>' data-id='<?php echo $libID;?>'>
      <i class='icon icon-2x icon-folder-open-alt'></i>
      <?php if($libID != 'files'):?><i class='icon icon-move'> </i><?php endif;?>
      <div class='lib-name' title='<?php echo $libName?>'><?php echo $libName?></div>
    </a>
    <?php endforeach; ?>
    <?php if(commonModel::hasPriv('doc', 'createLib')) echo html::a(inlink('createLib', "type=project&projectID={$project->id}"), "<i class='icon icon-plus'></i>", "class='lib addbtn' data-toggle='modal' title='{$lang->doc->createLib}'");?>
  </div>
</div>
<?php js::set('type', 'doc');?>
<?php js::set('libType', 'project');?>
<?php include '../../common/view/footer.html.php';?>
