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