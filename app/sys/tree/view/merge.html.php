<?php
/**
 * The merge view of tree module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     tree
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php
$webRoot   = $config->webRoot;
$jsRoot    = $webRoot . "js/";
$themeRoot = $webRoot . "theme/";
?>
<?php include '../../common/view/chosen.html.php';?>
<form method='post' class='form-horizontal' id='ajaxForm' action="<?php echo inlink('merge', 'type=' . $type);?>">
  <div class='panel'>
    <div class='panel-heading'><strong><i class='icon-link'></i> <?php echo $lang->tree->merge;?></strong></div>
    <div class='panel-body'>
      <div class='form-group'> 
        <label class='col-md-2 control-label'><?php echo $lang->category->origin;?></label>
        <div class='col-md-6'><?php echo html::select('originCategories[]', $categories, '', "class='chosen form-control' multiple='multiple'");?></div>
      </div>
      <div class='form-group'> 
        <label class='col-md-2 control-label'><?php echo $lang->category->target;?></label>
        <div class='col-md-6'><?php echo html::select('targetCategory', $categories, '', "class='chosen form-control'");?></div>
      </div>
    </div>
    <div class='form-group'>
      <label class='col-md-2'></label>
      <div class='col-md-6'><?php echo html::submitButton();?></div>
    </div>
  </div>
</form>
