$(function()
{
    $('#libType').change(function()
    {
        var libType = $(this).val();
        if(libType == 'project')
        {
            $('table tr.project').removeClass('hidden');
        }
        else
        {
            $('table tr.project').addClass('hidden');
        }
    });

    $('#libType').change();
});
