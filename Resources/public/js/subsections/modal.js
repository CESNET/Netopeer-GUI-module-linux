
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
                if ($(modalResult).find("form").length > 0) {
                    $modal.find("form").html($(modalResult).find("form").html());
                } else {
                    $modal.modal("hide");
                    $form.replaceWith(modalResult);
                }
            });
        });

        $(".modal .modal-content").on("click", "button.remove-item", function (e) {
            e.preventDefault();

            var confirmResult = confirm("Are you sure you want to delete this node. This change cannot be undone");
            if (confirmResult) {
                $modal.modal("hide");
                $form.remove();
                $icon.remove();
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
                if ($(modalResult).find("form").length > 0) {
                    $modal.find("form").html($(modalResult).find("form").html());
                } else {
                    $modal.modal("hide");

                    $newItemImgDiv.before(createdExistingItemImgDiv);
                    if ($form.find("div.server-subform").length > 0) {
                        $form.find("div.server-subform").last().after("<div class='" + newItemFormDivClass + "'>" + modalResult + "</div>");
                    }
                    else {
                        $form.find("input").last().after("<div class='" + newItemFormDivClass + "'>" + modalResult + "</div>");
                    }
                }
            });
        });
    });
}
