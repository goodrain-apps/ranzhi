<?php 
/**
 * The edit view file of sales module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     sales 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><i class="icon-plus"></i> <?php echo $lang->sales->edit;?></strong>
  </div>
  <div class='panel-body'>
    <form method='post' id='ajaxForm' class='form-condensed'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->sales->name;?></th>
          <td class='w-p40'>
            <?php echo html::hidden('id', $group->id);?>
            <?php echo html::input('name', $group->name, "class='form-control'");?>
          </td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->sales->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', $group->desc, "rows='2' class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->sales->user;?></th>
          <td colspan='2'><div class='checkbox-users'><?php echo html::checkbox('users', $users, $group->users);?></div></td>
        </tr>
        <tr>
          <th></th>
          <td colspan='2'><?php echo html::submitButton() . '&nbsp;&nbsp;' . html::backButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
