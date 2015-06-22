
function deactivateZone(){
    $(".timezone").removeClass("active-zone");
}


$(document).ready(function() {
	$("button").click(function(){
            $(".xml").toggle();
        });
        
        $(".timezone").click(function(){
            deactivateZone();
            $(this).toggleClass("active-zone");
        });
});