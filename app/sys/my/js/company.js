$(document).ready(function()
{
    $('.submit').click(function()
    {
        var dept    = $('#dept').val();
        var account = $('#account').val();
        var begin   = $('#begin').val();
        var end     = $('#end').val();
        begin = begin.replace(/-/g, '');
        end = end.replace(/-/g, '');

        var url = createLink('my', 'company', "type=" + v.type + "&dept=" + dept + "&account=" + account + "&begin=" + begin + "&end=" + end);
        location.href = url;
    });
    $('table.datatable').datatable();
});
