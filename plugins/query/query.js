$(document).ready(function () {
  
  $('.tables').click(function(){
    var table = $(this).html();
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
  
  $('#query_form').submit(function(e){
    e.preventDefault();
    $.get('./plugin.php',$(this).serialize(),function(antwort){
      $('#query_output').html(antwort);
    });
  });
});