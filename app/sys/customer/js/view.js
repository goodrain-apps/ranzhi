function setComment()
{
    $('#commentBox').toggle();
    $('.ke-container').css('width', '100%');
    setTimeout(function() { $('#commentBox textarea').focus(); }, 50);
}
