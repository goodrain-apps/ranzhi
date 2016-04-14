$(function()
{
    $('#menu .nav li').removeClass('active').find('[href*=' + v.mode + ']').parent().addClass('active');

    $(document).on('click', '.leads-apply', function()
    {
        $.getJSON($(this).attr('href'), function(response) 
        {
            if(response.result == 'success')
            {
                return location.href = response.locate;
            }
            else
            {
                 return bootbox.alert(response.message);
            }
        });
        return false;
    })
})
