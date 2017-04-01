$(function()
{
    $('#menu .nav > li').removeClass('active');
    if(v.mode) $('#menu .nav').find('[href*=' + v.mode + ']').parent().addClass('active');
    else $('#menu .nav').find('li.all').addClass('active');
});
