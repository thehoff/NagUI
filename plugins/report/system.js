$(document).ready(function () {
    $('.report_details').click(function(e){
        var what = $(this).attr('r_was');
        var item = $(this).attr('r_item');
		var time = $(this).attr('r_time');
		var year = $(this).attr('r_year');
		var source = $(this).attr('r_source');
		var soft = $(this).attr('r_soft');
		var warning = $(this).attr('r_warning');
		var jstime = new Date();
		var div_id = jstime.getTime();
        $.get('plugin.php', {name: 'report', file: 'result', job: 'details', item: item, was: what, source: source, soft: soft, warning: warning, dialog_id: div_id, time: time, year: year}, function(antwort){
            $('#dialog_'+div_id).remove();
            $('body').append(antwort);
            $('#dialog_'+div_id).dialog({ 
				minWidth: 800,
				minHeigth: 600,
			});
        });
    });
	
	
	$('.report_timeline').click(function(e){
        var what = $(this).attr('r_was');
        var item = $(this).attr('r_item');
		var time = $(this).attr('r_time');
		var year = $(this).attr('r_year');
		var source = $(this).attr('r_source');
		var soft = $(this).attr('r_soft');
		var warning = $(this).attr('r_warning');
		var jstime = new Date();
		var div_id = jstime.getTime();
        $.get('plugin.php', {name: 'report', file: 'result',  job: 'stats', item: item, was: what, source: source, soft: soft, warning: warning, dialog_id: div_id, time: time, year: year}, function(antwort){
            $('#dialog_'+div_id).remove();
            $('body').append(antwort);
            $('#dialog_'+div_id).dialog({ 
				minWidth: 800,
				minHeigth: 600,
			});
        });
    });
	
});