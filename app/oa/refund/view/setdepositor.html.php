<?php
/**
 * The set depositor view file of refund module of Ranzhi.
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
      <div class='panel-heading'><strong><?php echo $lang->refund->setDepositor;?></strong></div>
      <div class='panel-body'>
        <form id='ajaxForm' class='form-inline' method='post'>
          <table class='table table-form table-condensed w-p40'>
            <tr>
              <th class='w-100px text-center'><?php echo $lang->refund->depositor;?></th>
              <td><?php echo html::select('depositor', array('' => '') + $depositorList, isset($this->config->refund->depositor) ? $this->config->refund->depositor : '', "class='form-control'")?></td>
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
