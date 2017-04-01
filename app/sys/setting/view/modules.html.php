<?php
/**
 * The set modules view file of setting module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     setting
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('attend', commonModel::isAvailable('attend'));?>
<div class='panel'>
  <div class='panel-heading'>
    <?php echo $lang->setting->modules;?>
  </div>
  <div class='panel-body'>
    <form id='ajaxForm' class='form-inline' method='post'>
      <table class='table table-form table-condensed'>
        <tr><td><?php echo html::checkbox('modules', $lang->setting->moduleList, $config->setting->modules);?></td></tr>
        <tr><td><?php echo html::submitButton() . html::hidden('hidden', 1);?></td></tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
