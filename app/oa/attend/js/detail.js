$(document).ready(function()
{
    $('#dept').change(function()
    {
        $('#account').load(createLink('attend', 'ajaxGetDeptUsers', 'dept=' + $(this).val()), function()
        {
            $('#account').trigger('chosen:updated');
        });
    });

    $('.form-month').each(function()
    {
        var date = $(this).val();
        $(this).val(date.substr(0, 7));
    });

    fixTableHeader();
})
