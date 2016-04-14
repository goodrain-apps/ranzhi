<?php
/**
 * The set style view of entry module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     entry 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
include '../../common/view/header.html.php';
?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><i class='icon-magic'></i> <?php echo $lang->entry->style;?></strong>
  </div>
  <div class='panel-body'>
    <form method='post' class='form-inline' id='entryForm'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->entry->logo;?></th>
          <td><?php echo html::file('files', "class='form-control'");?></td>
          <td colspan='2'><?php echo $lang->entry->note->logo;?></td>
        </tr>
        <tr>
          <th class='w-100px'><?php echo $lang->entry->open;?></th>
          <td class='w-p50'><?php echo html::select('open', $lang->entry->openList, $entry->open, 'class="form-control"');?></td>
          <td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->entry->control;?></th>
          <td><?php echo html::select('control', $lang->entry->controlList, $entry->control, "class='form-control'");?></td>
          <td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->entry->size;?></th>
          <td><?php echo html::select('size', $lang->entry->sizeList, $entry->size, "class='form-control'");?></td>
          <td id='custom' class='w-200px'>
            <div class='input-group'>
              <div class='input-group-addon'><?php echo $lang->entry->width;?></div>
              <?php echo html::input('width', isset($entry->width) ? $entry->width : '700', "class='form-control'");?>
              <div class='input-group-addon'><?php echo $lang->entry->height;?></div>
              <?php echo html::input('height', isset($entry->height) ? $entry->height : '538', "class='form-control'");?>
            </div>
          </td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->entry->position;?></th>
          <td><?php echo html::select('position', $lang->entry->positionList, $entry->position, "class='form-control'");?></td>
          <td></td><td></td>
        </tr>
        <tr>
          <th></th><td><?php echo html::submitButton() . html::backButton();?></td><td></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
