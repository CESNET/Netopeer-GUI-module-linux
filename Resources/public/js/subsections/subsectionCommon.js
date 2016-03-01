$(document).ready(function() {
    $("#block-linux-body").on("click", ".back-to-system-section", function() {
        var path = $(this).attr("data-path");
        window.location.href = path;
    });   
    
    $("#block-linux-body").on("click", ".save-subsection", function(){
        $("#block-linux-body-content").find("form").submit();
    });
});


