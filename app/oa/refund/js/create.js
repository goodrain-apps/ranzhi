$(document).ready(function()
{
    /* show or hide detail. */
    $(document).on('click', '.detail', function()
    {
        if($(this).find('i').hasClass('icon-double-angle-down'))
        {
            $('#refund-detail').removeClass('hidden');
            $('#money').prop('readonly', 'readonly');
            $('#refund-date').addClass('hidden');
            $('#refund-related').addClass('hidden');
            $(this).find('i').removeClass('icon-double-angle-down');
            $(this).find('i').addClass('icon-double-angle-up');
        }
        else
        {
            $('input[name^=moneyList]').val('');
            $('#refund-detail').addClass('hidden');
            $('#money').prop('readonly', '');
            $('#refund-date').removeClass('hidden');
            $('#refund-related').removeClass('hidden');
            $(this).find('i').removeClass('icon-double-angle-up');
            $(this).find('i').addClass('icon-double-angle-down');
        }
        return false;
    });

    /* update money. */
    function updateMoney()
    {
        var money = 0;
        $('input[name^=moneyList]').each(function()
        {
            if($.isNumeric($(this).val()))
            {
                money += parseFloat($(this).val());
            }
        });
        money = Math.round(money * 100) / 100;
        $('#money').val(money);
        return false;
    }
    $('input[name^=moneyList]').change(updateMoney);

    /* Add a trade detail item. */
    $(document).on('click', '.table-detail .icon-plus', function()
    {
        $(this).closest('tr').after($('#detailTpl').html().replace(/key/g, v.key));
        $(this).closest('tr').next().find("select").chosen({no_results_text: v.noResultsMatch, disable_search_threshold: 1, search_contains: true, width: '100%', allow_single_deselect: true});
        var options = window.datetimepickerDefaultOptions;
        $.extend(options, {startView: 2, minView: 2, maxView: 1, format: 'yyyy-mm-dd'})
        $(this).closest('tr').next().find("[name^='dateList']").fixedDate().datetimepicker(options);
        $('input[name^=moneyList]').change(updateMoney);
        v.key++;
        return false;
    });

    /* Remove a trade detail item. */
    $(document).on('click', '.table-detail .icon-remove', function()
    {
        if($('#detailBox tr').size() > 1)
        {
            $(this).closest('tr').remove();
        }
        else
        {
            $(this).closest('tr').find('input,select').val('');
        }
        $('input[name^=moneyList]').change();
        return false;
    });
});
