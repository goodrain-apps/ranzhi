function switchDateTodo(switcher)
{
    if(switcher.checked)
    {
        $('#date').attr('disabled','disabled');
        $('[name^=switchDate]:not(:checked)').click();
    }
    else
    {
        $('#date').removeAttr('disabled');
        $('[name^=switchDate]:checked').click();
    }
}

function loadList(type, id)
{
    if(id)
    {
        divClass = '.nameBox' + id;
        divID    = '#nameBox' + id;
    }
    else
    {
        divClass   = '.nameBox';
        divID      = '#nameBox';
    }

    var param = 'account=' + v.account;
    if(id) param += '&id=' + id;

    if(type == 'task')
    {
        link = createLink('task', 'ajaxGetTodoList', param);
    }
    else if(type == 'order')
    {
        link = createLink('crm.order', 'ajaxGetTodoList', param);
    }
    else if(type == 'customer')
    {
        link = createLink('crm.customer', 'ajaxGetTodoList', param);
    }

    if(type != 'custom')
    {
        $(divClass).load(link, function(){$(divClass).find('select').chosen(window.defaultChosenOptions)});
    }
    else if(type == 'custom')
    {
        $(divClass).html($(divID).html());
    }
}

function selectNext()
{
    $("#end ")[0].selectedIndex = $("#begin ")[0].selectedIndex + 3;
}

function switchDateFeature(switcher)
{
    if(switcher.checked) 
    {
        $('#begin').attr('disabled','disabled');
        $('#end').attr('disabled','disabled');
    }
    else
    {
        $('#begin').removeAttr('disabled');
        $('#end').removeAttr('disabled');
    }
}
