$(document).ready(function()
{
    $('#menu a[href*=setReportUnit]').attr({'data-toggle' : 'modal', 'data-width' : 400});

    $('[name*=objectType]').change(function()
    {
        if($(this).prop('checked')) 
        {
            $('[name*=objectType]').not(this).prop('checked', false).change();
            $('.traderTR').hide();
        }
        else
        {
            $('.traderTR').show();
        }
        $('#' + $(this).val()).parents('tr').toggle($(this).prop('checked'))
        if($(this).val() == 'order') $('.customerTR').toggle($(this).prop('checked'));
        if($(this).val() == 'contract') $('.allCustomerTR').toggle($(this).prop('checked'));
    })

    $('[name*=objectType]').each(function()
    {
        if($(this).prop('checked')) $(this).change();
    });

    /* Toggle create trader items. */
    $('[name*=createTrader]').change(function()
    {
        if($(this).prop('checked')) 
        {
            $(this).parents('.input-group').find('select').hide();
            $(this).parents('.input-group').find('[id*=trader][id*=_chosen]').hide();
            $(this).parents('.input-group').find('input[type=text][id*=traderName]').show().focus();
            $(this).parents('.input-group-addon').find('.icon-question').hide();
        }
        else
        {
            $(this).parents('.input-group').find('[id*=trader][id*=_chosen]').show();
            $(this).parents('.input-group').find('input[type=text][id*=traderName]').hide();
        }
    })

    /* Highlight submenu. */
    if(config.requestType == 'GET')
    {
        if(v.mode == 'in')
        {
            $('#mainNavbar li').removeClass('active').find("[href*='mode\\=" + v.mode + "']").not('[href*=mode\\=invest]').parent().addClass('active');
        }
        else
        {
            $('#mainNavbar li').removeClass('active').find("[href*='mode\\=" + v.mode + "']").parent().addClass('active');
        }
    }
    else
    {
        $('#mainNavbar li').removeClass('active');
        if(v.mode == 'in')
        {
            $('#mainNavbar li').find("[href*='trade-browse-in.html']").parent().addClass('active');
        }
        else
        {
            $('#mainNavbar li').find("[href*='-" + v.mode + "']").parent().addClass('active');
        }
    }

    $(document).on('change', '#customer,#trader', function()
    {
        if($(this).val())
        {
            $.get(createLink('trade', 'ajaxGetDepositor', 'customer=' + $(this).val()), function(depositor)
            {
                if(depositor)  $('tr.customer-depositor').show().find('#customerDepositor').val(depositor);
                if(!depositor) $('tr.customer-depositor').hide();
            });
        }
        else
        {
            $('tr.customer-depositor').hide();
        }
    })

    $('#customer,#trader').change();
})

/**
 * Get contract of a trader. 
 * 
 * @param  int    $traderID 
 * @access public
 * @return void
 */
function getContract(traderID)
{
    if(traderID == '') return false;
    $('.contractTD select').empty().load(createLink('crm.contract', 'getOptionMenu', 'traderID=' + traderID));
}
