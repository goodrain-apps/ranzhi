<?php
/**
 * The binduser view of entry module of RanZhi.
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
    <form id='ajaxForm' method='post' class='form-inline'>
      <table class='table table-form w-p50'>
        <?php $i=1;?>
        <?php foreach($ranzhiUsers as $account => $realname):?>
        <tr>
          <th class='w-100px'><?php echo html::input("ranzhiAccounts[$i]", $account, "class='hide'");?> <?php echo $realname;?></th>
          <td>
            <?php if(!empty($bindUsers[$account])):?>
            <?php echo html::select("zentaoAccounts[$i]", $zentaoUsers, $bindUsers[$account], "class='form-control'");?>
            <?php elseif(!empty($zentaoUsers[$account])):?>
            <?php echo html::select("zentaoAccounts[$i]", $zentaoUsers, $account, "class='form-control'");?>
            <?php else:?>
            <div class='input-group'>
              <?php echo html::select("zentaoAccounts[$i]", $zentaoUsers, '', "class='form-control'");?>
              <span class='input-group-addon'>
                <label class='checkbox'><input type='checkbox' name="createUsers[<?php echo $i;?>]" id='createusers' value='1' /> <?php echo $lang->entry->createUser;?></label>
              </span>
            </div>
            <?php endif;?>
          </td>
        </tr>
        <?php $i++;?> 
        <?php endforeach;?>
        <tr>
          <th></th><td><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
