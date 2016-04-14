$(document).ready(function()
{
    $('#account').focus();

    $("#langs li > a").click(function() 
    {
        selectLang($(this).data('value'));
    });

    /* show update notice. */
    if(typeof(latest) != 'undefined')
    {
        if(typeof(v.ignoreNotice) == 'undefined' || $.inArray('update' + latest.version, v.ignoreNotice) == -1)
        {
            var content = 'NOTE: <a href=' + latest.url + ' target=\'_blank\'>' + latest.note + '(' + latest.releaseDate + ')</a>';
            content += "&nbsp;&nbsp;&nbsp;<a class='ignore' href=" + createLink('misc', 'ignoreNotice', 'version=update' + latest.version) + ">" + v.ignore + "</a>";
            content = "<p>" + content + "</p>";
            $('.notice').append(content); 
        }
    }
    if(typeof(notice) != 'undefined')
    {
        if(typeof(v.ignoreNotice) == 'undefined' || $.inArray('notice' + notice.id, v.ignoreNotice) == -1)
        {
            var content = 'NOTE: <a href=' + notice.url + ' target=\'_blank\'>' + notice.note + '(' + notice.date + ')</a>';
            content += "&nbsp;&nbsp;&nbsp;<a class='ignore' href=" + createLink('misc', 'ignoreNotice', 'version=notice' + notice.id) + ">" + v.ignore + "</a>";
            content = "<p>" + content + "</p>";
            $('.notice').append(content); 
        }
    }
    $('.ignore').click(function()
    {
        $.get($(this).prop('href'));
        $(this).prop('href', '###');
        $('.notice').html('');
        return false;
    });
})

/* Keep session random valid. */
$('#submit').click(function()
{
    var password    = md5(md5(md5($('#password').val()) + $('#account').val()) + v.random);
    var rawPassword = md5($('#password').val());

    loginURL = createLink('user', 'login');
    $.ajax(
    {
        type: "POST",
        data:"account=" + $('#account').val() + '&password=' + password + '&referer=' + encodeURIComponent($('#referer').val()) + '&rawPassword=' + rawPassword + '&keepLogin=' + $('#keepLogin1').is(':checked'),
        url:$('#ajaxForm').attr('action'),
        dataType:'json',
        success:function(data)
        {
            if(data.result == 'fail') return  bootbox.alert(data.message);
            if(data.result == 'success') return location.href=data.locate;
            if(typeof(data) != 'object') return bootbox.alert(data);
        },
        error:function(data){bootbox.alert(data.responseText)}
    })
    return false;
})
