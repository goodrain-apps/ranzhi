$(function()
{
    $('#menu li').removeClass('active').find('[href*=' + v.status + ']').parent().addClass('active');
})
