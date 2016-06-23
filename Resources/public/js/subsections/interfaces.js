$(document).ready(function () {
    $('body').on('click', ".interfaces-interface.existing-interface", function() {
        var interfaceName = $(this).attr("class").replace("interfaces-interface existing-interface", "");
        interfaceName = interfaceName.replace("link-active", "");
        var path = $(this).attr("data-path");

        var data = [];
        var $form = $("#interface-form").find(".interface-subform[class*=" + interfaceName + "]").first();
        data = data.concat($form.find(":input").serializeArray());

        var $icon = $(".interfaces-config-img .interfaces-interface").find(".existing-interface[class*=" + interfaceName + "]").first();
        var interfaceXpath = $form.find("input[name*=interfaceXpath]").first().val();

        createModalEditItemForm($form, $icon, path, interfaceXpath);
    });

    $(".interfaces-interface.new-interface").click(function () {
        var $form = $("#interface-form").find("form").first();
        var path = $(this).attr("data-path");
        var interfaceNumber = $form.find("div.interface-subform").length + 1;
        var data = {interfaceNumber: interfaceNumber};
        //data = data.concat(({name: 'serverNumber', value: 5}).serializeArray());

        //  var userName = $(this).attr("class").replace("user existing-user", "");
        //  userName = userName.replace("link-active", "");

        var $newInterfaceImgDiv = $("#linux-body-content-interface").find(".interfaces-interfaces .interfaces-interface.new-interface").first();


        // createModalNewItemForm($form, data, path, newUserFormDivClass, $newUserImgDiv, createdExistingUserImgDiv);

        $.post(path, data, function (result) {
            var $modal = $(result);
            $modal.find("input[name*=name]").first().attr("readonly", false);
            $modal.find("input[name*=name]").first().closest(".input-group").removeClass("readonly");
            $modal.modal();

            $(".modal .modal-content").on("click", "button.submit", function (e) {
                e.preventDefault();
                var $modalForm = $(".modal .modal-content").find("form").first();
                var interfaceName = $modalForm.find("input[name*=name]").first().val();
                var newInterfaceFormDivClass = 'interface-subform interface-' + interfaceName;
                // newUserFormDivClass = newUserFormDivClass.replace("user-" +  userNumber, );
                var createdExistingInterfaceImgDiv = getExistingInterfaceDiv(interfaceName, path);

                var modalData = [];
                modalData = modalData.concat($modalForm.find(":input").serializeArray());

                $.post($modalForm.attr("action"), modalData, function (modalResult) {
                    $modal.modal("hide");

                    $newInterfaceImgDiv.before(createdExistingInterfaceImgDiv);
                    if ($form.find("div.interface-subform").length > 0) {
                        $form.find("div.interface-subform").last().after("<div class='" + newInterfaceFormDivClass + "'>" + modalResult + "</div>");
                    }
                    else {
                        $form.find("input").last().after("<div class='hidden-form'><div class='" + newInterfaceFormDivClass + "'>" + modalResult + "</div></div>");
                    }
                });
            });
        });
    });
});


function getExistingInterfaceDiv(interfaceName, path) {
    // TODO: need to gat dynamic user class - user names are not numbers
    var existingInterfaceDivClass = "interfaces-interface existing-interface interface-" + interfaceName + " link-active";
    var existingInterfaceDiv = "<div class='" + existingInterfaceDivClass + "' data-path='" + path + "'>";
    existingInterfaceDiv += "<span class='interface-name'>" + interfaceName + "</span>";
    existingInterfaceDiv += "<br><i class='fa fa-wifi fa-3x' aria-hidden='true'></i>";
    existingInterfaceDiv += "</div>";
    return existingInterfaceDiv;
}
