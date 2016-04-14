$(document).ready(function()
{
    $('[name*=createUsers]').each(function()
    {
        $(this).change(function()
        {
            if($(this).prop('checked'))
            {
                $(this).parents('tr').find('#zentaoAccounts').attr('disabled', true);
            }
            else
            {
                $(this).parents('tr').find('#zentaoAccounts').attr('disabled', false);
            }
        })
    })
})
