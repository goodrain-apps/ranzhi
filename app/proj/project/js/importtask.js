function reload(toProject, fromProject)
{ 
    link = createLink('project','importtask','toProject='+toProject + '&fromProject='+fromProject);
    location.href = link;
}

$(document).ready(function()
{
    $('.doc').click(function()
    {
        $.openEntry('doc', $(this).attr('href')); 
        return false;
    })
})
