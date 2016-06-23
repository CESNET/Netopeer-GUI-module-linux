$(document).ajaxStart(function() {
    $("#ajax-spinner").show();
});

$(document).ajaxStop(function() {
    $("#ajax-spinner").fadeOut();
});



$(document).ready(function() {
    // Just for debugging
    $("button.toggle-main-xml").click(function(){
        var button = $(this);
        if (button.html() == "Show xml") {
            button.html("Hide xml");
        }
        else {
            button.html("Show xml");
        }
        $("#block-linux-debugging .xml").toggle();
    });
});