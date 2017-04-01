<?php 
/**
 * The create view of leads module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     leads 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><i class="icon-plus"></i> <?php echo $lang->leads->create;?></strong>
  </div>
  <div class='panel-body'>
    <form method='post' id='contactForm' class='form-condensed'>
      <div class='row'>
        <div class='col-md-9'>
          <table class='table table-form w-p60'>
            <tr class='required'>
              <th class='w-100px'><?php echo $lang->contact->realname;?></th>
              <td><?php echo html::input('realname', '', "class='form-control'");?></td>
            </tr>
            <tr class='required'>
              <th><?php echo $lang->contact->origin;?></th>
              <td><?php echo html::input('origin', '', "class='form-control'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->contact->company;?></th>
              <td><?php echo html::input('company', '', "class='form-control'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->contact->gender;?></th>
              <td><?php unset($lang->genderList->u); echo html::radio('gender', $lang->genderList, '');?></td>
            </tr>
            <tr>
              <th><?php echo $lang->contact->email;?></th>
              <td><?php echo html::input('email', '', "class='form-control' placeholder='{$lang->contact->emailTip}'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->contact->mobile;?></th>
              <td><?php echo html::input('mobile', '', "class='form-control'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->contact->phone;?></th>
              <td><?php echo html::input('phone', '', "class='form-control'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->contact->fax;?></th>
              <td><?php echo html::input('fax', '', "class='form-control'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->contact->qq;?></th>
              <td><?php echo html::input('qq', '', "class='form-control'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->contact->weixin;?></th>
              <td><?php echo html::input('weixin', '', "class='form-control'");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->contact->desc;?></th>
              <td><?php echo html::textarea('desc', '', "rows='3' class='form-control'");?></td>
            </tr>
            <tr>
              <th></th>
              <td>
                <?php echo html::submitButton() . '&nbsp;&nbsp;' . html::backButton();?>
                <div id='duplicateError' class='hide'></div>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </form>
  </div>
</div>
<div class='errorMessage hide'>
  <div class='alert alert-danger alert-dismissable'>
    <button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button>
    <button type='submit' class='btn btn-default' id='continueSubmit'><?php echo $lang->continueSave;?></button>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
