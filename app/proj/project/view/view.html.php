<?php
/**
 * The detail view file of project module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     project 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<div class='panel project-block'>
  <div class='panel-heading'>
    <strong><?php echo $project->name;?></strong>
  </div>
  <div class='panel-body'>
    <p class='info'><?php echo $project->desc;?></p>
    <div class='footerbar text-important'>
      <span><?php foreach($project->members as $member) if($member->role == 'manager') echo "<i class='icon icon-user'> </i>" . $users[$member->account];?></span>
      <span class=''><i class='icon icon-time'> </i><?php echo formatTime($project->begin, 'm-d') . ' ~ ' .  formatTime($project->end, 'm-d');?></span>
    </div>
  </div>
</div>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
