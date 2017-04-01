$(document).ready(function()
{
    $('#menu .nav > li').removeClass('active').find('[href*=' + v.mode + ']').parent().addClass('active');
});
function switchDateList(number)
{
    if($('#switchDate' + number).prop('checked'))
    {
        $('[name=dates\\[' + number + '\\]]').attr('disabled', 'disabled');
        $('[name=begins\\[' + number + '\\]]').attr('disabled', 'disabled');
        $('[name=ends\\[' + number + '\\]]').attr('disabled', 'disabled');
    }
    else
    {
        $('[name=dates\\[' + number + '\\]]').removeAttr('disabled');
        $('[name=begins\\[' + number + '\\]]').removeAttr('disabled');
        $('[name=ends\\[' + number + '\\]]').removeAttr('disabled');
    }
}

function switchDateAll(switcher)
{
    if(switcher.checked)
    {
        $('[name^=switchDate]:not(:checked)').click();
    }
    else
    {
        $('[name^=switchDate]:checked').click();
    }
}

function setBeginsAndEnds(i, beginOrEnd)
{
    if(typeof i == 'undefined')
    {
        for(j = 0; j < batchCreateNum; j++)
        {
            if(j != 0) $("[name=begins\\[" + j + '\\]]')[0].selectedIndex = $("[name=ends\\[" + (j - 1) + '\\]]')[0].selectedIndex;
            $("[name=ends\\[" + j + '\\]]')[0].selectedIndex = $("[name=begins\\[" + j + '\\]]')[0].selectedIndex + 3;
        }
    }
    else
    {
        if(beginOrEnd == 'begin')
        {
            $("[name=ends\\[" + i + '\\]]')[0].selectedIndex = $("[name=begins\\[" + i + '\\]]')[0].selectedIndex + 3;
        }
        for(j = i+1; j < batchCreateNum; j++)
        {
            $("[name=begins\\[" + j + '\\]]')[0].selectedIndex = $("[name=ends\\[" + (j - 1) + '\\]]')[0].selectedIndex;
            $("[name=ends\\[" + j + '\\]')[0].selectedIndex = $("[name=begins\\[" + j + '\\]]')[0].selectedIndex + 3;
        }
    }
}

