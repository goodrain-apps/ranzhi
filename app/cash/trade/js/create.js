$(document).ready(function()
{
    $('#order, #contract').change(function()
    {
        $('#money').val($(this).find('option:selected').attr('data-amount'));
        $('#customer').val($(this).find('option:selected').attr('data-customer'));
        $('#customer').trigger('chosen:updated');
    })
})
