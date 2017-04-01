<?php 
/**
 * The compare report view file of trade module of Ranzhi.
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
<?php js::set('compareTip', $lang->trade->report->compareTip);?>
<div class='row'>
  <div class='col-md-3 col-lg-2'>
    <form method='post'>
      <div class='panel panel-sm'>
        <div class='panel-heading'>
          <strong><?php echo $lang->trade->report->selectYears;?></strong>
          <div class='panel-actions pull-right'>
            <?php echo html::select('currency', $currencyList, $currency, "class='form-control'")?>
            <?php echo html::select('unit', $lang->trade->report->unitList, $unit, "class='form-control'")?>
          </div>
        </div>
        <div class='panel-body'>
          <?php echo html::checkBox('years', $tradeYears, $selectYears, '', 'block');?>
          <p><?php echo html::submitButton($lang->trade->report->create);?></p>
        </div>
      </div>
    </form>
  </div>
  <div class='col-md-9 col-lg-10'>
    <div class='panel panel-sm'>
      <div class='panel-heading'><strong><?php echo $lang->trade->report->compare;?></strong></div>
      <div class='panel-body'>
        <table class='table active-disabled'>
          <tr>
            <td colspan='3'>
              <div class='chart-wrapper text-center'>
                <h5><?php echo $lang->trade->in . $lang->trade->report->compare . '(' . $currencyList[$currency] . ':' . $lang->trade->report->unitList[$unit] . ')';?></h5>
                <div class='chart-canvas'><canvas height='350' width='800' id='chart-income'></canvas></div>
              </div>
            </td>
            <td class='w-p30'>
              <div class='table-wrapper' style='overflow:auto'>
                <table id='barChart' class='table table-condensed table-hover table-striped table-bordered table-chart' data-chart='bar' data-target='#chart-income' data-animation='false'>
                  <thead>
                    <tr class='text-center'>
                      <th><?php echo $lang->trade->month;?></th>
                      <?php foreach($selectYears as $key => $selectYear):?>
                      <th class='chart-label-<?php echo $key + 1;?>'><i class='chart-color-dot-<?php echo $key + 1;?> icon-circle'></i> <?php echo $selectYear;?></th>
                      <?php endforeach;?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($lang->trade->monthList as $month => $name):?>
                    <?php if($month == 'last' or $month == 'total') continue;?>
                    <tr class='text-center'>
                      <td class='chart-label'><?php echo $month;?></td>
                      <?php foreach($selectYears as $key => $selectYear):?>
                      <td class='chart-value-<?php echo $key + 1;?>'><?php echo isset($incomeDatas[$month][$selectYear]) ? $incomeDatas[$month][$selectYear] : 0;?></td>
                      <?php endforeach;?>
                    </tr>
                    <?php endforeach;?>
                    <tr class='text-center'>
                      <td class='chart-label'><?php echo $lang->trade->total;?></td>
                      <?php foreach($selectYears as $key => $selectYear):?>
                      <td class='chart-value-<?php echo $key + 1;?>'><?php echo isset($incomeDatas['all'][$selectYear]) ? $incomeDatas['all'][$selectYear] : 0;?></td>
                      <?php endforeach;?>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
        </table>
        <table class='table active-disabled'>
          <tr>
            <td colspan='3'>
              <div class='chart-wrapper text-center'>
                <h5><?php echo $lang->trade->out . $lang->trade->report->compare . '(' . $currencyList[$currency] . ':' . $lang->trade->report->unitList[$unit] . ')';?></h5>
                <div class='chart-canvas'><canvas height='350' width='800' id='chart-expense'></canvas></div>
              </div>
            </td>
            <td class='w-p30'>
              <div class='table-wrapper' style='overflow:auto'>
                <table id='barChart' class='table table-condensed table-hover table-striped table-bordered table-chart' data-chart='bar' data-target='#chart-expense' data-animation='false'>
                  <thead>
                    <tr class='text-center'>
                      <th><?php echo $lang->trade->month;?></th>
                      <?php foreach($selectYears as $key => $selectYear):?>
                      <th class='chart-label-<?php echo $key + 1;?>'><i class='chart-color-dot-<?php echo $key + 1;?> icon-circle'></i> <?php echo $selectYear;?></th>
                      <?php endforeach;?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($lang->trade->monthList as $month => $name):?>
                    <?php if($month == 'last' or $month == 'total') continue;?>
                    <tr class='text-center'>
                      <td class='chart-label'><?php echo $month;?></td>
                      <?php foreach($selectYears as $key => $selectYear):?>
                      <td class='chart-value-<?php echo $key + 1;?>'><?php echo isset($expenseDatas[$month][$selectYear]) ? $expenseDatas[$month][$selectYear] : 0;?></td>
                      <?php endforeach;?>
                    </tr>
                    <?php endforeach;?>
                    <tr class='text-center'>
                      <td class='chart-label'><?php echo $lang->trade->total;?></td>
                      <?php foreach($selectYears as $key => $selectYear):?>
                      <td class='chart-value-<?php echo $key + 1;?>'><?php echo isset($expenseDatas['all'][$selectYear]) ? $expenseDatas['all'][$selectYear] : 0;?></td>
                    <?php endforeach;?>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
        </table>
        <table class='table active-disabled'>
          <tr>
            <td colspan='3'>
              <div class='chart-wrapper text-center'>
                <h5><?php echo $lang->trade->profit . $lang->trade->loss . $lang->trade->report->compare . '(' . $currencyList[$currency] . ':' . $lang->trade->report->unitList[$unit] . ')';?></h5>
                <div class='chart-canvas'><canvas height='350' width='800' id='chart-profit'></canvas></div>
              </div>
            </td>
            <td class='w-p30'>
              <div class='table-wrapper' style='overflow:auto'>
                <table id='barChart' class='table table-condensed table-hover table-striped table-bordered table-chart' data-chart='bar' data-target='#chart-profit' data-animation='false'>
                  <thead>
                    <tr class='text-center'>
                      <th><?php echo $lang->trade->month;?></th>
                      <?php foreach($selectYears as $key => $selectYear):?>
                      <th class='chart-label-<?php echo $key + 1;?>'><i class='chart-color-dot-<?php echo $key + 1;?> icon-circle'></i> <?php echo $selectYear;?></th>
                      <?php endforeach;?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($lang->trade->monthList as $month => $name):?>
                    <?php if($month == 'last' or $month == 'total') continue;?>
                    <tr class='text-center'>
                      <td class='chart-label'><?php echo $month;?></td>
                      <?php foreach($selectYears as $key => $selectYear):?>
                      <td class='chart-value-<?php echo $key + 1;?>'><?php echo isset($profitDatas[$month][$selectYear]) ? $profitDatas[$month][$selectYear] : 0;?></td>
                      <?php endforeach;?>
                    </tr>
                    <?php endforeach;?>
                    <tr class='text-center'>
                      <td class='chart-label'><?php echo $lang->trade->total;?></td>
                      <?php foreach($selectYears as $key => $selectYear):?>
                      <td class='chart-value-<?php echo $key + 1;?>'><?php echo isset($profitDatas['all'][$selectYear]) ? $profitDatas['all'][$selectYear] : 0;?></td>
                      <?php endforeach;?>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
