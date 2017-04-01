<?php
/**
 * The set time file of backup module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     backup
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<form method='post' id='ajaxForm' action="<?php echo helper::createLink('backup', 'setSaveDays');?>">
  <table class='table table-borderless'>
    <tr>
      <th class='w-70px text-middle'><?php echo $lang->backup->saveDays;?></th>
      <td class='w-200px'>
        <div class='input-group'>
          <?php echo html::input('saveDays', isset($config->backup->saveDays) ? $config->backup->saveDays : '30', "class='form-control'")?>
          <span class='input-group-addon'><?php echo $lang->date->day;?></span>
        </div>
      </td> 
      <td><?php echo html::submitButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
