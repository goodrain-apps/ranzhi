<?php
/**
 * The edit view file of doc module of RanZhi.
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
<?php include '../../../sys/common/view/chosen.html.php';?>
<?php if($doc->contentType == 'markdown') include '../../../sys/common/view/markdown.html.php';?>
<?php js::set('type',  $doc->type);?>
<?php js::set('libID', $doc->lib);?>
<?php js::set('libType', $doc->project ? 'project' : 'custom');?>
<?php js::set('private', $doc->private);?>
<?php if($this->session->docFrom == 'doc') $this->doc->setMenu(0, $doc->lib, $doc->module);?>
<?php if($this->session->docFrom == 'project') $this->loadModel('project', 'proj')->setMenu($projects, $doc->project);?>
<div class='with-menu page-content'>
  <form method='post' enctype='multipart/form-data' id='ajaxForm' action='<?php echo inlink('edit', "libID=$doc->id")?>'>
    <div class='col-md-8'>
      <div class='panel'>
        <div class='panel-heading'><strong><?php echo $lang->doc->edit;?></strong></div>
        <div class='panel-body'>
          <table class='table table-form table-data'> 
            <tr>
              <th class='w-80px'><?php echo $lang->doc->title;?></th>
              <td><?php echo html::input('title', $doc->title, "class='form-control'");?></td>
              <td class='w-100px'>
                <label class='checkbox'><input type='checkbox' name='private' id='private' value='1' /><?php echo $lang->doc->private;?></label>
              </td>
            </tr> 
            <tr id='userTR'>
              <th><?php echo $lang->doc->users;?></th>
              <td colspan='2'><?php echo html::select('users[]', $users, $doc->users, "class='form-control chosen' multiple");?></td>
            </tr>
            <tr id='groupTR'>
              <th><?php echo $lang->doc->groups;?></th>
              <td colspan='2'><?php echo html::checkbox('groups', $groups, $doc->groups);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->doc->digest;?></th>
              <td colspan='2'><?php echo html::textarea('digest', $doc->digest, "class='form-control' rows=2");?></td>
            </tr>  
            <tr id='urlBox' class='hidden'>
              <th><?php echo $lang->doc->url;?></th>
              <td colspan='2'><?php echo html::input('url', urldecode($doc->content), "class='form-control'");?></td>
            </tr>  
            <tr id='contentBox' class='hidden'>
              <th><?php echo $lang->doc->content;?></th>
              <td colspan='2'><?php echo html::textarea('content', $doc->content, "style='width:100%;height:200px'");?></td>
            </tr>  
            <tr>
              <th><?php echo $lang->doc->comment;?></th>
              <td colspan='2'><?php echo html::textarea('comment','', "style='width:100%;height:200px;'");?></td>
            </tr> 
            <tr>
              <th><?php echo $lang->doc->files;?></th>
              <td colspan='2'><?php echo $this->fetch('file', 'buildform', 'fileCount=2');?></td>
            </tr>
          </table>
        </div>
      </div>
      <?php echo $this->fetch('action', 'history', "objectType=doc&objectID={$doc->id}");?>
      <div class='page-actions'><?php echo html::submitButton() . html::backButton() .html::hidden('lib', $doc->lib);?></div>
    </div>
    <div class='col-md-4'>
      <div class='panel'>
        <div class='panel-heading'><strong><?php echo $lang->doc->basicInfo;?></strong></div>
        <div class='panel-body'>
          <table class='table table-form table-data'> 
            <tr>
              <th class='w-80px'><?php echo $lang->doc->category;?></th>
              <td><?php echo html::select('module', $moduleOptionMenu, $doc->module, "class='form-control'");?></td>
            </tr>  
            <tr>
              <th><?php echo $lang->doc->type;?></th>
              <td><?php echo $lang->doc->types[$doc->type];?></td>
            </tr>
            <tr>
              <th><?php echo $lang->doc->keywords;?></th>
              <td><?php echo html::input('keywords', $doc->keywords, "class='form-control'");?></td>
            </tr>  
          </table>
        </div>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
