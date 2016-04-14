$(document).ready(function()
{
    /* Highlight submenu. */
    if(config.requestType == 'GET')
    {
        $('#menu li').removeClass('active').find("[href*='=" + v.type + "']").parent().addClass('active');
    }
    else
    {
        $('#menu li').removeClass('active').find("[href*='-" + v.type + "']").parent().addClass('active');
    }
});
