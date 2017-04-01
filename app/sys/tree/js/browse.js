$(document).ready(function()
{
    /* set active left menu. */
    var menu = $('.leftmenu .nav li').size() == 0 ? '.nav li' : '.leftmenu .nav li';
    if(v.type == 'dept' && $('.leftmenu .nav li').size() == 0) menu = '';
    $(menu).removeClass('active');
    if(config.requestType == 'GET')
    {
        $(menu + " a[href*='tree'][href*='=" + v.type + "']").parent().addClass('active');
    }
    else
    {
        $(menu + " a[href*='tree'][href*='" + v.type + "']").parent().addClass('active');
    }

    if(v.type == 'doc')
    {
         if(v.project)
         {
             if(v.docFrom == 'project')
             {   
                 $('#mainNavbar .nav li').find("a[href*='project']").parent().addClass('active');
                 $('#menu .nav li').find("a[href*='doc']").parent().addClass('active');
             }   
             
             if($('#mainNavbar .nav li').find("a[href*='" + v.root + "'][href*='" + v.project + "']").length > 0)
             {
                 $('#mainNavbar .nav li').find("a[href*='" + v.root + "'][href*='" + v.project + "']").parent().addClass('active');
             }
             else
             {
                 $("#mainNavbar .nav a[href*='alllibs'][href*='project']").parent().addClass('active');
             }
         }
         else
         {
             $("#mainNavbar .nav a[href*='alllibs'][href*='custom']").parent().addClass('active');
         }
    }
    if(v.type == 'provider') $(menu + " a[href*=" + v.type + "]").parent().addClass('active');

    /* Load the children of current category when page loaded. */
    var link = createLink('tree', 'children', 'type=' + v.type + '&moduleID=' + v.moduleID + '&root=' + v.root);
    $('#categoryBox').load(link);
    $('#treeMenuBox li:has(ul)').each(function()
    {
        $(this).children('.deleter').remove();
    });

    $.setAjaxLoader('#treeMenuBox .ajax, .panel-actions .ajax', '#categoryBox');

    if(v.type == 'customdoc') $('.leftmenu .nav li a[href*=createlib]').modalTrigger({type:'ajax', width:800});
})
