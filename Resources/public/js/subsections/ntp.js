$(document).ready(function () {

    $('body').on('click', ".ntp-server.existing-server", function() {
        if ($("#ntp-form").find("input[name*=enabled]").first().val() === "false") {
            return;
        }
         
        var serverName = $(this).attr("class").replace("ntp-server existing-server", "");
        serverName = serverName.replace("link-active", "");
        var path = $(this).attr("data-path");

        var $form = $("#ntp-form").find(".server-subform[class*=" + serverName + "]").first();
        var $icon = $(".ntp-config-img .ntp-servers").find(".existing-server[class*=" + serverName + "]").first();
        var serverXpath = $form.find("input[name*=serverXpath]").first().val();


        createModalEditItemForm($form, $icon, path, serverXpath);
    });

    $(".ntp-server.new-server").click(function () {
        if ($("#ntp-form").find("input[name*=enabled]").first().val() === "false") {
            return;
        }

        var $form = $("#ntp-form").find("form").first();
        var path = $(this).attr("data-path");
        var serverNumber = $form.find("div.server-subform").length + 1;
        while($("#ntp-form").find(".server-subform[class*=" + "server-" + serverNumber + "]").length > 0) {
            serverNumber++;
        }

        var data = {serverNumber: serverNumber};

        var newServerFormDivClass = 'server-subform server-server-' + serverNumber;

        var $newServerImgDiv = $("#linux-body-content-ntp").find(".ntp-servers .ntp-server.new-server").first();
        var createdExistingServerImgDiv = getExistingNtpServerDiv(serverNumber, path);

        createModalNewItemForm($form, data, path, newServerFormDivClass, $newServerImgDiv, createdExistingServerImgDiv);
    });
    
    $("#block-linux-body").on("click", "button#enable-ntp", function(){
        $("#block-linux-body-content").find("form").find("input[name*=enabled]").val(true);
        var $servers = $("#block-linux-body-content .ntp-servers").find("div.link-inactive");
        $servers.removeClass("link-inactive");
        $servers.addClass("link-active");
        
    });
    
    $("#block-linux-body").on("click", "button#disable-ntp", function(){
        $("#block-linux-body-content").find("form").find("input[name*=enabled]").val(false);
        var $servers = $("#block-linux-body-content .ntp-servers").find("div.link-active");
        $servers.removeClass("link-active");
        $servers.addClass("link-inactive");
    });
});


function getExistingNtpServerDiv(serverNumber, path) {
    var existingServerDivClass = "ntp-server existing-server server-server-" + serverNumber + " link-active";
    var existingServerDiv = "<div class='" + existingServerDivClass + "' data-path='" + path + "'>";
    existingServerDiv += "<span class='server-name'>server-" + serverNumber + "</span>";
    existingServerDiv += "<br><i class='fa fa-server fa-3x' aria-hidden='true'></i>";
    existingServerDiv += "</div>";
    return existingServerDiv;
}