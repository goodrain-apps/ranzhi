$(document).ready(function()
{
    $('[name*=objectType]').change(function()
    {
        if($(this).prop('checked')) $('[name*=objectType]').not(this).prop('checked', false).change();
        $('#' + $(this).val()).parents('tr').toggle($(this).prop('checked'))
    })
    $('[name*=objectType]').change();
})
