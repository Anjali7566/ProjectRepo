function handleTableRowSelect(newRow) {
    const selectedCSS = "selected";
    var rows = $('table.sleek tr');
    var wasSelected = $(newRow).hasClass(selectedCSS);
    rows.each(function() { $(this).removeClass(selectedCSS); });
    if (!wasSelected)
        $(newRow).addClass(selectedCSS);
}

var $th = $('table.sleek').find('thead tr');
$('.tableFixHead').on('scroll', function() {
    $th.css('transform', 'translateY('+ this.scrollTop +'px)');
});

$(window).on('load resize', function() {
    const xxs = 600;//768;
    const width = $(window).width();
    var disappearing = $("table.sleek .disappearing");
    if (width <= xxs)
        disappearing.hide();
    else
        disappearing.show();
});