<?php
/**
 * The import view file of file module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      liugang <liugang@cnezsoft.com> 
 * @package     file 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/common/view/header.modal.html.php';?>
<form method='post' id='ajaxForm' enctype='multipart/form-data' action='<?php echo inlink('import')?>'>
  <table class='w-p100'>
    <tr>
      <td>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->importFile;?></span>
          <?php echo html::file('files', "class='form-control'")?>
        </div>
      </td>
      <td><?php echo html::submitButton($lang->import);?></td>
    </tr>
  </table>
</form>
<?php include '../../../sys/common/view/footer.modal.html.php';?>
