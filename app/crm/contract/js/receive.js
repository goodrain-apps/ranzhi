$(document).ready(function()
{
    $('#createTrade').change(function()
    {
        if($(this).prop('checked'))
        {
            $('.tradeTR').show();
        }
        else
        {
            $('.tradeTR').hide();
        }
    });

    $.setAjaxForm('#receiveForm', function(response)
    {
        if(response.result == 'fail')
        {
            if(response.error && response.error.length)
            {
                $('#duplicateError').html($('.errorMessage').html());
                $('#duplicateError .alert').prepend(response.error);
                $('#duplicateError').show();

                $(document).on('click', '#duplicateError #continueSubmit', function()
                {
                    $('#duplicateError').append("<input value='1' name='continue' class='hide'>");
                    $('#submit').attr('type', 'button');
                })
            }
        }
        else
        {
            if(response.locate == 'reload')
            {
                $.reloadAjaxModal(1500);
            }
            else
            {
                setTimeout(function(){location.href = response.locate;}, 1200);
            }
        }
    });
})
