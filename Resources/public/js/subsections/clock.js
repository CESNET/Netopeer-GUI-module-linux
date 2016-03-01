
$(document).ready(function() {
    var startingOffset = $("#clock-form input.timezone-offset").val();
    $(".timezones-map .timezone-offset-" + startingOffset).addClass("active-zone");
        
    $(".timezone-map").click(function(){
        var $img = $(this);
        var offset = $img.data('offset');

        var $inputOffset = $("#clock-form input.timezone-offset");
        if ($inputOffset !== null) {
            $inputOffset.val(offset);
        }

        $(".timezone-map").removeClass("active-zone");
        $(".timezones-map .timezone-offset-" + offset).addClass("active-zone");
    });   
});