<?php
/**
 * The setting view file of leads module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     leads 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='with-side'>
  <div class='side'>
    <nav class='menu leftmenu affix'>
      <ul class='nav nav-primary'>
        <li class='active'><?php commonModel::printLink('leads', 'setting', '', "{$lang->leads->applyRule}");?></li>
      </ul>
    </nav>
  </div>
  <div class='main'>
    <div class='panel'>
      <div class='panel-heading'>
        <strong><i class='icon-exchange'></i> <?php echo $lang->leads->applyRule;?></strong>
      </div>
      <div class='panel-body'>
        <form id='ajaxForm' method='post'>
          <table class='table table-form table-condensed'>
            <tr>
              <th class='w-130px'><?php echo $lang->leads->applyLimit;?></th>
              <td class='w-200px'><?php echo html::select('applyLimit', $lang->leads->applyLimitList, $applyLimit, "class='form-control chosen'")?></td>
              <td style='padding-left:5px;'></td>
            </tr>
            <tr>
              <th><?php echo $lang->leads->applyRemain;?></th>
              <td><?php echo html::select('applyRemain', $lang->leads->applyRemainList, $applyRemain, "class='form-control chosen'")?></td>
              <td style='padding-left:5px;'><a href='javascript:void(0)' data-original-title="<?php echo $lang->leads->tips->applyRemain;?>" data-placement='right' data-toggle='tooltip'><i class='icon icon-question-sign'></i></a></td>
            </tr>
            <tr>
              <th></th>
              <td colspan='2'><?php echo html::submitButton();?></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
