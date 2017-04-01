<?php
/**
 * The view file of doc module of RanZhi.
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
<?php echo css::internal($keTableCSS);?>
<?php js::set('libType', $doc->project ? 'project' : 'custom');?>
<?php js::set('libID ', $doc->lib);?>
<?php if($this->session->docFrom == 'project') $this->loadModel('project', 'proj')->setMenu($projects, $doc->project);?>
<?php if($this->session->docFrom == 'doc'):?>
<?php $this->doc->setMenu(0, $doc->lib, $doc->module);?>
<div id='menuActions'>
  <?php $browseLink = $this->session->docList ? $this->session->docList : inlink('browse');?>
  <?php echo html::a($browseLink, $lang->goback, "class='btn btn-default'");?>
</div>
<?php endif;?>
<div class='row with-menu page-content'>
  <div class='col-md-8'>
    <div class='panel'>
      <div class='panel-heading'>
        <strong><?php echo $lang->doc->view;?></strong>
        <div class='panel-actions pull-right' style='margin:0'>
          <?php if($doc->version > 1):?>
          <span class='dropdown'>
            <a href='#' data-toggle='dropdown' class='text-muted'><?php echo '#' . $version;?> <span class='caret'></span></a>
              <ul class='dropdown-menu'>
              <?php
              for($i = $doc->version; $i >= 1; $i --)
              {
                  $class = $i == $version ? " class='active'" : '';
                  echo '<li' . $class .'>' . html::a(inlink('view', "docID=$doc->id&version=$i"), '#' . $i) . '</li>';
              }
              ?>
            </ul>
          </span>
          <?php endif; ?>
        </div>
      </div>
      <div class='panel-body doc-content'>
        <table class='table table-form table-data'>
          <tr>
            <th class='w-70px'><?php echo $lang->doc->title;?></th>
            <td>
              <?php echo "#" . $doc->id . ' ' . $doc->title;?>
              <?php if($doc->deleted):?>
              <span class='label label-danger'><?php echo $lang->doc->deleted;?></span>
              <?php endif;?>
            </td>
          </tr>
          <tr>
            <th class='w-70px'><?php echo $lang->doc->digest;?></th>
            <td><?php echo $doc->digest;?></td>
          </tr>
          <?php if($doc->type == 'url'):?>
          <tr>
            <th><?php echo $lang->doc->url;?></th>
            <td><?php echo html::a(urldecode($doc->content), '', "target='_blank'");?></td>
          </tr>
          <?php endif;?>
          <?php if($doc->type == 'text'):?>
          <tr>
            <th class='text-top'><?php echo $lang->doc->content;?></th>
            <td class='content'></td>
          </tr>
          <tr>
            <td colspan='2' class='content'><?php echo $doc->content;?></td>
          </tr>
          <?php endif;?>
          <?php if($doc->files):?>
          <tr>
            <th><?php echo $lang->files;?></th>
            <td><?php echo $this->fetch('file', 'printFiles', array('files' => $doc->files, 'fieldset' => 'false'));?></td>
          </tr>
          <?php endif;?>
        </table>
      </div>
    </div>
    <?php echo $this->fetch('action', 'history', "objectType=doc&objectID={$doc->id}");?>
    <div class='page-actions'>
      <?php
      $params = "docID=$doc->id";
      if(!$doc->deleted)
      {
          echo "<div class='btn-group'>";
          commonModel::printLink('doc', 'edit', $params, $lang->edit, "class='btn'");
          commonModel::printLink('doc', 'delete', $params, $lang->delete, "class='deleter btn'");
          echo "</div>";
      }
  
      $browseLink = $this->session->docList ? $this->session->docList : inlink('browse');
      echo html::a($browseLink, $lang->goback, "class='btn btn-default'");
      ?>
    </div>
  </div>
  <div class='col-md-4'>
    <div class='panel'>
      <div class='panel-heading'><strong><?php echo $lang->doc->basicInfo;?></strong></div>
      <div class='panel-body'>
        <table class='table table-info'>
          <?php if($doc->project):?>
          <tr>
            <th class='w-80px'><?php echo $lang->doc->project;?></th>
            <td><?php echo zget($projects, $doc->project);?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th class='w-80px'><?php echo $lang->doc->lib;?></th>
            <td><?php echo $lib;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->category;?></th>
            <td><?php echo $doc->moduleName ? $doc->moduleName : '/';?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->type;?></th>
            <td><?php echo $lang->doc->types[$doc->type];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->keywords;?></th>
            <td><?php echo $doc->keywords;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->createdBy;?></th>
            <td><?php echo $users[$doc->createdBy];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->createdDate;?></th>
            <td><?php echo $doc->createdDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->editedBy;?></th>
            <td><?php echo $users[$doc->editedBy];?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->editedDate;?></th>
            <td><?php echo $doc->editedDate;?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
