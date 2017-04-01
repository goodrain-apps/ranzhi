$(document).ready(function()
{
    $('.setDept').click(function()
    {
        var href = $(this).prop('href');
        var app  = 'team';
        $.openEntry(app, href);
        return false;
    });
});
