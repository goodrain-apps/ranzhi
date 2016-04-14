$(function()
{
    /* show team menu. */
    $('[name^=multiple]').change(function()
    {
        if($(this).prop('checked'))
        {
            $('#assignedTo, #assignedTo_chosen').addClass('hidden');
            $('.team-group').removeClass('hidden');
            $('#estimate').attr('readonly', true);
        }
        else
        {
            $('#assignedTo, #assignedTo_chosen').removeClass('hidden');
            $('.team-group').addClass('hidden');
            $('#estimate').attr('readonly', false);
        }
    });

    $('#modalTeam .btn').click(function()
    {
        var team = '';
        var time = 0;
        $('[name*=team]').each(function()
        {
            if($(this).find('option:selected').text() != '')
            {
                team += ' ' + $(this).find('option:selected').text();
            }

            estimate = parseFloat($(this).parents('td').next('td').find('[name*=teamEstimate]').val());
            if(!isNaN(estimate))
            {
                time += estimate;
            }

            $('#teamMember').val(team);
            $('#estimate').val(time);
        })
    })
})
