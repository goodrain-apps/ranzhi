$(function()
{
    $('#menu li').removeClass('active').find('[href*=' + v.mode + ']').parent().addClass('active');

    $('.batchAssign').click(function()
    {
        $('#browseForm').submit();        
    });
});
