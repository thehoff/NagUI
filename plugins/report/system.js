$(document).ready(function () {
    $('.report_details').click(function(e){
        var what = $(this).attr('r_was');
        var item = $(this).attr('r_item');
		var title = $(this).attr('r_title');
		var source = $(this).attr('r_source');
        $.get('plugin.php', {name: 'report', file: 'result', job: 'details', item: item, was: what, title: title, source: source}, function(antwort){
            $('#dialog_'+item).remove();
            $('body').append(antwort);
            $('#dialog_'+item).dialog();
        });
    });
});