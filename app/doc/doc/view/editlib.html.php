<?php
/**
 * The editlib view file of doc module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<?php js::set('private', $lib->private);?>
<form method='post' id='ajaxForm' action='<?php echo inlink('editLib', "libID=$libID")?>'>
  <table class='table table-form'>
    <?php if(!empty($lib->project)):?>
    <tr>
      <th class='w-100px'><?php echo $lang->doc->project?></th>
      <td><?php echo $project->name;?></td>
    </tr>
    <?php endif;?>
    <tr>
      <th class='w-100px'><?php echo $lang->doc->libName;?></th>
      <td><?php echo html::input('name', $lib->name, "class='form-control'");?></td>
      <td class='w-120px'>
        <label class='checkbox'><input type='checkbox' name='private' id='private' value='1' /><?php echo $lang->doc->private;?></label>
      </td>
    </tr>
    <tr id='userTR'>
      <th><?php echo $lang->doc->users;?></th>
      <td colspan='2'><?php echo html::select('users[]', $users, $lib->users, "class='form-control chosen' multiple");?></td>
    </tr>
    <tr id='groupTR'>
      <th><?php echo $lang->doc->groups;?></th>
      <td colspan='2'><?php echo html::checkbox('groups', $groups, $lib->groups);?></td>
    </tr>
  </table>
  <div class='text-center'><?php echo html::submitButton();?></div>
</form>
<?php include '../../../sys/common/view/footer.modal.html.php'; ?>
