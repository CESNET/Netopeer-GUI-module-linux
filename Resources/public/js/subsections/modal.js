
function createModalEditItemForm($form, $icon, path, itemXpath) {
    var data = [];
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
                $form.replaceWith(modalResult);
            });
        });

        $(".modal .modal-content").on("click", "button.remove-item", function (e) {
            e.preventDefault();

            var confirmResult = confirm("Are you sure you want to delete this node. This change cannot be undone");
            if (confirmResult) {
                var $modalForm = $(".modal .modal-content").find("form").first();
             /*   var modalData = [];
                modalData = modalData.concat($modalForm.find(":input").serializeArray());

                var modalDataString = JSON.stringify(modalData);
                modalDataString = modalDataString.replace(new RegExp("configDataForm", "g"), "removeNodeForm");
                modalData = JSON.parse(modalDataString);*/

              //  $.post($modalForm.attr("data-path-remove"), {"removeNodeForm[parent]": itemXpath}, function (modalResult) {
                    $modal.modal("hide");
                    $form.remove();
                    $icon.remove();
                    // TODO: without reload, can't remove two servers
                 //   window.location = window.location.href;
                    //window.location.reload(false);
              //  });
            }
        });
    });
}

function createModalNewItemForm($form, data, path, newItemFormDivClass, $newItemImgDiv, createdExistingItemImgDiv) {
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

                $newItemImgDiv.before(createdExistingItemImgDiv);
                if ($form.find("div.server-subform").length > 0) {
                    $form.find("div.server-subform").last().after("<div class='" + newItemFormDivClass + "'>" + modalResult + "</div>");
                }
                else {
                    $form.find("input").last().after("<div class='" + newItemFormDivClass + "'>" + modalResult + "</div>");
                }
            });
        });
    });

}
