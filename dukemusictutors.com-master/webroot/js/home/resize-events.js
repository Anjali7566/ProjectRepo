$(window).on('load resize', function() {
    const xs = 993;//768;
    const centerText = 'center-text';

    var width = $(window).width();
    var eventsContent = $('#events-content');
    var mdEvents = $('#events-md').children();
    var xsEvents = $('#events-xs').children();
    if (width <= xs) {
        eventsContent.addClass(centerText);
        hideChildren(mdEvents);
        showChildren(xsEvents);
    }
    else {
        eventsContent.removeClass(centerText);
        showChildren(mdEvents);
        hideChildren(xsEvents);
    }

    function hideChildren(parent) {
        var children = parent.children();
        parent.hide();
        if (children.length > 0) {
            children.each(function() { hideChildren($(this));})
        }
    }

    function showChildren(parent) {
        var children = parent.children();
        parent.show();
        if (children.length > 0) {
            children.each(function() { showChildren($(this));})
        }
    }
});