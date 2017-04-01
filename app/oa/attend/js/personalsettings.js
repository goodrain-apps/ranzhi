$(function()
{
    $(document).on('click', '.addItem', function()
    {
        $(this).parents('tr').after('<tr>' + $(this).parents('tr').html() + '</tr>');
        $(this).parents('tr').next('tr').find('.chosen-container').remove();
        $(this).parents('tr').next('tr').find('.chosen').chosen(window.chosenDefaultOptions);
        $(this).parents('tr').next('tr').find('.form-date').fixedDate().datetimepicker(
        {
            language:  config.clientLang,
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0,
            format: 'yyyy-mm-dd'
        });
    });

    $(document).on('click', '.delItem', function()
    {
        if($('.delItem').size() == 1)
        {
            $(this).parents('tr').find('input,select').val('');
            $(this).parents('tr').find('select').trigger('chosen:updated');
            return;
        }
        $(this).parents('tr').remove();
    });
})
