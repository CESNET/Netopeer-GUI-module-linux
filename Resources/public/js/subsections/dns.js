$(document).ready(function () {
    $('body').on('click', ".dns-server.existing-server", function() {
        var serverName = $(this).attr("class").replace("dns-server existing-server", "");
        serverName = serverName.replace("link-active", "");
        var path = $(this).attr("data-path");

        var $form = $("#dns-form").find(".server-subform[class*=" + serverName + "]").first();
        var $icon = $(".dns-config-img .dns-servers").find(".existing-server[class*=" + serverName + "]").first();
        var serverXpath = $form.find("input[name*=serverXpath]").first().val();

        createModalEditItemForm($form, $icon, path, serverXpath);
    });

    $(".dns-server.new-server").click(function () {
        var $form = $("#dns-form").find("form").first();
        var path = $(this).attr("data-path");
        var serverNumber = $form.find("div.server-subform").length + 1;
        var data = {serverNumber: serverNumber};

        var newServerFormDivClass = 'server-subform server-nameserver-' + serverNumber;

        var $newServerImgDiv = $("#linux-body-content-dns-resolver").find(".dns-servers .dns-server.new-server").first();
        var createdExistingServerImgDiv = getExistingDnsServerDiv(serverNumber, path);

        createModalNewItemForm($form, data, path, newServerFormDivClass, $newServerImgDiv, createdExistingServerImgDiv);
    });
});


function getExistingDnsServerDiv(serverNumber, path) {
    var existingServerDivClass = "dns-server existing-server server-nameserver-" + serverNumber + " link-active";
    var existingServerDiv = "<div class='" + existingServerDivClass + "' data-path='" + path + "'>";
    existingServerDiv += "<span class='server-name'>nameserver-" + serverNumber + "</span>";
    existingServerDiv += "<br><i class='fa fa-server fa-3x' aria-hidden='true'></i>";
    existingServerDiv += "</div>";
    return existingServerDiv;
}
