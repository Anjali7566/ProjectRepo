/*
FIXME doesn't work
$(window).on('load', function() {
    const screenHeight = $(window).height();
    const navHeight = $('nav.nav-height').height();
    const padding = (screenHeight - navHeight) / 4.0;
    $('div.user-form .pad').css('padding-top', padding);
});
*/



$(window).on('load resize', function() {
    const xs = 993;//768;
    const width = $(window).width();
    var textAlign = (width <= xs) ? 'center' : 'right';
    $('.field-name').css('text-align', textAlign);
});