<?php
/**
 * The set report unit view file of trade module of RanZhi.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     trade 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<form id='ajaxForm' method='post' action='<?php echo inlink('setReportUnit');?>'>
  <div class='input-group'>
    <span class='input-group-addon'><?php echo $lang->trade->report->unit;?></span>
    <?php echo html::select('unit', $lang->trade->report->unitList, $config->trade->report->unit, "class='form-control'");?>
    <span class='input-group-btn'><?php echo html::submitButton();?></span>
  </div>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
