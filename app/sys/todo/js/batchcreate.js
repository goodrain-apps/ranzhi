function updateAction(date)
{
  if(date.indexOf('-') != -1)
  {
    var datearray = date.split("-");
    var date = '';
    for(i=0 ; i<datearray.length ; i++)
    {
      date = date + datearray[i];
    }
  }

  var modal = $('#triggerModal');
  link = createLink('todo', 'batchCreate', 'date=' + date);
  modal.attr('ref', link);

  setTimeout(function()
  {
      modal.load(modal.attr('ref'), function(){$(this).find('.modal-dialog').css('width', $(this).data('width'));
      $.zui.ajustModalPosition()})
  }, 1000);
}

function switchDateList(number)
{
    if($('#switchDate' + number).prop('checked'))
    {
        $('[name=begins\\[' + number + '\\]]').attr('disabled', 'disabled');
        $('[name=ends\\[' + number + '\\]]').attr('disabled', 'disabled');
    }
    else
    {
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
