$(document).ready(function()
{    
    if(v.status == 'reviewed')
    {
        $('#menu > ul.nav > li').removeClass('active').find('a[href*=' + v.status + ']').parent().addClass('active');
    }
})
