<?php
/**
 * The calendar view file of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      chujilu <chujilu@cnezsoft.com>
 * @package     todo
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php include '../../../sys/my/view/header.html.php';?>
<?php include '../../../sys/common/view/datepicker.html.php';?>
<?php include '../../../sys/common/view/calendar.html.php';?>
<?php js::set('account', $this->app->user->account);?>
<?php js::set('settings', new stdclass());?>
<?php js::set('settings.startDate', $date == 'future' ? date('Y-m-d') : date('Y-m-d', strtotime($date)));?>
<?php js::set('settings.data', $data);?>
<?php js::set('users', $users);?>
<?php js::set('zentaoEntryList', array_keys($zentaoEntryList));?>
<div class='with-side <?php echo $this->cookie->todoCalendarSide == 'hide' ? 'hide-side' : ''?>'>
  <div class='side'>
    <ul id='myTab' class='nav nav-tabs'>
      <li class='active'><a href='#tab_undone' data-toggle='tab'><?php echo $lang->todo->periods['before']?></a></li>
      <li><a href='#tab_custom' data-toggle='tab'><?php echo $lang->todo->periods['future']?></a></li>
      <li><a href='#tab_task' data-toggle='tab'><?php echo $lang->task->common;?></a></li>
      <li><a href='#tab_order' data-toggle='tab'><?php echo $lang->order->common;?></a></li>
      <li><a href='#tab_customer' data-toggle='tab'><?php echo $lang->customer->common;?></a></li>
      <?php if(!empty($zentaoEntryList)):?>
      <li class='dropdown'>
        <a data-toggle='dropdown' class='dropdown-toggle' href='#'><?php echo $lang->entry->common;?><b class='caret'></b></a>
        <ul aria-labelledby='myTabDrop1' role='menu' class='dropdown-menu pull-right'>
          <?php foreach($zentaoEntryList as $code => $name):?>
          <li><a data-toggle='tab' tabindex='-1' href="<?php echo "#tab_{$code}";?>"><?php echo $name;?></a></li>
          <?php endforeach;?>
        </ul>
      </li>
      <?php endif;?>
    </ul>
    <div class='tab-content'>
      <?php foreach($todoList as $type => $todos):?>
      <?php $index = 0;?>
      <div class='tab-pane fade in <?php echo $type == 'undone' ? 'active' : ''?>' id='tab_<?php echo $type;?>'>
        <?php foreach($todos as $id => $todo):?>
        <?php if($type == 'custom' or $type == 'undone'):?>
        <?php $index++;?>
        <div class='board-item text-nowrap text-ellipsis' title='<?php echo $todo->name;?>' data-id='<?php echo $todo->id?>' data-index='<?php echo $index?>' data-name='<?php echo $todo->name?>' data-type='<?php echo $todo->type?>' data-begin='<?php echo $todo->begin?>' data-end='<?php echo $todo->end?>' data-action='edit' data-toggle="droppable" data-target=".day">
          <?php echo html::a("javascript:void(0)", $todo->name, "onclick=\"viewTodo(this)\" data-remote='" . $this->createLink('todo', 'view', "id=$todo->id") . "' data-title='{$todo->name}' data-width='70%'")?>
        </div>
        <?php endif;?>
        <?php endforeach;?>
      </div>
      <?php endforeach;?>
    </div>
  </div>
  <div class='calendar main'>
    <div class='side-handle'>
      <?php $class = $this->cookie->todoCalendarSide == 'hide' ? 'icon-collapse-full' : 'icon-expand-full'?>
      <?php echo html::a('###', "<i class='$class'></i>", "class='btn'")?>
    </div>
    <div class='day trash' data-date='1970-01-01' title='<?php echo $lang->delete?>'><i class="icon icon-trash"></i></div>
  </div>
</div>
<script>
function updateCalendar()
{
    var calendar = $('.calendar').data('zui.calendar');
    var date = calendar.date.format('yyyyMMdd');
    $.get(createLink('todo', 'calendar', 'date=' + date, 'json'), function(response)
    {
        if(response.status == 'success')
        {
            var data = JSON.parse(response.data);
            for(e in data.data.events) 
            {
                data.data.events[e]['start'] = new Date(data.data.events[e]['start']);
                data.data.events[e]['end']   = new Date(data.data.events[e]['end']);
            }
            calendar.events = data.data.events;
            v.settings.data.events = data.data.events;
            calendar.display();
        }
    }, 'json');
}

/* Finish a todo. */
function finishTodo(id)
{
    $.get(createLink('todo', 'finish', 'todoId=' + id, 'json'),function(response)
    {
        if(response.result == 'success')
        {
            if(response.confirm)
            {
                if(confirm(response.confirm.note))
                {   
                    $.openEntry(response.confirm.entry, response.confirm.url);
                }   
            }
        }
        else
        {
            if(response.message) $.zui.messager.show(response.message);
        }
        updateCalendar();
        return false;
    }, 'json');
}

