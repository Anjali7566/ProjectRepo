$(window).on('load resize', function() {
    const minWidth = 820;
    const width = $(window).width();
    var table = $('table.sleek');
    var message = $('#too-small');
    if (width > minWidth) {
        table.show();
        message.hide();
    }
    else {
        table.hide();
        message.show();
    }
});