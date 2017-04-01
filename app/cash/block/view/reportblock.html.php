<?php $jsRoot = $config->webRoot . "js/";?>
<?php include '../../../sys/common/view/chart.html.php';?>

<table class='table active-disabled'>
  <tr class='text-top'>
    <td>
      <div class='chart-wrapper text-center'>
        <?php $dateTip = $currentMonth . $lang->month;?>
        <h5><?php echo $dateTip . $lang->trade->$type . $lang->trade->chartList[$groupBy];?></h5>
        <div class='chart-canvas'>
          <canvas id="<?php echo 'chart-' . $type . '-' . $groupBy;?>" width='80' height='20' data-responsive='true'></canvas>
        </div>
      </div>
    </td>
  </tr>
  <tr>
    <td class='w-400px'>
      <div style="overflow:auto; max-height:200px" class='table-wrapper'>
        <table class='table table-condensed table-hover table-striped table-bordered table-chart' data-chart='pie' data-target="<?php echo '#chart-' . $type . '-' . $groupBy;?>" data-animation='false'>
          <thead>
            <tr class='text-center'>
              <th colspan='2'><?php echo $lang->trade->$groupBy;?></th>
              <th><?php echo $lang->trade->money;?></th>
              <th><?php echo $lang->report->percent;?></th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($datas as $data):?>
          <tr class='text-center'>
            <td class='chart-color w-20px'><i class='chart-color-dot icon-circle'></i></td>
            <td class='chart-label text-left'><?php echo $data->name;?></td>
            <td class='chart-value'><?php echo $data->value;?></td>
            <td><?php echo ($data->percent * 100) . '%';?></td>
          </tr>
          <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </td>
  </tr>
</table>
