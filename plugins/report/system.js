$(document).ready(function () {
    $('.report_details').click(function(e){
        var what = $(this).attr('r_was');
        var item = $(this).attr('r_item');
        $.get('plugins/report/result.php', {job: 'details', item: item, was: what}, function(antwort){
            $('#dialog_'+item).remove();
            $('body').append(antwort);
            $('#dialog_'+item).dialog();
        });
    });
});