<?php
/**
 * The attend block view file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<style>
.nomargin {margin: 0;}
.header {height: 10%;}
.AM {height: 39%;}
.PM {height: 39%;}
.col-p8 {width: 8%;}
.col-p137 {width: 13.7%;}
.col-p145 {width: 14.5%;}
.col-p158 {width: 15.8%;}
.status {height: 13%;}
.calendar {width: 100%; height: 100%; text-align: center;}
.calendar th {text-align: center; color: gray;}
.AM td, .PM td {vertical-align: top;}
.calendar tr+tr {border-top: 1px solid rgb(221, 221, 221);}
.calendar th+th, .calendar td+td, .calendar th+td {border-left: 1px solid rgb(221, 221, 221);}
.calendar .today {background: #f0f0f0;}
.event {color: rgb(255, 255, 255); width: 100%; height: 18px; overflow: hidden; margin: 1px 0;}
.event:hover {cursor: pointer;}
.event.done {background-color: rgb(56, 176, 63);}
.event.wait {background-color: rgb(50, 128, 252);}
.attend-normal {color: #8BC34A;}
.attend-late   {color: #EA644A;}
.attend-early  {color: #FF8A65;}
.attend-both   {color: #FF5722;}
.attend-absent {color: #F1A325;}
.attend-leave  {color: #9E9E9E;}
.attend-trip   {color: #8668B8;}
.attend-status:hover {cursor: pointer;}

.text-muted {position: relative; float: right; padding: 3px; color: #CCC;}
</style>
<?php $dateList = range(strtotime($startDate), strtotime($endDate), 86400);?>
<table class='calendar'>
  <tr class='header'>
    <th class='w-p5'></th>
    <?php 
        $sunTodo = '';
        $satTodo = '';
        foreach($dateList as $d)
        {
            if(date('w', $d) == 0) $sunTodo = isset($todos[date('Y-m-d', $d)]) ? $todos[date('Y-m-d', $d)] : '';
            if(date('w', $d) == 6) $satTodo = isset($todos[date('Y-m-d', $d)]) ? $todos[date('Y-m-d', $d)] : '';
        }
    ?>
    <?php foreach($dateList as $d):?>
    <?php 
        $dStr  = date('Y-m-d', $d);
        $week  = date('w', $d);
        if(empty($sunTodo) && empty($satTodo))
        {
            $width = ($week == 0 || $week == 6) ? 'col-p8' : 'col-p158';
        }
        elseif(!empty($sunTodo) && !empty($satTodo))
        {
            $width = 'col-p137';
        }
        elseif(!empty($sunTodo))
        {
            $width = $week == 6 ? 'col-p8' : 'col-p145';
        }
        else
        {
            $width = $week == 0 ? 'col-p8' : 'col-p145';
        }
        $class = $dStr == $date ? 'today' : '';
    ?>
    <th class='<?php echo $width . ' ' . $class;?> dayheader' data-date='<?php echo $dStr?>'><?php echo zget($this->lang->datepicker->abbrDayNames, date('w', $d))?></th>
    <?php endforeach;?>
  </tr>
  <tr class='AM'>
    <th><?php echo $lang->attend->AM?></th>
    <?php foreach($dateList as $d):?>
    <?php $dStr = date('Y-m-d', $d);?>
    <?php $class = $dStr == $date ? 'today' : '';?>
      <td class='day <?php echo $class?>' data-date='<?php echo $dStr?>'>
        <?php if(!isset($todos[$dStr]['AM'])) continue;?>
        <?php foreach($todos[$dStr]['AM'] as $todo):?>
        <div class='event <?php echo $todo->status?>' data-id="<?php echo $todo->id?>" title='<?php echo $todo->begin . ' ' . $todo->name?>'>
          <?php echo $todo->name;?>
        </div>
        <?php endforeach;?>
      </td>
    <?php endforeach;?>
  </tr>
  <tr class='PM'>
    <th><?php echo $lang->attend->PM?></th>
    <?php foreach($dateList as $d):?>
    <?php $dStr = date('Y-m-d', $d);?>
    <?php $class = $dStr == $date ? 'today' : '';?>
      <td class='day <?php echo $class?>' data-date='<?php echo $dStr?>'>
        <?php if(!isset($todos[$dStr]['PM'])) continue;?>
        <?php foreach($todos[$dStr]['PM'] as $todo):?>
        <div class='event <?php echo $todo->status?>' data-id="<?php echo $todo->id?>" title='<?php echo $todo->begin . ' ' . $todo->name?>'>
          <?php echo $todo->name;?>
        </div>
        <?php endforeach;?>
      </td>
    <?php endforeach;?>
  </tr>
  <?php if(strpos($this->config->setting->modules, 'attend') !== false):?>
  <?php $link = "$.openEntry('oa', '" . $this->createLink('oa.attend', 'personal') . "')";?>
  <tr class='status'>
    <th><?php echo $lang->attend->common?></th>
    <?php foreach($dateList as $d):?>
    <?php $dStr = date('Y-m-d', $d);?>
    <?php $class = $dStr == $date ? 'today' : '';?>
    <?php if(isset($attends[$dStr])):?>
      <td class='<?php echo "$class attend-status attend-{$attends[$dStr]->status}"?>' onclick="<?php echo $link?>">
        <?php echo zget($this->lang->attend->abbrStatusList, $attends[$dStr]->status)?>
      </td>
    <?php else:?>
      <td class='<?php echo $class?>'></td>
    <?php endif;?>
    <?php endforeach;?>
  </tr>
  <?php endif;?>
</table>
<script>
$(document).ready(function()
{
    v.date = new Date();
    v.d    = v.date.getDate();
    v.m    = v.date.getMonth();
    v.y    = v.date.getFullYear();

    var calendar = $('table.calendar');
    /* view todo. */
    calendar.find('.event').click(function()
    {
        var todourl = createLink('sys.todo', 'view', "id=" + $(this).data('id'), '', true);
        $.zui.modalTrigger.show({width: '85%', url: todourl});
        return false;
    });
    /* Add + */
    calendar.find('.dayheader').each(function()
    {
        var date = new Date($(this).data('date'));
        var year   = date.getFullYear();
        var month  = date.getMonth();
        var day    = date.getDate();
        if(year > v.y || (year == v.y && month > v.m) || (year == v.y && month == v.m && day >= v.d))
        {
            if($(this).find('.icon-plus').length == 0)
            {
                $(this).prepend("<span class='text-muted icon-plus'></span>");
            }
        }
    });
    /* batch create todo. */
    calendar.find('.dayheader, .day').click(function()
    {
        var date = new Date($(this).data('date'));
        var year   = date.getFullYear();
        var month  = date.getMonth();
        var day    = date.getDate();
        if(year > v.y || (year == v.y && month > v.m) || (year == v.y && month == v.m && day >= v.d))
        {
            month = month + 1;
            if(day <= 9) day = '0' + day;
            if(month <= 9) month = '0' + month;
            var todourl = createLink('sys.todo', 'batchCreate', "date=" + year + '' + month + '' + day, '', true);
            $.zui.modalTrigger.show({width: '85%', url: todourl});
        }
        return false;
    });
});
</script>
