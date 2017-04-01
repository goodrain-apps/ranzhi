$(document).ready(function()
{
    $.setAjaxForm('#editForm', function()
    {
        /* After the form posted, refresh the treeMenuBox content. */
        source = createLink('tree', 'browse', 'type=' + v.type + '&startModule=0&root=' + v.root) + ' #treeMenuBox';
        $('#treeMenuBox').parent().load(source, function()
        {
            /* Rebuild the tree menu after treeMenuBox refreshed. */
            $(".tree").treeview({collapsed: false, unique: false});    
        });
    });

    $('.group-item label.checkbox').css('float', 'left').css('margin-right', '10px').css('width', '100px');
});
