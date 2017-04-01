$(document).ready(function()
{
    $(document).on('click', '.fix-menu', function()
    {   
        $.getJSON($(this).attr('href'), function(data) 
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
        }); 
        return false;
    });
});

function setBrowseType(type)
{
    $.cookie('browseType', type, {expires:config.cookieLife, path:config.webRoot});
    location.href = location.href;
}
