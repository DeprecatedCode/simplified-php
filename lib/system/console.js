$(function() {
    var c = $('#sphp-console');
    c.attr('disabled', false).focus();
    c.on('keydown', function(e) {
        if(e.ctrlKey && e.keyCode == 76) {
            $('#sphp-content').html('');
        }
        else if(e.keyCode == 13) {
            $.post('?!=exec', {'code': c.val()}, function(data) {
                $('#sphp-content').prepend(data);
            });
            c.val('');
            return false;
        }
    });
});