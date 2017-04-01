$(document).ready(function()
{
    if(v.type == 'redeem')
    {
        $('tr.category').show();
        $('tr.investList').show();
        $('tr.trader').hide();
    }
    else
    {
        $('tr.category').hide();
        $('tr.investList').hide();
        $('tr.trader').show();
    }
});
