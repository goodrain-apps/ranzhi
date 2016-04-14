<?php
/**
 * The edit view of entry module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entry 
 * @version     $Id: edit.html.php 3205 2015-11-23 06:27:38Z daitingting $
 * @link        http://www.ranzhico.com
 */
include '../../common/view/header.html.php';
?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><i class='icon-edit'></i> <?php echo $lang->entry->edit;?></strong>
  </div>
  <div class='panel-body'>
    <form method='post' class='form form-inline' id='entryForm'>
      <table class='table table-form'>
        <?php if($entry->buildin):?>
        <tr>
          <th></th>
          <td><span class='text-info'><?php echo $this->lang->entry->editWarnning;?></span></td>
          <td><?php echo html::input('buildin', $entry->buildin, 'class="hidden"')?></td>
          <td></td>
        </tr>
        <?php endif?>
        <tr>
          <th class='w-100px'><?php echo $lang->entry->name;?></th>
          <td class='w-p50'>
            <div class='input-group'>
              <?php echo html::input('name', $entry->name, "class='form-control' placeholder='{$lang->entry->note->name}'");?>
              <div class="input-group-addon"></div>
              <?php echo html::input('abbr', $entry->abbr, "class='form-control' size='2' maxlength='2' placeholder='{$lang->entry->note->abbr}'");?>
              <div class='input-group-addon'>
                <label class="checkbox"><input type="checkbox" id="visible" name="visible" value="1" <?php if($entry->visible) echo 'checked';?>> <?php echo $lang->entry->note->visible;?></label>
              </div>
            </div>
          </td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->entry->login;?></th>
          <td><?php echo html::input('login', $entry->login, "class='form-control' placeholder='{$lang->entry->note->login}'");?></td>
        </tr>
        <tr>
          <th></th><td><?php echo html::submitButton() . html::backButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
