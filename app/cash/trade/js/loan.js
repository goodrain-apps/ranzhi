$(document).ready(function()
{
    if(v.type == 'repay')
    {
        $('tr.loanList').show();
        $('tr.interest').show();
        $('tr.trader').hide();
    }
    else
    {
        $('tr.loanList').hide();
        $('tr.interest').hide();
        $('tr.trader').show();
    }
});
