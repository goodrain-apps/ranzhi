<?php
/**
 * The zentaoAdmin view of entry module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     entry 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
include '../../common/view/header.html.php';?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><i class='icon-link'></i> <?php echo $lang->entry->bindUser;?></strong>
  </div>
  <div class='panel-body'>
    <form method='post' class='form-inline' id='entryForm'>
      <table class='table table-form w-p60'>
        <tr>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->entry->adminAccount;?></span>
              <?php echo html::input('account', '', "class='form-control'")?>
              <span class='input-group-addon'><?php echo $lang->entry->adminPassword;?></span>
              <?php echo html::input('password', '', "class='form-control'")?>
            </div>
          </td>
          <td><?php echo html::submitButton($lang->entry->nextStep);?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
