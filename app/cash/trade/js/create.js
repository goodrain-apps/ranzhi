$(document).ready(function()
{
    $('#contract').change(function()
    {
        $('#money').val($(this).find('option:selected').attr('data-amount'));
    })
})
