$(document).ready(function () {
    $('body').on('click', ".radius-server.existing-server", function() {
        var serverName = $(this).attr("class").replace("radius-server existing-server", "");
        serverName = serverName.replace("link-active", "");
        var path = $(this).attr("data-path");

        var $form = $("#radius-form").find(".server-subform[class*=" + serverName + "]").first();
        var $icon = $(".radius-config-img .radius-servers").find(".existing-server[class*=" + serverName + "]").first();
        var serverXpath = $form.find("input[name*=serverXpath]").first().val();

        createModalEditItemForm($form, $icon, path, serverXpath);
    });

    $(".radius-server.new-server").click(function () {
        var $form = $("#radius-form").find("form").first();
        var path = $(this).attr("data-path");
        var serverNumber = $form.find("div.server-subform").length + 1;
        var data = {serverNumber: serverNumber};
        //data = data.concat(({name: 'serverNumber', value: 5}).serializeArray());

        var newServerFormDivClass = 'server-subform server-server-' + serverNumber;

        var $newServerImgDiv = $("#linux-body-content-radius").find(".radius-servers .radius-server.new-server").first();
        var createdExistingServerImgDiv = getExistingradiusServerDiv(serverNumber, path);

        createModalNewItemForm($form, data, path, newServerFormDivClass, $newServerImgDiv, createdExistingServerImgDiv);
    });
});


function getExistingradiusServerDiv(serverNumber, path) {
    var existingServerDivClass = "radius-server existing-server server-server-" + serverNumber + " link-active";
    var existingServerDiv = "<div class='" + existingServerDivClass + "' data-path='" + path + "'>";
    existingServerDiv += "<span class='server-name'>server-" + serverNumber + "</span>";
    existingServerDiv += "<br><i class='fa fa-server fa-3x' aria-hidden='true'></i>";
    existingServerDiv += "</div>";
    return existingServerDiv;
}
