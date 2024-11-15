$(document).scroll(function(){
    function addNavShadow() {
        $('nav').addClass('shadow');
    }

    function removeNavShadow() {
        $('nav').removeClass('shadow');
    }

    var scrollTop = $(window).scrollTop();
    if (scrollTop > 1) {
        addNavShadow();
    }
    else {
        removeNavShadow();
    }
});
