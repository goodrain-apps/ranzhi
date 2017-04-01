/* Set doc type. */
function setType(type)
{
    if(type == 'url')
    {
        $('#urlBox').removeClass('hidden');
        $('#contentBox').addClass('hidden');
    }
    else if(type == 'text')
    {
        $('#urlBox').addClass('hidden');
        $('#contentBox').removeClass('hidden');
    }
}

$(document).ready(function()
{
    if(v.libType != undefined)
    {
        $('#mainNavbar .nav li').removeClass('active');
        $("#mainNavbar .nav li a[href*='" + v.libType + "']").parent().addClass('active');
    }

    if(typeof(v.libID) != undefined && $('#mainNavbar .nav li').find("a[href*='" + v.libID + "']").length > 0)
    {
        $('#mainNavbar .nav li').removeClass('active');
        if(config.requestType == 'GET')
        {   
            $("#mainNavbar .nav li a[href*='libID=" + v.libID + "']").parent().addClass('active');
        }   
        else
        {   
            $("#mainNavbar .nav li a[href*='browse-" + v.libID + "-']").parent().addClass('active');
        }   
    }

    $('#private').click(function()
    {
        $('#userTR').toggle();
        $('#groupTR').toggle();

        if($(this).prop('checked'))
        {
            $('#users').val('');
            $('#users').trigger('chosen:updated');
            $('[name*=groups]').attr('checked', false);
        }
    });

    if(v.private) $('#private').click();

    $('#libList').sortable(
    {
        trigger: '.icon-move',
        selector: '#libList .lib',
        finish: function()
        {
            var orders = {};     
            var orderNext = 1;
            $('#libList .lib').not('.addbtn').not('.files').each(function()
            {
                orders[$(this).data('id')] = orderNext ++;
            });

             $.post(createLink('doc', 'sort'), orders, function(data)
             {
                 if(data.result == 'success')
                 {
                     return location.reload(); 
                 }
                 else
                 {
                     alert(data.message);
                     return location.reload(); 
                 }
             }, 'json');
        }
    })

    $(document).on('click', '.edui-for-fullscreen', function()
    {
        $('#content .edui-editor').toggleClass('full-screen');

        if($('#content .edui-editor.full-screen').length > 0)
        {
            $(this).css('right', '100px');
            $('#content .edui-editor').css('z-index', 99999);
        }
        else
        {
            $(this).css('right', '');
            $('#content .edui-editor').css('z-index', '');
        }
    })
});
