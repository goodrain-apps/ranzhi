<?php
/**
 * The index view file of doc module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.html.php';?>
<?php
$allLibs = array();
$allLibs['project'] = $projects;
$allLibs['custom']  = $customLibs;
?>
<div id='navBarActions'><?php commonModel::printLink('doc', 'createlib', '', '<i class="icon-plus"></i> ' . $this->lang->doc->createLib, "id='createButton' class='btn btn-primary' data-toggle='modal'");?></div>
<div id='libs'>
  <?php foreach($allLibs as $libsName => $libs):?>
    <?php if(empty($libs)) continue;?>
    <?php if($libsName === 'project'): ?>
    <div class='row'>
      <?php
      $objectNum   = 1;
      $objectCount = count($libs);
      ?>
      <?php foreach($libs as $project):?>
      <?php if($objectCount > 8 and $objectNum == 8):?>
      <div class='col-md-3'>
        <div class='libs-group clearfix lib-more'>
          <?php echo html::a(inlink('allLibs', "type=$libsName"), "{$lang->more}{$lang->doc->libTypeList['project']}<i class='icon icon-double-angle-right'></i>", "title='$lang->more' class='more'")?>
        </div>
      </div>
      <?php break;?>
      <?php endif;?>
      <?php if(isset($subLibs[$project->id])):?>
      <div class='col-md-3'>
        <?php
        $i = 0;
        $subLibCount = count($subLibs[$project->id]);
        ?>
        <div class='libs-group-heading libs-project-heading'>
          <?php
          $label = $objectNum == 1 ? "<span class='label label-success'>{$lang->doc->libTypeList['project']}</span> " : '';
          echo html::a(inlink('projectLibs', "projectID=$project->id"), $label . $project->name, "title='{$project->name}'");
          if($subLibCount > 3) echo html::a(inlink('projectLibs', "projectID=$project->id"), "{$lang->more}<i class='icon icon-double-angle-right'></i>", "title='{$lang->more}' class='pull-right'");
          ?>
        </div>
        <div class='libs-group clearfix'>
          <?php
          $widthClass = 'w-lib-p100';
          if($subLibCount == 2) $widthClass = 'w-lib-p50';
          if($subLibCount >= 3) $widthClass = 'w-lib-p33';
          ?>
          <?php foreach($subLibs[$project->id] as $libID => $libName):?>
          <?php
          if($libID == 'files') $libLink = inlink('showFiles', "projectID=$project->id");
          else                  $libLink = inlink('browse', "libID=$libID&moduleID=&projectID=$project->id");
          ?>
          <a class='lib <?php echo $widthClass?>' title='<?php echo $libName?>' href='<?php echo $libLink ?>'>
            <i class='icon icon-2x icon-folder-open-alt'></i>
            <div class='lib-name' title='<?php echo $libName?>'><?php echo $libName?></div>
          </a>
          <?php if($i >= 2) break;?>
          <?php $i++;?>
          <?php endforeach; ?>
        </div>
      </div>
      <?php $objectNum++;?>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <hr />
    <?php else:?>
      <div class='row clearfix'>
      <?php
      $objectNum   = 1;
      $objectCount = count($libs);
      ?>
      <?php foreach($libs as $libID => $libName):?>
        <?php if($objectCount > 8 and $objectNum == 8):?>
        <div class='col-md-3'>
          <div class='libs-group clearfix lib-more'>
            <?php echo html::a(inlink('allLibs', "type=$libsName"), "{$lang->more}{$lang->doc->libTypeList['custom']}<i class='icon icon-double-angle-right'></i>", "title='$lang->more' class='more'")?>
          </div>
        </div>
        <?php break;?>
        <?php endif;?>
        <div class='col-md-3'>
          <div class='libs-group-heading libs-custom-heading'>
            <?php
            if($objectNum == 1) echo "<span class='label label-info lable-custom'>{$lang->doc->customLibs}</span> ";
            echo html::a(inlink('browse', "libID=$libID"), $libName, "title='{$libName}'")
            ?>
          </div>
        </div>
        <?php $objectNum++;?>
      <?php endforeach; ?>
    <?php endif; ?>
  <?php endforeach;?>
</div>
<?php include '../../../sys/common/view/footer.html.php';?>

