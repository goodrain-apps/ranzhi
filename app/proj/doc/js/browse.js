function setBrowseType(type)
{
    $.cookie('browseType', type, {expires:config.cookieLife, path:config.webRoot});
    location.href = location.href;
}
