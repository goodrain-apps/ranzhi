$(document).ready(function()
{
    $('[data-toggle=ajax]').click(function()
    {
        if($(this).hasClass('disabled')) return false;
        var status = $(this).data('status');
        if(status == 'undefined' || confirm(v.confirmReview[status]))
        {
            $.get($(this).prop('href'), function(response)
            {
                if(response.message) $.zui.messager.success(response.message);
                if(response.result == 'success') location.reload();
                return false;
            }, 'json');
        }
        return false;
    });
});
