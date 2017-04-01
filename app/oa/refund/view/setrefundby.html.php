<?php
/**
 * The set money view file of refund module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     refund
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<?php if(!$module):?>
<div class='with-side'>
  <div class='side'>
    <nav class='menu leftmenu affix'>
      <ul class='nav nav-primary'>
        <?php foreach($lang->refund->settings as $setting):?>
        <?php list($label, $module, $method) = explode('|', $setting);?>
        <li><?php commonModel::printLink($module, $method, '', $label);?></li>
        <?php endforeach;?>
      </ul>
    </nav>
  </div>
  <div class='main'>
<?php endif;?>
    <div class='panel'>
      <div class='panel-heading'><strong><?php echo $lang->refund->refundBy;?></strong></div>
      <div class='panel-body'>
        <form id='ajaxForm' class='form-inline' method='post'>
          <table class='table table-form table-condensed w-p50'>
            <tr>
              <th class='w-100px'><?php echo $lang->refund->refundBy;?></th>
              <td><?php echo html::select('refundBy', $users, isset($this->config->refund->refundBy) ? $this->config->refund->refundBy : '', "class='form-control chosen'");?></td>
            </tr>
            <tr>
              <th></th>
              <td><?php echo html::submitButton();?></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
<?php if(!$module):?>
  </div>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
