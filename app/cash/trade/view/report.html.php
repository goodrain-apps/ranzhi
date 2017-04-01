<?php
/**
 * The report view file of trade module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     trade
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include '../../../sys/common/view/chart.html.php';?>
<?php js::set('mode', 'report');?>
<div class='panel panel-sm'>
  <div class='panel-heading'>
    <div class='date dropdown'>
      <?php $currentMonthTip = $currentMonth == '00' ? '' : $currentMonth . $lang->month;?>
      <button type='button' class='btn btn-sm btn-default dropdown-toggle' data-toggle='dropdown'><?php echo $currentYear . $lang->year . $currentMonthTip;?> <span class="caret"></span></button>
      <ul class='dropdown-menu'>
        <?php foreach($tradeYears as $tradeYear):?>
        <li class='dropdown-submenu'>
          <?php echo html::a(helper::createLink('trade', 'report', "date=$tradeYear&currency=$currentCurrency&unit=$currentUnit"), $tradeYear);?>
          <ul class='dropdown-menu'>
            <?php foreach($tradeMonths[$tradeYear] as $tradeMonth):?>
            <li><?php echo html::a(helper::createLink('trade', 'report', "date=$tradeYear$tradeMonth&currency=$currentCurrency&unit=$currentUnit"), $tradeMonth . $lang->month);?></li>
            <?php endforeach;?>
            <li><?php echo html::a(helper::createLink('trade', 'report', "date={$tradeYear}00&currency=$currentCurrency&unit=$currentUnit"), $lang->trade->fullYear);?></li>
          </ul>
        </li>
        <?php endforeach;?>
      </ul>
    </div>
    <div class='currency dropdown'>
      <button type='button' class='btn btn-sm btn-default dropdown-toggle' data-toggle='dropdown'><?php echo $currencyList[$currentCurrency];?> <span class="caret"></span></button>
      <ul class='dropdown-menu'>
        <?php foreach($currencyList as $key => $currency):?>
        <li><?php echo html::a(helper::createLink('trade', 'report', "date=$currentYear$currentMonth&currency=$key&unit=$currentUnit"), $currency);?></li>
        <?php endforeach;?>
      </ul>
    </div>
    <div class='unit dropdown'>
      <button type='button' class='btn btn-sm btn-default dropdown-toggle' data-toggle='dropdown'><?php echo $lang->trade->report->unitList[$currentUnit];?> <span class='caret'></span></button>
      <ul class='dropdown-menu'>
        <?php foreach($lang->trade->report->unitList as $key => $unit):?>
        <li><?php echo html::a(helper::createLink('trade', 'report', "date=$currentYear$currentMonth&currency=$currentCurrency&unit=$key"), $unit);?></li>
        <?php endforeach;?>
      </ul>
    </div>
  </div>
  <div class='panel-body'>
    <?php if($currentMonth == '00'):?>
    <table class='table active-disabled'>
      <tr>
        <td colspan='3' class='annual'>
          <div class='chart-wrapper text-center'>
            <h5><?php echo $currentYear . $lang->trade->report->annual . '(' . $currencyList[$currentCurrency] . ':' . $lang->trade->report->unitList[$currentUnit] . ')';?></h5>
            <div class='chart-canvas'><canvas height='260' width='800' id='chart-annual'></canvas></div>
          </div>
        </td>
        <td class='w-400px annual'>
          <div style="overflow:auto;" class='table-wrapper'>
            <table id='barChart' class='table table-condensed table-hover table-striped table-bordered table-chart' data-chart='bar' data-target='#myBarChart' data-animation='false'>
              <thead>
                <tr class='text-center'>
                  <th><?php echo $lang->trade->month;?></th>
                  <th class='chart-label-in'><i class='chart-color-dot-in icon-circle'></i> <?php echo $lang->trade->in;?></th>
                  <th class='chart-label-out'><i class='chart-color-dot-out icon-circle'></i> <?php echo $lang->trade->out;?></th>
                  <th class='chart-label-profit'><?php echo $lang->trade->profit . '/' . $lang->trade->loss;?></th>
                </tr>
              </thead>
              <tbody>
              <?php foreach($annualChartDatas as $month => $annualChartData):?>
              <tr class='text-center'>
                <td class='chart-label'><?php echo $month == 'all' ? $lang->trade->total : $month;?></td>
                <td class='chart-value-in'><?php echo $annualChartData['in'];?></td>
                <td class='chart-value-out'><?php echo $annualChartData['out'];?></td>
                <td class='chart-value-profit'><?php echo $annualChartData['profit'];?></td>
              </tr>
              <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </td>
      </tr>
    </table>
    <?php endif;?>
    <?php foreach($monthlyChartDatas as $groupBy => $chartDatas):?>
    <table class='table active-disabled'>
      <?php foreach($chartDatas as $type => $datas):?>
      <tr class='text-top'>
        <td></td>
        <td class='w-p50 monthly'>
          <div class='chart-wrapper text-center'>
            <?php $dateTip = $currentMonth == '00' ? $currentYear . $lang->year : $currentMonth . $lang->month;?>
            <h5><?php echo $dateTip . $lang->trade->$type . $lang->trade->chartList[$groupBy];?></h5>
            <div class='chart-canvas'><canvas id="<?php echo 'chart-' . $type . '-' . $groupBy;?>" width='400' height='200' data-responsive='true'></canvas></div>
          </div>
        </td>
        <td class='w-p25 monthly'>
          <div style="overflow:auto;" class='table-wrapper'>
            <table class='table table-fixed table-condensed table-hover table-striped table-chart' data-scaleLineHeight='1.5' data-chart='pie' data-target="<?php echo '#chart-' . $type . '-' . $groupBy;?>" data-animation='false'>
              <thead>
                <tr class='text-center'>
                  <th><?php echo $lang->trade->$groupBy;?></th>
                  <th class='w-70px'><?php echo $lang->trade->money;?></th>
                  <th class='w-50px'><?php echo $lang->report->percent;?></th>
                </tr>
              </thead>
              <tbody>
              <?php foreach($datas as $data):?>
              <tr class='text-center'>
                <td class='chart-label text-left' title="<?php echo $data->name;?>"><i class='chart-color-dot icon-circle'></i> <?php echo $data->name;?></td>
                <td class='chart-value'><?php echo $data->value;?></td>
                <td><?php echo ($data->percent * 100) . '%';?></td>
              </tr>
              <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </td>
        <td></td>
      </tr>
      <?php endforeach;?>
    </table>
    <?php endforeach;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
