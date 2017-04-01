$(function()
{
    if(v.backLink !== undefined) $('#menu .nav:first').append('<li>' + v.backLink + '</li>');
    if($.cookie('projectStatus') && v.projectID)
    {
        $('#mainNavbar .nav li').removeClass('active').find('[href*=' + $.cookie('projectStatus') + ']').parent().addClass('active');
    }

    /* Set style of priority options in form */
    $('form .pri[data-value="' + $('form #pri').val() + '"]').addClass('active');
    $('form .pri').click(function()
    {
        $('form .pri.active').removeClass('active');
        $('form #pri').val($(this).addClass('active').data('value'));
    });

    $('#menu li[data-group="' + v.groupBy + '"]').addClass('active');

    $('.task-toggle').click(function()
    {
        var obj = $(this).find('i');
        if(obj.hasClass('icon-plus'))
        {
           obj.parents('tr').next('tr').show();
           obj.removeClass('icon-plus').addClass('icon-minus');
        }
        else if(obj.hasClass('icon-minus'))
        {
           obj.parents('tr').next('tr').hide();
           obj.removeClass('icon-minus').addClass('icon-plus');
        }
        return false;
    });

    /* Add parent task link to menu. */
    if($('.addonMenu').length)
    {
        $('#menu .nav li:last').html(v.viewChild);
        $('#menu .nav li:last').before($('.addonMenu').html());
        $('.addonMenu').remove();
    }

    $('.switcher').on('click', function()
    {
        var url = $(this).attr('href');
        bootbox.confirm($(this).data('confirm'),  function(result)
        {
            if(result)
            {
                $.getJSON(url, function(response)
                {
                    if(response.result == 'success')
                    {
                        bootbox.alert(response.message, function(){location.reload()});
                    }
                    else
                    {
                        bootbox.alert(response.message);
                    }
                })
            }
        })
        return false;
    });

    $('.btn-move-up, .btn-move-down').click(function()
    {
        var $this = $(this);
        if($this.hasClass('btn-move-up'))
        {
            $(this).parents('tr').prev().before($(this).parents('tr'));
        }
        else
        {
            $this.parents('tr').next().after($(this).parents('tr'));
        }
        $('.btn-move-up, .btn-move-down').removeClass('disabled').removeAttr('disabled');

        adjustSortBtn();
    });

    adjustSortBtn();
});

function adjustSortBtn()
{
    $('.btn-move-up:first').addClass('disabled').attr('disabled', 'disabled');
    $('.btn-move-down:last').addClass('disabled').attr('disabled', 'disabled');
}

function setComment()
{
    $('#commentBox').toggle();
    $('.ke-container').css('width', '100%');
    setTimeout(function() { $('#commentBox textarea').focus(); }, 50);
}