/* Adjust calendar width. */
function adjustWidth()
{
    var weekendEvents = 0;
    var width = 80;
    $('.calendar tbody.month-days tr.week-days').each(function()
    {
        weekendEvents += $(this).find('td').eq(5).find('.event').size();
        weekendEvents += $(this).find('td').eq(6).find('.event').size();
    });
    if(weekendEvents == 0)
    {
        $('.calendar tr.week-head th').width('auto');
        $('.calendar tr.week-head th').eq(5).width(width);
        $('.calendar tr.week-head th').eq(6).width(width - 10);
        $('.calendar tbody.month-days tr.week-days').each(function()
        {
            $(this).find('td').width('auto');
            $(this).find('td').eq(5).width(width);
            $(this).find('td').eq(6).width(width - 10);
        });
    }
    else
    {
        $('.calendar tr.week-head th').removeAttr('style');
        $('.calendar tbody.month-days tr.week-days').each(function()
        {
            $(this).find('td').removeAttr('style');
        });
    }
}

/* Add +. */
function appendAddLink()
{
    $('.calendar tbody.month-days tr.week-days td.cell-day div.day div.heading .number').each(function()
    {
        var $this = $(this);
        $this.parent().find('.icon-plus').remove();

        thisDate = new Date($this.parents('div.day').attr('data-date'));
        year     = thisDate.getFullYear();
        month    = thisDate.getMonth();
        day      = thisDate.getDate();
        if(year > v.y || (year == v.y && month > v.m) || (year == v.y && month == v.m && day >= v.d))
        {
            $this.after(" <span class='text-muted icon-plus'>&nbsp;<\/span>")
        }
    });
}

/* Add calendar event handler. */
v.date = new Date();
v.d    = v.date.getDate();
v.m    = v.date.getMonth();
v.y    = v.date.getFullYear();

if(typeof(v.settings) == 'undefined') v.settings = {};
if(typeof(v.settings.data) == 'undefined') v.settings.data = {};
v.settings.clickCell = function(event)
{
    if(event.view == 'month')
    {
        var date = event.date;
        var year   = date.getFullYear();
        var month  = date.getMonth();
        var day    = date.getDate();
        if(year > v.y || (year == v.y && month > v.m) || (year == v.y && month == v.m && day >= v.d))
        {
            month = month + 1;
            if(day <= 9) day = '0' + day;
            if(month <= 9) month = '0' + month;
            var todourl = createLink('todo', 'batchCreate', "date=" + year + '' + month + '' + day, '', true);

            $.zui.modalTrigger.show({width: '85%', url: todourl});
        }
    }
};

v.settings.beforeChange = function(event)
{
    if(event.change == 'start')
    {
        var data = {
            'date': event.to.format('yyyy-MM-dd'),
            'name': event.event.title,
            'type': event.event.calendar
        }
        if(!event.event.allDay)
        {
            data.begin = event.event.start.format('hh:mm');
            data.end = event.event.end.format('hh:mm');
        }
        if(data.date == '1970-01-01')
        {
            /* Delete. */
            var link = createLink('todo', 'delete', 'id=' + event.event.id);
        }
        else
        {
            /* Edit. */
            var link = createLink('todo', 'edit', 'id=' + event.event.id);
        }

        $.post(link, data, function(response)
        {
            updateCalendar();
        }, 'json');
    }
};

v.settings.display = function(event)
{
    for(key in v.settings.data.events)
    {
        var e = v.settings.data.events[key];
        if((e.data.status != 'done' && e.data.status != 'closed') && (e.data.assignedTo == '' || e.data.assignedTo == v.account) && (e.calendar != 'trip' && e.calendar != 'leave' ))
        {
            $('.events .event[data-id=' + e.id + ']').append("<div class='action'><a href='javascript:;' class='finish'><?php echo $lang->todo->finish?><\/a><\/div>").addClass('with-action');
            $('.events .event[data-id=' + e.id + '] .action .finish').click(function()
            {
                var id = $(this).closest('.event').data('id');
                finishTodo(id);
                return false;
            });
        }
        if(typeof(e.data.assignedBy) != 'undefined' && e.data.assignedBy != '' && e.data.assignedBy != v.account && e.data.assignedTo == v.account)
        {
            var eventObj = $('.events .event[data-id=' + e.id + ']');
            eventObj.prepend("<span title='<?php echo $lang->todo->assignedBy;?>' class='assign'>" + v.users[e.data.assignedBy] + "<\/span>");
        }
        if(e.data.assignedTo != '' && e.data.assignedTo != v.account)
        {
            var eventObj = $('.events .event[data-id=' + e.id + ']');
            eventObj.prepend("<span title='<?php echo $lang->todo->assignedTo;?>' class='assign'>" + v.users[e.data.assignedTo] + "<\/span>");
            eventObj.css('background-color', '#808080');
        }
        if(e.data.status == 'done' || e.data.status == 'closed')
        {
            var eventObj = $('.events .event[data-id=' + e.id + ']');
            eventObj.css('background-color', '#38B03F');
            eventObj.appendTo(eventObj.parent());
        }
    }
    adjustWidth();
    appendAddLink();
}

v.settings.clickNextBtn  = updateCalendar;
v.settings.clickPrevBtn  = updateCalendar;
v.settings.clickTodayBtn = updateCalendar;
</script>
<?php include '../../common/view/footer.html.php';?>
