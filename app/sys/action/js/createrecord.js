$(document).ready(function()
{
    $.setAjaxForm('#createRecordForm', function(response)
    {
        if(response.result == 'success')
        {
            if(response.locate == '' || response.locate == 'reload')
            {
                location.reload(); 
            }
            else if(response.locate == 'parent.reload')
            {
                parent.location.reload();
            }
            else
            {
                location = response.locate;
            }
        }
        else
        { 
            if(response.error && response.error.length)
            {
                $('#duplicateError').html($('.errorMessage').html());
                $('#duplicateError .alert').prepend(response.error).show();

                $(document).on('click', '#duplicateError #continueSubmit', function()
                {
                    $('#duplicateError').append("<input value='1' name='continue' class='hide'>");
                    $('#submit').attr('type', 'button');
                })
            }
        }
    });

    if(v.history) $('#actionBox').load(createLink('action', 'history', 'objectType=' + v.objectType + '&objectID=' + v.objectID + '&action=record&from=record'), function()
    {
        if($('#actionBox .panel-history .panel-body').height() > 300) 
        {
          $('#actionBox .panel-history .panel-body').css({'height' : 300, 'overflow' : 'auto'});
        }
    });

    $('[name*=objectType]').change(function()
    {
        $('#order, #contract').attr('disabled', true).parents('tr').hide();
        if($(this).prop('checked')) 
        {
            $('[name*=objectType]').not(this).attr('checked', false);
            $('#' + $(this).val()).attr('disabled', false).parents('tr').show();
        }
    });
    $('#ajaxModal .sorter').click();
  
    $('[name*=createContact]').change(function()
    {   
        if($(this).prop('checked')) 
        {   
            $(this).parents('.input-group').find('select').hide();
            $('#contact_chosen').hide();
            $(this).parents('.input-group').find('input[id=realname]').show().focus();
        }   
        else
        {   
            $('#contact_chosen').show();
            $(this).parents('.input-group').find('input[id=realname]').hide();
        }   
    });

    /* Change contact. */
    $('#contact').change(function()
    {
        var phone = $.trim($(this).find('option:selected').attr('data-phone'));
        var qq    = $.trim($(this).find('option:selected').attr('data-qq'));
        var email = $.trim($(this).find('option:selected').attr('data-email'));

        phone = phone == '' ? '' : "<i class='icon-phone-sign'></i>" + phone;
        qq    = qq    == '' ? '' : "<i class='icon-qq'></i><a target='_blank' href='http://wpa.qq.com/msgrd?v=3&uin=" + qq + "&site=&menu=yes'>" + qq + "</a>";
        email = email == '' ? '' : "<i class='icon-envelope-alt'></i><a href='mailto:" + email + "'>" + email + "</a>";
        
        if(phone || qq || email)
        {
            $('#phoneTR').show();
            $('#phoneTD').html(phone + qq + email);
        }
    });

    $('#fileform > .text-danger').remove();
});

/**
 * Compute the next contact date for action.
 * 
 * @param  int    $delta 
 * @access public
 * @return void
 */
function computeNextDate(delta)
{
    today = new Date();
    today = today.toString('yyyy-M-dd');
    if(!today) return;

    nextDate = convertStringToDate(today).addDays(parseInt(delta));
    nextDate = nextDate.toString('yyyy-M-dd');

    if(delta == 365000)
    {
        $('#createRecordForm #nextDate').val('').attr('disabled', true);
    }
    else
    {
        $('#createRecordForm #nextDate').val(nextDate).attr('disabled', false);
    }
}

/**
 * Convert a date string like 2011-11-11 to date object in js.
 * 
 * @param  string $date 
 * @access public
 * @return date
 */
function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    return new Date(dateString[0], dateString[1] - 1, dateString[2]);
}
