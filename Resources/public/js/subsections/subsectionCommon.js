$(document).ready(function() {
    var formSubmitting = false;
    var setFormSubmitting = function() { formSubmitting = true; };

    $('form').data('serialize', $('form').serialize());

    $('form').on('submit', function(e) {
        setFormSubmitting();
        e.stopPropagation()
    });

    $(window).bind('beforeunload', function (e) {
        var serializedForm = $('form').serialize();
        var serializedData = $('form').data('serialize');
        if ((serializedData != null) && (serializedForm != null) && (serializedForm != serializedData) && !formSubmitting) {
            return "You are leaving page with unsaved changes. The changes will be lost. Do you want to continue?";
        } else {
            e = null;
        }
    });

    $("#block-linux-body").on("click", ".back-to-system-section", function() {
        var path = $(this).attr("data-path");
        window.location.href = path;
    });   
    
    $("#block-linux-body").on("click", ".save-subsection", function(e){
        e.preventDefault();

        var $form = $("#block-linux-body-content").find("form");
        var formValid = true;
        var $inputs = $form.find("input");
        $inputs.each(function() {
            if ($(this).closest(".input-group").hasClass("required") && ($(this).val() == null || $(this).val() == "")) {
                formValid = false;
            }
        });

        if (!formValid) {
            var confirmResult = confirm("All mandatory fields aren't filled. It may cause not valid configuration. Do you want to continue anyway?");
            if (!confirmResult) {
                return;
            }
        }

        $form.submit();

        return false;
        // -----------------------------------------------
       // $("#block-linux-body-content").find("form").submit();
    });

    $("#block-linux-body").on("click", ".arrow-navigation", function(){
        var path = $(this).attr("data-path");
        window.location.href = path;
    });

    $('body').on('click', ".fa-plus", function() {
        var count = $(this).closest(".input-group").find(".removable-item").length;
        var $divToClone = $(this).siblings(".removable-item").last();
        var $clonedDiv = $divToClone.clone();
        var $input = $clonedDiv.children("select,input");
        var inputName = $input.attr("name");
        inputName = inputName.replace((count - 1), (count));
        $input.attr("name", inputName);
        $input.val("");
        $clonedDiv.insertAfter($divToClone);
    });

    $('body').on("click", ".fa-remove", function () {
        var confirmResult = confirm("Are you sure you want to delete this item.");
        if (confirmResult) {
            var $divToRemove = $(this).closest(".removable-item");
            var $input = $(this).siblings('select,input');
            var inputName = $input.attr('name');
          //  var inputXpath = inputName.split("_")[1];
          //  inputXpath = inputXpath.replace("system", "*").replace("]", "");

          //  $.post($input.attr("data-path-remove"), {"removeNodeForm[parent]": inputXpath}, function () {
                $divToRemove.remove();
           //     window.location = window.location.href;
          //  });
        }
    });
});


