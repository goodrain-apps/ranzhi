$(document).ready(function()
{
    $.setAjaxForm('#childForm');

    var initSortable = function()
    {
        $('#childList').sortable({trigger: '.sort-handle', selector: '.category', dragCssClass: ''});
    }

    var setChildrenKey = function()
    {   
        maxID = v.maxID;
        $('[value=new]').each(function()
        {   
            maxID ++; 
            $(this).parents('.category').find('[id*=children]').attr('name', 'children[' + maxID + ']');
            $(this).attr('name', 'mode[' + maxID + ']');
        })  
    }   

    initSortable();
    setChildrenKey();
});
