function getIdNum(element) {
    //assuming input is like ...-...-#
    return parseInt(element.attr('id').split('-')[2]);//FIXME
}

function hideAllAbove(num) {
    if (isNaN(parseInt(num)))
        return;
    $(".document-receipt").each(function() {
        const idNum = getIdNum($(this));
        if (isNaN(idNum) || idNum > num)
            $(this).hide();
        else 
            $(this).show();
    })
}

$(document).ready(function() {
    const radioKeyword = "input[name='numreceipts'][type='radio']";
    const events = "click change";
    $(radioKeyword).on(events, function(e) {
        const value = $(radioKeyword + ":checked").val();
        if (value)
            hideAllAbove(value);
    });
    hideAllAbove(1);
});
