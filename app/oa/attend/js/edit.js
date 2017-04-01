$(document).ready(function()
{
    var status = ',leave,makeup,overtime,lieu,trip,egress,';
    if(v.status == 'normal' || (v.reason && status.indexOf(',' + v.status + ',') == -1 ))
    {
        $('.editMode').hide();
        $('.viewMode').show();
    }
    else
    {
        $('.editMode').show();
        $('.viewMode').hide();
    }

    $('.edit').click(function()
    {
        $('.editMode').show();
        $('.viewMode').hide();
    })
})
