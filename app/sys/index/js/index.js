$(function()
{
    /* start ips */
    $.ipsStart(entries, $.extend({onBlocksOrdered: sortBlocks, onDeleteBlock: deleteBlock, onDeleteEntry: deleteEntry, onUpdateEntryMenu: updateEntryMenu, onSortEntries: sortEntries}, config, ipsLang));
    if(v.attend)
    {
        initAttendButton();
        $('.sign').parent('li').show();
    }
    else
    {
        $('.sign').parent('li').hide();
    }

    $(document).on('mouseover', '.categoryButton', function()
    {
        $('.categoryButton').not('.open').removeClass('active');
        $(this).addClass('active');
        var id    = $(this).data('id');
        var menu  = $('#categoryMenu' + id);
        var lis   = menu.find('li');
        var color = $('body').css('background-color');
        menu.css({'background-color' : color, 'top' : $(this).offset().top - 3, 'width' : 40 * lis.size() + 20});
        $('.categoryMenu').not(menu).hide();
        menu.show();
    });

    $(document).on('mouseover', '.categoryMenu li .app-btn', function()
    {
        $('.categoryMenu li .app-btn').removeClass('active');
        $(this).addClass('active');
    });

    $(document).on('mouseout', '.categoryMenu li .app-btn', function()
    {
        $('.categoryMenu li .app-btn').removeClass('active');
        var id = $(this).parents('.categoryMenu').data('id');
        if(!$('#category' + id).hasClass('open')) $('#category' + id).removeClass('active');
    });

    $(document).on('click', '.categoryMenu li .app-btn', function()
    {
        $('.categoryMenu').hide();
        $('.categoryButton').removeClass('active');

        var id = $(this).parents('.categoryMenu').data('id');
        $('#category' + id).addClass('open active');
    });

    $(document).on('mouseover', '#leftBar #apps-menu .bar-menu li .app-btn', function()
    {
        $('.categoryButton').not(this).not('.open').removeClass('active');
        if(!$(this).hasClass('categoryButton')) $('.categoryMenu').hide();
    });

    $(document).on('click', '#leftBar #apps-menu .bar-menu li .app-btn', function()
    {
        $('.categoryButton').removeClass('active');
        $('.categoryMenu').hide();
    });

    $(document).on('click', '#bottomBar #taskbar .bar-menu li .app-btn', function()
    {
        $('.categoryButton').removeClass('active');
        var dataid = $(this).data('id');
        $('.categoryMenu li .app-btn').each(function()
        {
            if($(this).data('id') == dataid)
            {
                var id = $(this).parents('.categoryMenu').data('id');
                $('#category' + id).addClass('active');
                return;
            }
        });
    });

    $(document).mouseover(function(e)
    {
        $('.categoryMenu').each(function()
        {
            if($(this).is(':visible'))
            {
                var dataid  = $(this).data('id');
                var button  = $('#category' + dataid);
                var top     = button.offset().top;
                var left    = button.offset().left;
                var right   = left + button.width() + $(this).width();
                var bottom  = top + button.height() + $(this).height();
                if(e.pageX < left || e.pageX > right || e.pageY < top || e.pageY > bottom)
                {
                    $(this).hide();
                }
            }
        });
    });
});

/**
 * Update orders of entries
 * 
 * @param  object      orders
 * @param  function    callback after success
 * @access public
 * @return void
 */
function sortEntries(orders, callback)
{
    $.post(createLink('entry', 'customSort'), orders, function(data)
    {
        callback && callback(data.result == 'success');
    }, 'json');
}

/**
 * Update menu attribute of entry
 * 
 * @param  int         entry id 
 * @param  function    callback after success
 * @access public
 * @return void
 */
function updateEntryMenu(et, callback)
{
    $.post(createLink('entry', 'updateEntryMenu'), et, function(data)
    {
        callback && callback(data.result == 'success');
    }, 'json');
}

/**
 * Delete entry.
 * 
 * @param  object      entry 
 * @param  function    callback after delete success
 * @access public7
 * @return void
 */
function deleteEntry(et, callback)
{
    $.getJSON(createLink('entry', 'delete', 'code=' + et.code), function(data)
    {
        callback && callback(data.result == 'success');
    });
}

/**
 * Delete block.
 * 
 * @param  int    $index 
 * @access public
 * @return void
 */
function deleteBlock(index)
{
    $.getJSON(createLink('block', 'delete', 'index=' + index), function(data)
    {
        if(data.result != 'success')
        {
            alert(data.message);
            return false;
        }
    })
}

/**
 * Hidden block;
 * 
 * @param  index $index 
 * @access public
 * @return void
 */
function hiddenBlock(index)
{
    $.getJSON(createLink('block', 'delete', 'index=' + index + '&app=sys&type=hidden'), function(data)
    {
        if(data.result != 'success')
        {
            alert(data.message);
            return false;
        }
        reloadHome();
        $.zui.messager.info(ipsLang["hiddenBlock"]);
    })
}

/**
 * Sort blocks.
 * 
 * @param  object $orders  format is {'block2' : 1, 'block1' : 2, oldOrder : newOrder} 
 * @access public
 * @return void
 */
function sortBlocks(orders)
{
    var oldOrder = new Array();
    var newOrder = new Array();
    for(i in orders)
    {
       oldOrder.push(i.replace('block', ''));
       newOrder.push(orders[i]);
    }

    $.getJSON(createLink('block', 'sort', 'oldOrder=' + oldOrder.join(',') + '&newOrder=' + newOrder.join(',')), function(data)
    {
        if(data.result != 'success') return false;

        $('#home .panels-container .panel:not(.panel-dragging-holder)').each(function()
        {
            var $this = $(this);
            var index = $this.data('order');
            var url = createLink('entry', 'printBlock', 'index=' + index);
            /* Update new index for block id edit and delete. */
            $this.attr('id', 'block' + index).data('id', index).attr('data-url', url).data('url', url);
            $this.find('.panel-actions .edit-block').attr('href', createLink('block', 'admin', 'index=' + index));
        });
    });
}

/**
 * init sign in and sign out button.
 * 
 * @access public
 * @return void
 */
function initAttendButton()
{
    $('.signin').click(function()
    {
        $.getJSON(createLink('oa.attend', 'signin'), function(data)
        {
            if(data.result == 'success') $.zui.messager.success(data.message);
            if(data.result == 'fail') $.zui.messager.info(data.message);
        });
    });
    $('.signout').click(function()
    {
        $.getJSON(createLink('oa.attend', 'signout'), function(data)
        {
            if(data.result == 'success') window.location.href = createLink('user', 'logout');
            if(data.result == 'fail') $.zui.messager.info(data.message);
        });
    });
}
