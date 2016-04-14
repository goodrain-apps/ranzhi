<?php
/**
 * The show import view file of contact module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      liugang <liugang@cnezsoft.com> 
 * @package     contact
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<li id='bysearchTab'><?php echo html::a('#', "<i class='icon-search icon'></i>" . $lang->search->common)?></li>
<div class='panel'>
  <div class='panel-heading'>
    <strong class='text-danger'><?php echo sprintf($lang->contact->importResult, count($successList), count($errorList));?></strong>
    <div class="panel-actions pull-right">
      <?php echo html::a(inlink('browse', "mode=all&status=wait"), $lang->contact->showImport, "class='btn btn-primary'");?>
    </div>
  </div>
  <?php if(!empty($errorList)):?>
  <table class='table table-condensed table-hover table-striped error-report'>
    <thead>
      <tr class='text-left'>
          <th class='w-140px'><?php echo $lang->contact->realname;?></th>
          <th class='w-140px'><?php echo $lang->contact->company;?></th>
          <th class='w-110px'><?php echo $lang->contact->gender;?></th>
          <th class='w-110px'><?php echo $lang->contact->phone;?></th>
          <th class='w-150px'><?php echo $lang->contact->email;?></th>
          <th class='w-110px'><?php echo $lang->contact->qq;?></th>
          <th><?php echo $lang->contact->failReason;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($errorList as $contact):?>
      <tr>
        <td><?php echo $contact->realname;?></td>
        <td><?php echo $contact->company;?></td>
        <td><?php echo $contact->gender;?></td>
        <td><?php echo $contact->phone;?></td>
        <td><?php echo $contact->email;?></td>
        <td><?php echo $contact->qq;?></td>
        <td><?php echo $contact->reason;?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php endif ;?>
</div>
<?php include '../../common/view/footer.html.php';?>
