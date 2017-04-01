<?php
/**
 * The personal view file of attend module of Ranzhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     attend
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../../sys/common/view/treeview.html.php';?>
<div class='with-side'>
  <div class='side'>
    <div class='panel panel-sm'>
      <div class='panel-body'>
        <ul class='tree' data-collapsed='true'>
          <?php foreach($yearList as $year):?>
          <li class='<?php echo $year == $currentYear ? 'active' : ''?>'>
            <?php commonModel::printLink('attend', 'personal', "date=$year", $year);?>
            <ul>
              <?php foreach($monthList[$year] as $month):?>
              <li class='<?php echo ($year == $currentYear and $month == $currentMonth) ? 'active' : ''?>'>
                <?php commonModel::printLink('attend', 'personal', "date=$year$month", $year . $month);?>
              </li>
              <?php endforeach;?>
            </ul>
          </li>
          <?php endforeach;?>
        </ul>
      </div>
    </div>
  </div>
  <div class='main'>
    <div class='row'>
      <?php
      $weekIndex = 0;
      if($this->config->attend->workingDays > 7)
      {
          $startDate     = strtotime("$currentYear-$currentMonth-01");
          $startDate     = date('w', $startDate) == 0 ? $startDate : strtotime("last Sunday", $startDate);
          $endDate       = strtotime("next month -1 day $currentYear-$currentMonth-01");
          $endDate       = date('w', $endDate) == 6 ? $endDate : strtotime("next Saturday", $endDate);
          $firstDayIndex = 0;
          $lastDayIndex  = 6;
      }
      else
      {
          $startDate     = strtotime("$currentYear-$currentMonth-01");
          $startDate     = date('w', $startDate) == 1 ? $startDate : strtotime("last Monday", $startDate);
          $endDate       = strtotime("next month -1 day $currentYear-$currentMonth-01");
          $endDate       = date('w', $endDate) == 0 ? $endDate : strtotime("next Sunday", $endDate);
          $firstDayIndex = 1;
          $lastDayIndex  = 0;
      }
      ?>
      <?php while($startDate <= $endDate):?>
      <?php $dayIndex = date('w', $startDate);?>
      <?php if($dayIndex == $firstDayIndex):?>
      <div class='col-xs-4'>
        <div class='panel'>
          <div class='panel-body no-padding'>
            <table class='table table-data text-center'>
              <thead>
                <tr>
                  <th class='w-90px'><?php echo $lang->attend->weeks[$weekIndex];?></th>
                  <th class='text-center'><?php echo $lang->attend->dayName;?></th>
                  <th class='text-center'><?php echo $lang->attend->signIn;?></th>
                  <th class='text-center'><?php echo $lang->attend->signOut;?></th>
                  <th class='text-center w-100px'><?php echo $lang->actions . '/' . $lang->attend->status;?></th>
                </tr>
              </thead>
        <?php endif;?>
              <?php $currentDate = date("Y-m-d", $startDate);?>
              <?php if(isset($attends[$currentDate])):?>
              <?php $status = $attends[$currentDate]->status;?>
              <?php $reason = $attends[$currentDate]->reason;?>
              <?php $reviewStatus = isset($attends[$currentDate]->reviewStatus) ? $attends[$currentDate]->reviewStatus : '';?>
              <tr class="attend-<?php echo $status?> <?php echo (date('m', $startDate) == $currentMonth) ? '' : 'otherMonth'?>" title='<?php echo $lang->attend->statusList[$status]?>'>
                <td><?php echo $currentDate;?></td>
                <td><?php echo $lang->datepicker->abbrDayNames[$dayIndex]?></td>
                <td class='attend-signin'>
                  <?php $signIn = substr($attends[$currentDate]->signIn, 0, 5);?>
                  <?php if(strpos(',late,absent,rest,', ",$status,") !== false) $signIn = $lang->attend->statusList[$status];?>
                  <?php if($status == 'both') $signIn = $lang->attend->statusList['late'];?>
                  <?php echo $signIn;?>
                </td>
                <td class='attend-signout'>
                  <?php $signOut = substr($attends[$currentDate]->signOut, 0, 5);?>
                  <?php if(strpos(',early,absent,rest,', ",$status,") !== false) $signOut = $lang->attend->statusList[$status];?>
                  <?php if($status == 'both') $signOut = $lang->attend->statusList['early'];?>
                  <?php echo $signOut;?>
                </td>
                <td>
                  <?php
                  if(strpos(',rest,normal,leave,makeup,overtime,lieu,trip,egress,', ",$status,") === false):
                  $edit       = $reviewStatus == 'wait' ? $lang->attend->edited : $lang->attend->edit;
                  $leave      = $reason == 'leave' ? $lang->attend->leaved : $lang->attend->leave;
                  $overtime   = $reason == 'overtime' ? $lang->attend->overtimed : $lang->attend->overtime;
                  $lieu       = $reason == 'lieu' ? $lang->attend->lieud : $lang->attend->lieu;
                  ?>
                  <?php if($reviewStatus == 'wait' or strpos(',late,early,both,', ",$status,") !== false):?>
                  <?php echo html::a($this->createLink('attend', 'edit', "date=" . str_replace('-', '', $currentDate)), $edit, "data-toggle='modal' data-width='500px'");?>
                  <?php elseif($reason == 'leave'): ?>
                  <?php commonModel::printLink('leave', 'create', "date=" . str_replace('-', '', $currentDate), $leave, "data-toggle='modal' data-width='700px'");?>
                  <?php elseif($reason == 'lieu'): ?>
                  <?php commonModel::printLink('lieu', 'create', "date=" . str_replace('-', '', $currentDate), $lieu, "data-toggle='modal' data-width='700px'");?>
                  <?php elseif($reason == 'overtime'): ?>
                  <?php commonModel::printLink('overtime', 'create', "date=" . str_replace('-', '', $currentDate), $overtime, "data-toggle='modal' data-width='700px'");?>
                  <?php else:?>
                  <div class='dropdown'>
                    <a href='javascript:;' data-toggle='dropdown'><?php echo $lang->actions;?><span class='caret'></span></a>
                    <ul role='menu' class='dropdown-menu'>
                      <?php if($reason == '' or $reason == 'normal')   echo "<li>" . html::a($this->createLink('attend', 'edit', "date=" . str_replace('-', '', $currentDate)), $edit, "data-toggle='modal' data-width='500px'") . "</li>";?>
                      <?php if($reason == '' or $reason == 'leave')    commonModel::printLink('leave', 'create', "date=" . str_replace('-', '', $currentDate), $leave, "data-toggle='modal' data-width='700px'", '', '', 'li');?>
                      <?php if($reason == '' or $reason == 'overtime') commonModel::printLink('overtime', 'create', "date=" . str_replace('-', '', $currentDate), $lang->attend->overtime, "data-toggle='modal' data-width='700px'", '', '', 'li');?>
                      <?php if($reason == '' or $reason == 'egress')   commonModel::printLink('egress', 'create', '', $lang->attend->egress, "data-toggle='modal' data-width='700px'", '', '', 'li');?>
                      <?php if($reason == '' or $reason == 'trip')     commonModel::printLink('trip', 'create', '', $lang->attend->trip, "data-toggle='modal' data-width='700px'", '', '', 'li');?>
                      <?php if($reason == '' or $reason == 'lieu')     commonModel::printLink('lieu', 'create', '', $lang->attend->lieu, "data-toggle='modal' data-width='700px'", '', '', 'li');?>
                    </ul>
                  </div>
                  <?php endif;?>
                  <?php else:?>
                  <?php if($status == 'rest'):?>
                  <span class='attend-<?php echo $status;?>'>
                    <?php commonModel::printLink('overtime', 'create', "date=" . str_replace('-', '', $currentDate), $lang->attend->overtime, "data-toggle='modal' data-width='700px'");?>
                  </span>
                  <?php elseif($status == 'normal'):?> 
                  <span class='attend-<?php echo $status;?>'>
                    <?php echo $lang->attend->statusList[$status];?>
                  </span>
                  <?php else:?>
                  <div class='dropdown'>
                    <a href='javascript:;' data-toggle='dropdown'>
                      <span class='attend-<?php echo $status;?>'>
                        <?php echo $lang->attend->statusList[$status];?>
                        <?php if(strpos(',leave,makeup,overtime,lieu,trip,egress,', ",$status,") !== false and $attends[$currentDate]->desc) echo ' ' . $attends[$currentDate]->desc . 'h';?>
                      </span>
                      <span class='caret'></span>
                    </a>
                    <ul role='menu' class='dropdown-menu'>
                      <li><?php echo html::a($this->createLink('attend', 'edit', "date=" . str_replace('-', '', $currentDate)), $edit, "data-toggle='modal' data-width='500px'") . "</li>";?>
                    </ul>
                  </div>
                  <?php endif;?>
                  <?php endif;?>
                </td>
              </tr>
              <?php else:?>
              <tr class="<?php echo (date('m', $startDate) == $currentMonth) ? '' : 'otherMonth'?>">
                <td><?php echo $currentDate;?></td>
                <td><?php echo $lang->datepicker->abbrDayNames[$dayIndex]?></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <?php endif;?>
      <?php if($dayIndex == $lastDayIndex):?>
            </table>
          </div>
        </div>
        <?php $weekIndex += 1;?>
      </div>
      <?php endif;?>
      <?php $startDate = strtotime('+1 day', $startDate);?>
      <?php endwhile;?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
