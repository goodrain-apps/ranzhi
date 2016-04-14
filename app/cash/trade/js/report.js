$(document).ready(function()
{
    var labels   = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    var datasets = [];

    var datasetIn  = {label: $('#barChart').find('thead .chart-label-in').text(), color: 'green', data: []};
    var datasetOut = {label: $('#barChart').find('thead .chart-label-out').text(), color: 'red', data: []};
    
    $('#barChart').find('.chart-color-dot-in').css('color', 'green');
    $('#barChart').find('.chart-color-dot-out').css('color', 'red');
    
    var chartLabels = [];
    $('#barChart').find('tbody .chart-label').each(function(){ chartLabels.push($(this).text()); })

    $.each(labels, function(key, value)
    {
        if($.inArray(value, chartLabels) != -1)
        {
            $('#barChart').find('tbody .chart-value-in').each(function()
            {
                if($(this).parent('tr').find('.chart-label').text() == value)
                {
                    datasetIn.data.push(parseInt($(this).text()));
                }
            })

            $('#barChart').find('tbody .chart-value-out').each(function()
            {
                if($(this).parent('tr').find('.chart-label').text() == value)
                {
                    datasetOut.data.push(parseInt($(this).text()));
                }
            })
        }
        else
        {
            datasetIn.data.push(parseInt(0));
            datasetOut.data.push(parseInt(0));
        }
    })
    
    var data = {labels: labels, datasets: [datasetIn, datasetOut]};
    
    var options = {multiTooltipTemplate: "<%= datasetLabel %> <%= value %>"};
    chart = $('#myBarChart').barChart(data, options);
    
    $('#currency').change(function()
    {
       var selectYear     = $('#year option:selected').text();
       var selectCurrency = $('#currency').val();
       location.href = createLink('trade', 'report', "date=" + selectYear + "&currency=" + selectCurrency);
    })
})
