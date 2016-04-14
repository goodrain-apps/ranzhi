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

        /* update calendar data if in calendar page. */
        location.reload();
        return false;
    }, 'json');
}

$(document).ready(function()
{
    $.setAjaxLoader('#triggerModal .ajaxEdit', '#triggerModal');
    $.setAjaxLoader('#ajaxModal .ajaxEdit', '#ajaxModal');
    $.setAjaxLoader('#triggerModal .ajaxAssign', '#triggerModal');
    $.setAjaxLoader('#ajaxModal .ajaxAssign', '#ajaxModal');

    $('.ajaxFinish').click(function()
    {
        $(this).prop('href', '');
        finishTodo($(this).data('id'));
        return false;
    });

    $('[data-toggle=ajax]').click(function()
    {
        $.get($(this).prop('href'), function(response)
        {
            if(response.message) $.zui.messager.success(response.message);
            /* update calendar data if in calendar page. */
            var uc = window['updateCalendar'];
            if($.isFunction(uc))
            {
                updateCalendar();
                $.zui.modalTrigger.close();
            }
            else
            {
                location.reload();
            }
            return false;
        }, 'json');
        return false;
    });

    /* Adjust default deleter. */
    $.setAjaxDeleter('.todoDeleter', function(data)
    {
        if(data.result == 'success')
        {
            /* update calendar data if in calendar page. */
            var uc = window['updateCalendar'];
            if($.isFunction(uc))
            {
                updateCalendar();
                $.zui.modalTrigger.close();
            }
            else
            {
                location.reload();
            }
        }
        else
        {
            return location.reload();
        }
        return false;
    });
});

function setComment()
{
    $('#commentBox').toggle();
    $('.ke-container').css('width', '100%');
    setTimeout(function() { $('#commentBox textarea').focus(); }, 50);
}
