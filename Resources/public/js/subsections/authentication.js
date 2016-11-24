$(document).ready(function () {
    $('body').on('click', ".user.existing-user", function() {
        var userName = $(this).attr("class").replace("user existing-user", "");
        userName = userName.replace("link-active", "");
        var path = $(this).attr("data-path");

        var data = [];
        var $form = $("#authentication-form").find(".user-subform[class*=" + userName + "]").first();
        data = data.concat($form.find(":input").serializeArray());
        
        var $icon = $(".authentication-config-img .users").find(".existing-user[class*=" + userName + "]").first();
        var userXpath = $form.find("input[name*=userXpath]").first().val();

        createModalEditItemForm($form, $icon, path, userXpath);
    });

    $(".user.new-user").click(function () {
        var $form = $("#authentication-form").find("form").first();
        var path = $(this).attr("data-path");
        var userNumber = $form.find("div.user-subform").length + 1;
        var data = {userNumber: userNumber};

        var $newUserImgDiv = $("#linux-body-content-authentication").find(".users .user.new-user").first();


       // createModalNewItemForm($form, data, path, newUserFormDivClass, $newUserImgDiv, createdExistingUserImgDiv);

        $.post(path, data, function (result) {
            var $modal = $(result);
            $modal.find("input[name*=name]").first().attr("readonly", false);
            $modal.find("input[name*=name]").first().closest(".input-group").removeClass("readonly");
            $modal.modal();

            $(".modal .modal-content").on("click", "button.submit", function (e) {
                e.preventDefault();
                var $modalForm = $(".modal .modal-content").find("form").first();
                var userName = $modalForm.find("input[name*=name]").first().val();
                var newUserFormDivClass = 'user-subform user-' + userName;
               // newUserFormDivClass = newUserFormDivClass.replace("user-" +  userNumber, );
                var createdExistingUserImgDiv = getExistingAuthenticationUserDiv(userName, path);

                var modalData = [];
                modalData = modalData.concat($modalForm.find(":input").serializeArray());

                $.post($modalForm.attr("action"), modalData, function (modalResult) {
                    if ($(modalResult).find("form").length > 0) {
                        $modal.find("form").html($(modalResult).find("form").html());
                        $modal.find("input[name*=name]").first().attr("readonly", false);
                        $modal.find("input[name*=name]").first().closest(".input-group").removeClass("readonly");
                    } else {
                        $modal.modal("hide");

                        $newUserImgDiv.before(createdExistingUserImgDiv);
                        if ($form.find("div.user-subform").length > 0) {
                            $form.find("div.user-subform").last().after("<div class='" + newUserFormDivClass + "'>" + modalResult + "</div>");
                        }
                        else {
                            $form.find("input").last().after("<div class='hidden-form'><div class='" + newUserFormDivClass + "'>" + modalResult + "</div></div>");
                        }
                    }
                });
            });
        });
    });

    $("body").on("click", ".change-password-button", function() {
        $(".change-password-form").show();
    });
    
});


function getExistingAuthenticationUserDiv(userName, path) {
    // TODO: need to gat dynamic user class - user names are not numbers
    var existingUserDivClass = "user existing-user user-" + userName + " link-active";
    var existingUserDiv = "<div class='" + existingUserDivClass + "' data-path='" + path + "'>";
    existingUserDiv += "<span class='user-name'>" + userName + "</span>";
    existingUserDiv += "<br><i class='fa fa-user fa-3x' aria-hidden='true'></i>";
    existingUserDiv += "</div>";
    return existingUserDiv;
}
