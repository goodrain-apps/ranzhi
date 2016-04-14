$(document).ready(function()
{
    $('.setExpense').click(function()
    {
        var href = $(this).prop('href');
        var app  = 'cash';
        $.openEntry(app, href);
        return false;
    });
});
