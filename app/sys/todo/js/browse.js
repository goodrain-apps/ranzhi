$(document).ready(function()
{
    $('#menu .nav > li').removeClass('active').find('[href*=' + v.mode + ']').parent().addClass('active');

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

    fixTableFooter($('#todoList'));
});
