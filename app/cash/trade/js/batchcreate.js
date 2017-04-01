$(document).ready(function()
{
    $(document).ready(removeDitto());//Remove 'ditto' in first row.

    $(document).on('change', '.type', function()
    {
        var type = $(this).val();
        $(this).parents('tr').find('.in, .out').next('.chosen-container').hide().attr('disabled', true).find('*').attr('disabled', true);
        $(this).parents('tr').find('.' + type).next('.chosen-container').show().attr('disabled', false).find('*').attr('disabled', false);
        $(this).parents('tr').find('.in, .out').hide();
        $(this).parents('tr').find('div.' + type).show();
    })

    $('.type').change();
});
