<?php
/**
 * The set begin date view file of attend module of RanZhi.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     attend 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<?php include '../../../sys/common/view/datepicker.html.php';?>
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
      <div class='panel-heading'><?php echo $lang->attend->beginDate->personal;?></div>
      <div class='panel-body'>
        <form id='ajaxForm' method='post'>
          <table class='table table-condensed table-borderless w-p40'>
            <?php foreach($config->attend->beginDate as $account => $date):?>
            <?php if($account == 'company') continue;?>
            <tr>
              <td class='w-120px'><?php echo html::select("account[]", $users, $account, "class='form-control chosen'");?></td>
              <td><?php echo html::input("date[]", $date, "class='form-control form-date'");?></td>
              <td class='w-100px'>
                <a class='btn addItem'><i class='icon icon-plus'></i></a>
                <a class='btn delItem'><i class='icon icon-remove'></i></a>
              </td>
            </tr>
            <?php endforeach;?>
            <tr>
              <td class='w-120px'><?php echo html::select("account[]", $users, '', "class='form-control chosen'");?></td>
              <td><?php echo html::input("date[]", '', "class='form-control form-date'");?></td>
              <td class='w-100px'>
                <a class='btn addItem'><i class='icon icon-plus'></i></a>
                <a class='btn delItem'><i class='icon icon-remove'></i></a>
              </td>
            </tr>
            <tr>
              <td><?php echo html::submitButton();?></td>
              <td></td>
              <td></td>
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
