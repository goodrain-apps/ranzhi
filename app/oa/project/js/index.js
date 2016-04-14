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

    $('a.mode-toggle').click(function()
    {
        $('a.mode-toggle').removeClass('active');
        $(this).addClass('active');
        $('a.mode-toggle').parent('div').nextAll('div').hide();
        $('#' + $(this).data('mode') + 'Mode').show();
        $.cookie('projectViewType', $(this).data('mode'), {path: "/"});
    })

    var type = $.cookie('projectViewType');
    if(typeof(type) == 'undefined' || type == '') type = 'card';
    $('#menuActions a[data-mode=' + type +']').click();
});
