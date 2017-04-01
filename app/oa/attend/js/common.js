$(document).ready(function()
{    
    if(config.requestType == 'GET')
    {
        $('.side .leftmenu ul').find('a[href*=f\\=' + config.currentMethod + ']').parent().addClass('active');
    }
    else
    {
        $('.side .leftmenu ul').find('a[href*=-' + config.currentMethod + ']').parent().addClass('active');
    }

    /* expand active tree. */
    $('.tree li.active .hitarea').click();
})
