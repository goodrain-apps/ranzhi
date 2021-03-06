<?php
/**
 * The browse view file of file module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     file 
 * @version     $Id: browse.html.php 4029 2016-08-26 06:50:41Z liugang $
 * @link        http://www.ranzhico.com
 */
?>
<div class="modal-dialog" style='width:1000px'>
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title" id="ajaxModalTitle"><i class="icon-paper-clip"></i> <?php echo $lang->file->browse;?></h4>
    </div>
    <div class="modal-body">
      <table class='table table-bordered'>
        <thead>
          <tr>
            <th><?php echo $lang->file->id;?></th>
            <th><?php echo $lang->file->common;?></th>
            <th><?php echo $lang->file->extension;?></th>
            <th><?php echo $lang->file->size;?></th>
            <th><?php echo $lang->file->createdBy;?></th>
            <th><?php echo $lang->file->createdDate;?></th>
            <th><?php echo $lang->file->downloads;?></th>
            <th><?php echo $lang->actions;?></th>
          </tr>          
        </thead>
        <tbody>
          <?php foreach($files as $file):?>
          <tr class='text-middle'>
            <td><?php echo $file->id;?></td>
            <td>
              <?php
              if($file->isImage)
              {
                  echo html::a(inlink('download', "id=$file->id"), html::image($file->smallURL, "class='image-small' title='{$file->title}'"), "target='_blank'");
                  if($file->primary == 1) echo '<small class="label label-success">'. $lang->file->primary .'</small>';
              }
              else
              {
                  echo html::a(inlink('download', "id=$file->id"), "{$file->title}.{$file->extension}", "target='_blank'");
              }
              ?>
            </td>
            <td><?php echo $file->extension;?></td>
            <td><?php echo $file->size;?></td>
            <td><?php echo $file->createdBy;?></td>
            <td><?php echo $file->createdDate;?></td>
            <td><?php echo $file->downloads;?></td>
            <td>
            <?php
            echo html::a(inlink('edit',   "id=$file->id"), $lang->edit, "class='edit'");
            echo html::a(inlink('delete', "id=$file->id"), $lang->delete, "class='deleter'");
            if($file->isImage) echo html::a(inlink('setPrimary', "id=$file->id"), $lang->file->setPrimary, "class='option'");
            ?>
            </td>
          </tr>
          <?php endforeach;?>          
        </tbody>

      </table>
      <?php if(commonModel::hasPriv('file', 'upload')):?>
      <form id="fileForm" method='post' enctype='multipart/form-data' action='<?php echo inlink('upload', "objectType=$objectType&objectID=$objectID");?>'>
        <table class='table table-form'>
          <?php if($writeable):?>
          <tr>
            <td class='text-middle'><?php echo $lang->file->upload . $lang->file->limit;?></td>
            <td><?php echo $this->fetch('file', 'buildForm');?></td>
          </tr>
          <tr><td colspan='2' class='text-center'><?php echo html::submitButton();?></td></tr>
          <?php else:?>
          <tr><td colspan='2'><h5 class='text-danger'><?php echo $lang->file->errorUnwritable;?></h5></td></tr>
          <?php endif;?>
        </table>
      </form>
      <?php endif;?>
    </div>
  </div>
</div>
<script>
$(document).ready(function()
{   
    $.setAjaxForm('#fileForm', function(data)
    {
        if(data.result == 'success') $.reloadAjaxModal(1500);
    }); 
    $.setAjaxLoader('.edit', '#ajaxModal');
    $('a.option').click(function(data)
    {
        $.getJSON($(this).attr('href'), function(data) 
        {
            if(data.result == 'success')
            {
                $.reloadAjaxModal();
            }
            else
            {
                alert(data.message);
            }
        });
        return false;
    });

    $(".modal-backdrop").click(function()
    {
        $('.modal').modal('hide');
    });
});
</script>
