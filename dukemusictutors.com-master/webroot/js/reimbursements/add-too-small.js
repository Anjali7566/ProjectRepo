// copied from table-too-small.js
$(window).on('load resize', function() {
    const minWidth = 1050;
    const width = $(window).width();
    var form = $('div.myform');
    var message = $('#too-small');
    if (width > minWidth) {
        form.show();
        message.hide();
    }
    else {
        form.hide();
        message.show();
    }
});