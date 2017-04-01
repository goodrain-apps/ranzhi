<?php
/**
 * The settings view file of attend module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     attend
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
        <li><?php commonModel::printLink('attend', 'settings', '', "{$lang->attend->settings}");?></li>
        <li><?php commonModel::printLink('attend', 'personalsettings', '', $lang->attend->personalSettings);?></li> 
        <li><?php commonModel::printLink('attend', 'setmanager', '', "{$lang->attend->setManager}");?></li>
      </ul>
    </nav>
  </div>
  <div class='main'>
<?php endif;?>
    <div class='panel'>
      <div class='panel-heading'><?php echo $lang->attend->setManager;?></div>
      <div class='panel-body'>
        <form id='deptForm' method='post'>
          <table class='table table-form table-condensed w-p40'>
            <?php foreach($deptList as $id => $dept):?>
            <tr>
              <th class='w-150px'><?php echo $dept->name;?></th>
              <td class='w-300px'><?php echo html::select("dept[$id]", $users, trim($dept->moderators, ','), "class='form-control chosen'")?></td>
            </tr>
            <?php endforeach;?>
            <?php if(!empty($deptList)):?>
            <tr><th></th><td><?php echo html::submitButton();?></td></tr>
            <?php else:?>
            <tr><th></th><td><?php commonModel::printLink('team.tree', 'browse', 'type=dept', $lang->attend->setDept, "class='btn btn-primary setDept'");?></td></tr>
            <?php endif;?>
          </table>
        </form>
      </div>
    </div>
<?php if(!$module):?>
  </div>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
