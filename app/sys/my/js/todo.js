$(document).ready(function()
{
    $('[data-toggle=ajax]').click(function()
    {
        if($(this).hasClass('disabled')) return false;
        $.get($(this).prop('href'), function(response)
        {
            if(response.message) $.zui.messager.success(response.message);
            /* show confirm info. */
            if(response.confirm)
            {
                if(confirm(response.confirm.note))
                {   
                    $.openEntry(response.confirm.entry, response.confirm.url);
                }   
            }
            if(response.result == 'success') location.reload();
            return false;
        }, 'json');
        return false;
    });

    $('form tr').click(function()
    {
        checkbox = $(this).find('td:first-child').find('input[type=checkbox]');
        if(typeof checkbox != 'undefined') checkbox.prop('checked', !checkbox.prop('checked'));
    });
});
