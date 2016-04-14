$(document).ready(function()
{
    /* Add a trade detail item. */
    $(document).on('click', '.icon-plus', function()
    {
        $(this).parents('tr').after($('#memberTpl').html().replace(/key/g, v.key));
        $(this).parents('tr').next().find("[name^='member']").chosen();
        v.key ++;
        return false;
    });

    /* Remove a trade detail item. */
    $(document).on('click', '.icon-remove', function()
    {
        if($('#ajaxForm table .icon-remove').size() > 1)
        {
            $(this).parents('tr').remove();
        }
        else
        {
            $(this).parents('tr').find('input,select').val('');
        }
        return false;
    });

    $('.switcher').on('click', function()
    {
        var url = $(this).attr('href');
        bootbox.confirm($(this).data('confirm'),  function(result)
        {
            if(result)
            {
                $.getJSON(url, function(response)
                {
                    if(response.result == 'success')
                    {
                        bootbox.alert(response.message, function(){location.reload()});
                    }
                    else
                    {
                        bootbox.alert(response.message);
                    }
                })
            }
        })
        return false;
    });
});
