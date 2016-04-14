<?php
/**
 * The member view file of project module of Ranzhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<?php $key = 0;?>
<form id='ajaxForm' method='post' action='<?php echo $this->createLink('oa.project', 'member', "projectID={$project->id}")?>'>
  <table class='table table-form table-hover no-td-padding'>
    <?php foreach($project->members as $member):?>
    <?php if($member->role == 'manager') continue;?>
    <tr>
      <td><?php echo html::select("member[$key]", $users, $member->account, "class='form-control chosen' onchange='updateMember()'")?></td>
      <td class='w-180px text-center'><?php echo html::select("role[$key]", $lang->project->roleList, $member->role, "class='form-control'")?></td>
      <td class='w-100px'></td>
    </tr>
    <?php $key++;?>
    <?php endforeach;?>
    <?php for($i = 0; $i < 3; $i++):?>
    <tr>
      <td><?php echo html::select("member[$key]", $users, '', "class='form-control chosen' onchange='updateMember()'")?></td>
      <td class='w-180px text-center'><?php echo html::select("role[$key]", $lang->project->roleList, 'member', "class='form-control'")?></td>
      <td class='w-100px'><i class='btn btn-mini icon-plus'></i> <i class='btn btn-mini icon-remove'></i></td>
    </tr>
    <?php $key++;?>
    <?php endfor;?>
  </table>
  <div class='alert alert-info'>
    <p title='<?php echo join("\r\n", $lang->project->roleTips)?>'><?php echo $lang->project->roleTip;?></p>
  </div>
  <div class='text-center'><?php echo html::submitButton();?></div>
</form>
<script type='text/template' id='memberTpl'>
  <tr>
    <td><?php echo html::select("member[key]", $users, '', "class='form-control' onchange='updateMember()'")?></td>
    <td class='w-180px text-center'><?php echo html::select("role[key]", $lang->project->roleList, 'member', "class='form-control'")?></td>
    <td class='w-100px'><i class='btn btn-mini icon-plus'></i> <i class='btn btn-mini icon-remove'></i></td>
  </tr>
</script>
<?php js::set('key', $key);?>
<?php js::set('manager', $project->PM);?>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
