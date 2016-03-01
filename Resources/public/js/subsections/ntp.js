$(document).ready(function () {
    $(".ntp-server.existing-server").click(function () {
        //var $targetDiv = e.target;

        var serverName = $(this).attr("class").replace("ntp-server existing-server", "");
        var path = $(this).attr("data-path");

        var data = [];
        var $form = $("#ntp-form").find(".server-subform[class*=" + serverName + "]").first();
        data = data.concat($form.find(":input").serializeArray());


        $.post(path, data, function (result) {
            var $modal = $(result);
            $modal.modal();

            $(".modal .modal-content").on("click", "button.submit", function (e) {
                e.preventDefault();
                var $modalForm = $(".modal .modal-content").find("form").first();
                var modalData = [];
                modalData = modalData.concat($modalForm.find(":input").serializeArray());

                $.post($modalForm.attr("action"), modalData, function (modalResult) {
                    $modal.modal("hide");
                    $form.html(modalResult);
                });
            });
        });
    });
    
    $("#block-linux-body").on("click", "button#enable-ntp", function(){
        $("#block-linux-body-content").find("form").find("input[name*=enabled]").val(true);
    });
    
    $("#block-linux-body").on("click", "button#disable-ntp", function(){
        $("#block-linux-body-content").find("form").find("input[name*=enabled]").val(false);
    });
});