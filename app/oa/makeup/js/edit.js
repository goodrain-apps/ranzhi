$(document).ready(function()
{
    $('[name=type], #begin, #start, #end, #finish').change(function()
    {
        var type   = $('input[name=type]:checked').val();
        var begin  = $('#begin').val();
        var end    = $('#end').val();
        var start  = $('#start').val();
        var finish = $('#finish').val();
        if(!begin || !end || !start || !finish) return false;

        begin = begin.replace(/-/g, '/');
        end   = end.replace(/-/g, '/');

        var hours = 0;
        var beginTime = Date.parse(new Date(begin + ' ' + start));
        var endTime   = Date.parse(new Date(end + ' ' + finish));
        if(beginTime > endTime) return false;

        if(begin == end)
        {
            hours = Math.round((endTime - beginTime)/(3600*1000)*100)/100;
            if(type == 'compensate' && hours > v.workingHours) hours = v.workingHours;
        }
        else
        {
            var signOutTime  = Date.parse(new Date(begin + ' ' + v.signOut));
            var signInTime   = Date.parse(new Date(end + ' ' + v.signIn));
            var hoursStart   = 0;
            var hoursEnd     = 0;
            var hoursContent = 0;
            
            if(beginTime < signOutTime)  
            {
                hoursStart = Math.round((signOutTime - beginTime)/(3600*1000)*100)/100;
            }
            else
            {
                hoursStart = Math.round((Date.parse(new Date(begin + ' 23:59:60')) - beginTime)/(3600*1000)*100)/100;
            }
            if(type == 'compensate' && hoursStart > v.workingHours) hoursStart = v.workingHours;
            
            if(endTime > signInTime)  
            {
                hoursEnd = Math.round((endTime - signInTime)/(3600*1000)*100)/100;
            }
            else
            {
                hoursEnd = Math.round((endTime - Date.parse(new Date(end)))/(3600*1000)*100)/100;
            }
            if(type == 'compensate' && hoursEnd > v.workingHours) hoursEnd = v.workingHours;
            
            var dayHours = Math.round((Date.parse(new Date(begin + ' ' + v.signOut)) - Date.parse(new Date(begin + ' ' + v.signIn)))/(3600*1000)*100)/100;
            if(type == 'compensate' && dayHours > v.workingHours) dayHours = v.workingHours;
            
            var days = Math.floor((Date.parse(new Date(end)) - Date.parse(new Date(begin)))/(24*3600*1000));
            if(days > 1) hoursContent = (days - 1) * dayHours;

            hours = hoursStart + hoursEnd + hoursContent;
        }
        $('#hours').val(hours);
    });
})
