$(document).ready(function()
{
    $('[name*=tree]').each(function()
    {
        if($(this).val() == 'browse') $(this).parent('label').css('width', '50%');
    });
    $('[name*=setting]').each(function()
    {
        if($(this).val() == 'lang') $(this).parent('label').css('width', '50%');
    });
    $('[name*=report]').each(function()
    {
        if($(this).val() == 'browse') $(this).parent('label').css('width', '50%');
    });
})

function showPriv(value)
{
  location.href = createLink('group', 'managePriv', "type=byGroup&param="+ groupID + "&menu=&version=" + value);
}

$('.checkApp').click(function()
{
    $(this).parents('.item').find('[type=checkbox]').prop('checked', $(this).prop('checked'));
});

$('.checkModule').click(function()
{
    $(this).parents('tr').find('[type=checkbox]').prop('checked', $(this).prop('checked'));
});
