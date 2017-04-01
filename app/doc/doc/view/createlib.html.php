<?php
/**
 * The createlib view file of doc module of RanZhi.
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
<form method='post' id='ajaxForm' action='<?php echo inlink('createLib')?>'>
  <table class='table table-form'>
    <tr>
      <th class='w-100px'><?php echo $lang->doc->libType?></th>
      <td>
        <?php if($this->session->docFrom == 'project'):?>
        <?php echo html::select('libType', $lang->doc->libTypeList, 'project', "class='form-control' readonly='readonly'")?>
        <?php else:?>
        <?php echo html::select('libType', $lang->doc->libTypeList, $type, "class='form-control'")?>
        <?php endif;?>
      </td>
    </tr>
    <tr class='project hidden'>
      <th><?php echo $lang->doc->project?></th>
      <td>
        <?php if($this->session->docFrom == 'project'):?>
        <?php echo html::select('project', $projects, $projectID, "class='form-control chosen' readonly='readonly'")?>
        <?php else:?>
        <?php echo html::select('project', $projects, $projectID, "class='form-control chosen'")?>
        <?php endif;?>
      </td>
    </tr>
    <tr>
      <th class='w-100px'><?php echo $lang->doc->libName;?></th>
      <td><?php echo html::input('name', '', "class='form-control'");?></td>
      <td class='w-100px'>
        <label class='checkbox'><input type='checkbox' name='private' id='private' value='1' /><?php echo $lang->doc->private;?></label>
      </td>
    </tr>
    <tr id='userTR'>
      <th><?php echo $lang->doc->users;?></th>
      <td colspan='2'><?php echo html::select('users[]', $users, '', "class='form-control chosen' multiple");?></td>
    </tr>
    <tr id='groupTR'>
      <th><?php echo $lang->doc->groups;?></th>
      <td colspan='2'><?php echo html::checkbox('groups', $groups);?></td>
    </tr>
  </table>
  <div class='text-center'><?php echo html::submitButton();?></div>
</form>
<?php js::set('libID', 'createLib')?>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
