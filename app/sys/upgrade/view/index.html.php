<?php
/**
 * The html template file of index method of upgrade module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: index.html.php 3300 2015-12-02 02:36:17Z chujilu $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <?php if($type == 'todoFolder'):?>
  <div class='modal-dialog'>
    <div class='modal-header'>
      <h3><?php echo sprintf($lang->upgrade->removeTodo, $todoPath);?></h3>
    </div>
    <div class='panel-body'><?php echo sprintf($lang->upgrade->removeTodoTip, $todoPath, $todoPath)?></div>
    <div class='modal-footer'>
      <?php echo html::a(inlink('index'), $lang->upgrade->next, "class='btn btn-primary'");?>
    </div>
  </div>
  <?php else:?>
  <div class='modal-dialog'>
    <div class='modal-header'>
      <h3><?php echo $lang->upgrade->redeploy;?></h3>
    </div>
    <div class='panel-body'><?php echo $lang->upgrade->redeployDesc?></div>
    <div class='modal-footer'>
      <?php echo html::a(inlink('backup'), $lang->upgrade->next, "class='btn btn-primary'");?>
    </div>
  </div>
  <?php endif;?>
</div>
<?php include '../../install/view/footer.html.php';?>
