$(document).ready(function () {
  updateToolbar();
  window.setInterval("autoUpdate()", 30000);
});


function autoUpdate()
{
   updateToolbar();
   updateData();
}

function updateToolbar()
{
   //Refresh of Tactical Data in navigation bar

   
   $.get('./index.php',{output_format: 'json'},function(output){
    var pos_host_total = $("#nav_host_total");
    var pos_host_down = $("#nav_host_down");
    var pos_host_unhandled = $("#nav_host_unhandeld");
  
    var pos_service_total = $("#nav_service_total");
    var pos_service_crit = $("#nav_service_crit");
    var pos_service_unhandled = $("#nav_service_unhandeld");
  

    pos_host_total.html('Hosts: '+ output.host_total);
    pos_host_down.html(output.host_down);
    pos_host_unhandled.html(output.host_unhandled);
    
    pos_service_total.html('Services: '+output.service_total);
    pos_service_crit.html(output.service_crit);
    pos_service_unhandled.html(output.service_unhandled);
    
    
    if(output.host_down != 0)
    {pos_host_down.addClass('nav_unhandled');
    }else{pos_host_down.removeClass('nav_unhandled');}
    
    if(output.host_unhandled != 0)
    {pos_host_unhandled.addClass('nav_error');
    }else{pos_host_unhandled.removeClass('nav_error');}
    
    if(output.service_crit != 0)
    {pos_service_crit.addClass('nav_unhandled');
    }else{pos_service_crit.removeClass('nav_unhandled');}
    
    if(output.service_unhandled != 0)
    {pos_service_unhandled.addClass('nav_error');
    }else{pos_service_unhandled.removeClass('nav_error');} 
  });
}


function updateData()
{
  var form = $('#autorefresh_form');
  var url  = $('#autorefresh_url').val();
  if(form.length > 0)
  {
    $.get(url,form.serialize(),function(data){
    $('#autorefresh_field').html(data);
    });
  }
}
