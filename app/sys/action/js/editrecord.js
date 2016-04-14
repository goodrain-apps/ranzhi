$(document).ready(function()
{
    if($('body.body-modal').length)
    {
        $.setAjaxForm('#editRecord',function() { $.reloadIframeModal(); });
    }
    else
    {
        $.setAjaxForm('#editRecord', function(response){location.href = response.locate});
    }

    /* Change contact. */
    $('#contact').change(function()
    {
        var phone = $(this).find('option:selected').attr('data-phone');
        if($.trim(phone) == '' || $.trim(phone) == '/') return false;
        
        $('#phoneTR').show();
        $('#phoneTD').html(phone);
    });

    $('#contact').change();
});
