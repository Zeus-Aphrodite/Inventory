$(document).ready(function () {
    var isCreate = false;
    var userId = 0;
    var deleteGoodsForUser = {};
    var searchUrlValue = $("#searchGoodsForUsersButton").attr("href");

    // store the goods for the every clients.
    $("#storeGoodsButton").click(function (event) {
        event.preventDefault();
        var errors = validateForm();
        if (Object.keys(errors).length > 0) {
            validationHandle(errors);
        } else {
            if (isCreate) {
                $.ajax({
                    url: `/goods/${goodsId}`,
                    method: "PUT",
                    data: $("#myForm").serialize(),
                    headers: {
                        "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr(
                            "content"
                        ),
                    },
                    success: function (res, status) {
                        console.log(res);
                        $("#goodsStoreModalCancelButton").click();
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.log("error");
                    },
                });
            } else {
                $.ajax({
                    url: "/goods",
                    method: "POST",
                    data: $("#goodsForm").serialize(),
                    headers: {
                        "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr(
                            "content"
                        ),
                    },
                    success: function (res, status) {
                        console.log(res);
                        $("#goodsStoreModalCancelButton").click();
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.log("error");
                    },
                });
            }
        }
    });

    //delete goods for the every clients.

    $("#deleteGoodsForUserButtonConfirm").click(function () {
        $.ajax({
            url: `/goods/${deleteGoodsForUser}`,
            method: "DELETE",
            data: {
                goodsIdValue: deleteGoodsForUser["goodsIdValue"],
                userIdValue: deleteGoodsForUser["userIdValue"],
            },
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
            },
            success: function (response) {
                $("#deleteGoodsForUserButtonCancel").click();
                location.reload();
            },
            error: function (xhr, status, error) {
                console.log("error");
            },
        });
    });

    $(".deleteGoodsForUserButton").click(function (event) {
        const goodsIdValue = this.getAttribute("goodsIdValue");
        const userIdValue = this.getAttribute("userIdValue");
        deleteGoodsForUser["goodsIdValue"] = goodsIdValue;
        deleteGoodsForUser["userIdValue"] = userIdValue;
    });
    $("#search_users_field").on("input", function () {
        var value = $(this).val().toLowerCase();
        $("#searchUsersButton").attr("href", "/goods" + "/?value=" + value);
    });
    $("#searchGoodsField").on("input", function () {
        var value = $(this).val().toLowerCase();
        $("#searchGoodsForUsersButton").attr(
            "href",
            searchUrlValue + "/?value=" + value
        );
    });
    $("#selectUserField").change(function () {
        var selectedOption = $(this).find(":selected");
        var userIds = selectedOption.attr("userIds");
        let userLink = `/goods/${userIds}`;
        $("#selectUserLink").attr("href", userLink);
        var event = new MouseEvent("click", {
            bubbles: true,
            cancelable: true,
            view: window,
        });
        document.getElementById("selectUserLink").dispatchEvent(event);
    });
    $("#tmpGoodsUploadButton").click(function () {
        userId = $(this).attr("userId");
        $("#goodsFormFileUpload").click();
    });
    $("#goodsFormFileUpload").change(function () {
        var form = new FormData();
        var files = $("#goodsFormFileUpload")[0].files;
        if (files.length > 0) {
            form.append("file", files[0]);
            $.ajax({
                url: `/goods/upload/${userId}`,
                method: "POST",
                data: form,
                contentType: false,
                processData: false,
                headers: {
                    "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr(
                        "content"
                    ),
                },
                success: function (response) {
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.log("error");
                },
            });
        }
    });

    function validateForm() {
        // Get input field values
        var manageId = $("#manageId").val();
        var goodsTitle = $("#goodsTitle").val();

        var errors = {};

        if (manageId.trim() === "") {
            errors.manageId = "管理IDの入力は必須です。";
        }
        if (goodsTitle.trim() === "") {
            errors.goodsTitle = "ブックタイトルの入力は必須です。";
        }
        return errors;
    }

    function validationHandle(errors) {
        for (var fieldName in errors) {
            if (errors.hasOwnProperty(fieldName)) {
                var errorMessage = errors[fieldName];
                var errorElement = $("#" + fieldName + "_Error");
                errorElement.text(errorMessage);
                errorElement.show();
            }
        }
        setTimeout(function () {
            for (var fieldName in errors) {
                if (errors.hasOwnProperty(fieldName)) {
                    var errorElement = $("#" + fieldName + "_Error");
                    errorElement.hide();
                }
            }
        }, 5000);
    }

    // Handle form submission
});
