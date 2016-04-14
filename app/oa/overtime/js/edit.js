$(document).ready(function()
{
    $('#begin, #start, #end, #finish').change(function()
    {
        var begin  = $('#begin').val();
        var end    = $('#end').val();
        var start  = $('#start').val();
        var finish = $('#finish').val();
        if(!begin || !end || !start || !finish) return false;

        begin = begin.replace(/-/g, ',');
        end   = end.replace(/-/g, ',');

        var hours = 0;
        var beginTime = Date.parse(new Date(begin + ' ' + start));
        var endTime   = Date.parse(new Date(end + ' ' + finish));
        var dayHours  = ((Date.parse(new Date(begin + ' ' + v.signOut)) - Date.parse(new Date(begin + ' ' + v.signIn)))/(3600*100))/10;
        if(beginTime > endTime) return false;

        if(begin == end)
        {
            hours = ((endTime - beginTime)/(3600*100))/10;
        }
        else
        {
            var signOutTime  = Date.parse(new Date(begin + ' ' + v.signOut));
            var signInTime   = Date.parse(new Date(end + ' ' + v.signIn));
            var hoursStart   = 0;
            var hoursEnd     = 0;
            var hoursContent = 0;
            if(beginTime < signOutTime) hoursStart = ((signOutTime - beginTime)/(3600*100))/10;
            if(endTime > signInTime) hoursEnd = ((endTime - signInTime)/(3600*100))/10;
            var days = Math.floor((Date.parse(new Date(end)) - Date.parse(new Date(begin)))/(24*3600*1000));
            if(days > 1) hoursContent = (days - 1) * dayHours;

            hours = hoursStart + hoursEnd + hoursContent;
        }
        $('#hours').val(hours);
    });
})
