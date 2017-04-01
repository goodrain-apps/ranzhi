$(document).ready(function()
{
    $('#mainNavbar .navbar-nav li').removeClass('active').find('a[href*=' + v.type + ']').parent().addClass('active');
});
