$(function()
{
    $('#selectCustomer').change(function()
    {
        if($(this).prop('checked'))
        {
            $('#customer_chosen').show();
            $(this).parents('.input-group').find('input[type=text][id=name]').hide();

            $('#customer').trigger("chosen:updated");
        }
        else
        {
            $(this).parents('.input-group').find('select').hide();
            $('#customer_chosen').hide();
            $(this).parents('.input-group').find('input[type=text][id=name]').show();

            $('#customer').trigger("chosen:updated");
        }
    });
    $('#selectCustomer').change();
})
