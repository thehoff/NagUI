$(document).ready(function () {
  $('#tables').click(function(){
    var table = $(this).val();
    var queryfield = $('#query');
    var old_query = queryfield.val().split('\n');
    if(!old_query[0].search(/^GET.*/))
    {
      old_query[0] = 'GET '+table;
      queryfield.val(old_query.join('\n'));
    }else
    {
      queryfield.val('GET '+table+'\n');
    }
  });
  
  $('#hidelink').click(function(){
    $('#query_form').toggle();
  });
});