$(document).ready(function()
{
    $("#menu .nav a[href*=" + config.currentMethod + "]").parent().addClass('active');
    $("#mainNavbar .navbar-nav a[href*=trade][href*=report]").parent().addClass('active');
    $('#menu .nav a[href*=export2Excel]').addClass('iframe');

    $('.table-chart').each(function()
    {
        var $table = $(this);
        var labels     = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        var datasets   = [];

        var datasetIn  = {label: $table.find('thead .chart-label-1').text(), color: 'primary', data: []};
        var datasetOut = {label: $table.find('thead .chart-label-2').text(), color: 'green', data: []};
        
        $table.find('.chart-color-dot-1').css('color', '#91B8F6');
        $table.find('.chart-color-dot-2').css('color', 'green');
        
        var chartLabels = [];
        $table.find('tbody .chart-label').each(function(){ chartLabels.push($(this).text()); })

        $.each(labels, function(key, value)
        {
            if($.inArray(value, chartLabels) != -1)
            {
                $table.find('tbody .chart-value-1').each(function()
                {
                    if($(this).parent('tr').find('.chart-label').text() == value)
                    {
                        datasetIn.data.push(parseFloat($(this).text()));
                    }
                })

                $table.find('tbody .chart-value-2').each(function()
                {
                    if($(this).parent('tr').find('.chart-label').text() == value)
                    {
                        datasetOut.data.push(parseFloat($(this).text()));
                    }
                })
            }
            else
            {
                datasetIn.data.push(parseFloat(0));
                datasetOut.data.push(parseFloat(0));
            }
        })
        
        var data = {labels: labels, datasets: [datasetIn, datasetOut]};
        
        var options = {multiTooltipTemplate: "<%= datasetLabel %> <%= value %>"};
        chart = $($table.data().target).barChart(data, options);
    });
    
    if($("input[name='years[]']:checked").length < 2)
    {
        $("input[name='years[]']:lt(2)").attr('checked', 'checked');
    }

    $("input[name='years[]']").each(function(){ if(!$(this).is(':checked')) $(this).attr("disabled","disabled")});

    $("input[name='years[]']").click(function()
    {
        if ($("input[name='years[]']:checked").length >= 2)
        {   
            $("input[name='years[]']").each(function(){ if(!$(this).is(':checked')) $(this).attr("disabled","disabled")});
        }   
        else
        {   
            $("input[name='years[]']").each(function(){$(this).attr("disabled", false)});
        }   
    });

    $('#submit').click(function()
    {
        if($("input[name='years[]']:checked").length < 2)
        {   
            bootbox.alert(v.compareTip);
            return false;
        }
    });

    $('.table-wrapper').height($('.chart-wrapper').outerHeight() - 2);
})
