$(window).on('load resize', function() {
    const xs = 993;//768;
    const xxs = 600;//768;
    const xxxs = 370;
    const width = $(window).width();
    var fontSize = 27;
    if (width <= xxxs)
        fontSize = 10;
    else if (width <= xxs)
        fontSize = 12;
    else if (width <= xs)
        fontSize = 18;
    $("#myhtml, body, div.main-content").css("font-size", fontSize + "px");
});
