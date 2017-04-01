$(document).ready(function()
{
    $('#menu .nav li').removeClass('active').find('[href*=' + v.mode + ']').parent().addClass('active');
    if(v.mode == 'review') $('#menu .nav li').find('[href*=reviewed], [href*=setreviewer]').parent().removeClass('active');

    $.setAjaxJSONER('.refund', function(response)
    {
        if(response.result == 'success')
        {
            bootbox.dialog(
            {  
                message: v.createTradeTip,  
                buttons:
                {  
                    trade:
                    {  
                        label: v.lang.yes,
                        className: 'btn-primary',  
                        callback:  function()
                        {
                            $('.modal').load(createLink('refund', 'createTrade', 'refundID=' + response.refundID), '', function()
                            {
                                $('.modal').modal('ajustPosition', 'fit');
                            });
                            return false;
                        }

                    },
                    back:
                    {  
                        label: v.lang.no,
                        className: '',  
                        callback:  function(){location.reload();}  
                    }
                }  
            });
        }

        return false;
    })
})
