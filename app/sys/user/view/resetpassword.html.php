<?php
/**
 * The resetpassword view file of user module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user 
 * @version     $Id: resetpassword.html.php 3138 2015-11-09 07:32:18Z chujilu $
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<section id="reset">
  <div class="box-radius">
    <div class="panel panel-default">
      <div class="panel-heading"><h4><strong><?php echo $lang->user->recoverPassword;?></strong></h4></div>
      <div class="panel-body">
        <form method='post' id='ajaxForm'>
          <table> 
            <tr>
              <td><?php echo html::input('account', '', "class='text-box' placeholder='{$lang->user->inputAccountOrEmail}'");?></td>
            </tr>
            <tr>
              <td><?php echo html::submitButton($lang->user->submit,'btn btn-primary btn-block');?></td>
            </tr>
          </table>
        </form>
      </div>
    </div>  
  </div>
</section>
<?php include '../../common/view/footer.html.php';?>
