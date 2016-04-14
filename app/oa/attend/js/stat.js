$(document).ready(function()
{
    if(v.mode == 'edit')
    {
        $('tr.edit').show();
        $('tr.view').hide();
    }
    else
    {
        $('tr.edit').hide();
        $('tr.view').show();
    }

    $('.singleEdit').click(function()
    {
        if($('tr.edit').is(':visible')) return false;

        $(this).parents('tr').next('tr.edit').show();
        $(this).parents('tr').next('tr.edit').children('td.singleSave').show();
        $(this).parents('tr').hide();
    })
})
