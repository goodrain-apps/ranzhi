<?php
/**
 * The detail view file of attend module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     attend 
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../../sys/common/view/treeview.html.php';?>
<?php include '../../../sys/common/view/chosen.html.php';?>
<?php include '../../../sys/common/view/datepicker.html.php';?>
<?php $lang->attend->abbrStatusList['rest'] = '';?>
<div id='menuActions'>
  <?php commonModel::printLink('attend', 'exportDetail', "date=$currentYear$currentMonth", "{$lang->attend->export}", "class='iframe btn btn-primary'")?>
</div>
<div class='with-side'>
  <div class='side'>
    <div class='panel'>
      <div class='panel-heading'><strong><?php echo $currentYear . $lang->year . $lang->attend->detail;?></strong></div>
      <div class='panel-body'>
      <?php 
        $lastmonth = $currentYear == date('Y') ? date('m') : 12;
        for($month = 1; $month <= $lastmonth; $month++)
        {
            $class = $month == $currentMonth ? 'btn-primary' : '';
            $month = $month < 10 ? '0' . $month : $month;
            echo "<div class='col-xs-3 monthDIV'>" . html::a(inlink('detail', "date=$currentYear$month"), $month . $lang->month, "class='btn btn-mini $class'") . '</div>';
        }
      ?>
      </div>
    </div>
    <div class='panel'>
      <div class='panel-heading'><strong><?php echo $lang->attend->search;?></strong></div>
      <div class='panel-body'>
        <form id='searchForm' method='post' action='<?php echo inlink('detail');?>'>
          <div class='form-group'>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->user->dept;?></span>
              <?php echo html::select('dept', $deptList, $dept, "class='form-control chosen'");?>
            </div>
          </div>
          <div class='form-group'>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->attend->user;?></span>
              <?php echo html::select('account', $userList, $account, "class='form-control chosen'");?>
            </div>
          </div>
          <div class='form-group'>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->attend->date;?></span>
              <?php echo html::input('date', $date, "class='form-control form-month'");?>
            </div>
          </div>
          <div class='form-group'><?php echo html::submitButton($lang->attend->search);?></div>
        </form>
      </div>
    </div>
  </div>
  <div class='main'>
    <div class='panel'>
      <div class='panel-heading text-center'>
        <strong><?php echo $fileName;?></strong>
      </div>
      <table class='table table-data table-bordered text-center table-fixedHeader'>
        <thead>
          <tr class='text-center'>
            <th><?php echo $lang->user->dept;?></th>
            <th><?php echo $lang->attend->user;?></th>
            <th><?php echo $lang->attend->date;?></th>
            <th><?php echo $lang->attend->dayName;?></th>
            <th><?php echo $lang->attend->status;?></th>
            <th><?php echo $lang->attend->signIn;?></th>
            <th><?php echo $lang->attend->signOut;?></th>
            <th><?php echo $lang->attend->ip;?></th>
          </tr>
        </thead>
        <?php foreach($attends as $attend):?>
        <tr>
          <td><?php echo $attend->dept;?></td>
          <td><?php echo $attend->realname;?></td>
          <td><?php echo $attend->date;?></td>
          <td><?php echo $attend->dayName;?>
          <td><?php echo empty($attend->desc) ? $attend->status : $attend->desc;?></td>
          <td><?php echo $attend->signIn;?></td>
          <td><?php echo $attend->signOut;?></td>
          <td><?php echo $attend->ip;?></td>
        </tr>
        <?php endforeach;?>
      </table>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
