$(document).ready(function()
{
    $('#menu .nav > li').removeClass('active').find('a[href*=' + v.type + ']').parent('li').addClass('active');

    /* process actions. */
    $('.actions a').click(function()
    {
        var skip = false;
        if($(this).data('toggle') == 'modal') skip = true;
        if($(this).hasClass('deleter')) skip = true;
        if($(this).hasClass('reloadDeleter')) skip = true;
        if($(this).hasClass('switcher')) skip = true;

        var href = $(this).prop('href');
        var app  = '';
        if(href.indexOf('/crm/') != -1)  app = 'crm';
        if(href.indexOf('/oa/') != -1)   app = 'oa';
        if(href.indexOf('/cash/') != -1) app = 'cash';
        if(href.indexOf('/team/') != -1) app = 'team';

        if(!skip && app != '')
        {
            $.openEntry(app, href);
            return false;
        }
    });
})
