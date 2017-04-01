$(document).ready(function()
{
    link = $('#backButton').attr('href');
    if(link.indexOf('provider') != -1) 
    {
        $('#backButton').attr('href', '');
        $('#backButton').click(function()
        {
            $.openEntry('cash', link);
        })
    }
})
function setComment()
{
    $('#commentBox').toggle();
    $('.ke-container').css('width', '100%');
    setTimeout(function() { $('#commentBox textarea').focus(); }, 50);
}
