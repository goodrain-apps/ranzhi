<?php
/**
 * The create view file of doc module of RanZhi.
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
<?php include '../../../sys/common/view/ueditor.html.php';?>
<?php include '../../../sys/common/view/markdown.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<?php js::set('holders', $lang->doc->placeholder);?>
<?php js::set('libID', $libID);?>
<?php js::set('libType', $projectID ? 'project' : 'custom');?>
<?php if($this->session->docFrom == 'doc') $this->doc->setMenu(0, $libID, $moduleID);?>
<?php if($this->session->docFrom == 'project') $this->loadModel('project', 'proj')->setMenu($projects, $projectID);?>
<div class='panel with-menu page-content'>
  <div class='panel-heading'>
    <strong><small class='text-muted'><i class='icon icon-plus'></i></small> <?php echo $lang->doc->create;?></strong>
  </div>
  <div class='panel-body'>
    <form class='form-condensed' method='post' enctype='multipart/form-data' id='ajaxForm'>
      <table class='table table-form'> 
        <tr>
          <th class='w-80px'><?php echo $lang->doc->category;?></th>
          <td><?php echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control'");?></td>
          <td class='w-100px'>
            <label class='checkbox'><input type='checkbox' name='private' id='private' value='1' /><?php echo $lang->doc->private;?></label>
          </td>
        </tr>  
        <tr id='userTR'>
          <th><?php echo $lang->doc->users;?></th>
          <td colspan='2'><?php echo html::select('users[]', $users, '', "class='form-control chosen' multiple");?></td>
        </tr>
        <tr id='groupTR'>
          <th><?php echo $lang->doc->groups;?></th>
          <td colspan='2'><?php echo html::checkbox('groups', $groups);?></td>
        </tr>
        <tr>
          <th><?php echo $lang->doc->type;?></th>
          <td colspan='2'><?php echo html::radio('type', $lang->doc->types, 'text', "onclick=setType(this.value)");?></td>
        </tr>  
        <tr>
          <th><?php echo $lang->doc->title;?></th>
          <td colspan='2'><?php echo html::input('title', '', "class='form-control'");?></td>
        </tr> 
        <tr id='urlBox' class='hidden'>
          <th><?php echo $lang->doc->url;?></th>
          <td colspan='2'><?php echo html::input('url', '', "class='form-control'");?></td>
        </tr>  
        <tr id='contentBox'>
          <th><?php echo $lang->doc->content;?></th>
          <td colspan='2'>
            <div class='contenthtml'><?php echo html::textarea('content', '', "style='width:100%;height:200px'");?></div>
            <div class='contentmarkdown hidden'><?php echo html::textarea('contentMarkdown', '', "style='width:100%;height:200px'");?></div>
            <?php echo html::hidden('contentType', 'html');?>
          </td>
        </tr>  
        <tr>
          <th><?php echo $lang->doc->keywords;?></th>
          <td colspan='2'><?php echo html::input('keywords', '', "class='form-control'");?></td>
        </tr>  
        <tr>
          <th><?php echo $lang->doc->digest;?></th>
          <td colspan='2'><?php echo html::textarea('digest', '', "class='form-control' rows=3");?></td>
        </tr>  
        <tr>
          <th><?php echo $lang->doc->files;?></th>
          <td colspan='2'><?php echo $this->fetch('file', 'buildform', 'fileCount=2');?></td>
        </tr>  
        <tr>
          <th></th>
          <td colspan='2'><?php echo html::submitButton() . html::backButton() . html::hidden('lib', $libID) . html::hidden('project', $projectID);?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
