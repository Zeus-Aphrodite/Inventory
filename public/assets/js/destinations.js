$(document).ready(function () {
    var isNew = false;
    var destinationMemberId = 0;
    var destinationId = null;
    var destinationUrl = $("#searchDestinationByName").attr("href");
    var deleteDestinationId = null;

    $("#addDestinationButton").click(function () {
        // $("#clientId").val("");
        $("#destinationName").val("");
        $("#destinationBuildingName").val("");
        $("#streetAddressName").val("");
        $("#locationName").val("");
        $("#post_code_prefix").val("");
        $("#post_code_suffix").val("");

        $("#clientId_Error").text("");
        $("#destinationName_Error").text("");
        $("#streetAddressName_Error").text("");
        $("#locationName_Error").text("");
        $("#post_code_suffix_Error").text("");
        isNew = false;
    });
    $("#addAndEditDestinationConfirmButton").click(function () {
        var errors = validateForm();
        if (Object.keys(errors).length > 0) {
            validationHandle(errors);
        } else {
            if (isNew) {
                $.ajax({
                    url: `/destination/${destinationId}`,
                    method: "PUT",
                    data: $("#newDestinationForm").serialize(),
                    headers: {
                        "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr(
                            "content"
                        ),
                    },
                    success: function (res, status) {
                        console.log(res);
                        $("#cancelAddDestinationButton").click();
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.log("error");
                    },
                });
            } else {
                $.ajax({
                    url: "/destination",
                    method: "POST",
                    data: $("#newDestinationForm").serialize(),
                    headers: {
                        "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr(
                            "content"
                        ),
                    },
                    success: function (res, status) {
                        var event = new MouseEvent("click", {
                            bubbles: true,
                            cancelable: true,
                            view: window,
                        });
                        document
                            .getElementById("cancelAddDestinationButton")
                            .dispatchEvent(event);
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.log("error");
                    },
                });
            }
        }
    });
    $(".editDestinationButton").click(function (event) {
        const id = this.getAttribute("memberId");
        const indexNum = this.getAttribute("indexNum");
        isNew = true;
        destinationMemberId = id;
        destinationId = indexNum;

        // $("#company_name").val($("#company_name_" + id).text());
        $("#clientId").val(id);
        $("#destinationName").val($("#destinationName_" + indexNum).val());
        $("#destinationPhoneNumber").val(
            $("#destinationPhoneNumber_" + indexNum).text()
        );
        $("#locationName").val($("#destinationLocation_" + indexNum).val());
        $("#streetAddressName").val(
            $("#destinationStreetAdress_" + indexNum).val()
        );
        $("#destinationBuildingName").val(
            $("#destinationBuildingName_" + indexNum).val()
        );
        $("#post_code_prefix").val(
            $("#destinationPostCode_" + indexNum)
                .text()
                .slice(0, 3)
        );
        $("#post_code_suffix").val(
            $("#destinationPostCode_" + indexNum)
                .text()
                .slice(4, 8)
        );
    });
    $("#selectUserFieldForDestinations").change(function () {
        var selectedOption = $(this).find(":selected");
        var userIds = selectedOption.attr("userIds");
        let userLink = `/destination/${userIds}`;
        $("#selectUserLinkForDestinations").attr("href", userLink);
        console.log(userLink);
        var event = new MouseEvent("click", {
            bubbles: true,
            cancelable: true,
            view: window,
        });
        document
            .getElementById("selectUserLinkForDestinations")
            .dispatchEvent(event);
    });
    $("#searchDestinationName").on("input", function () {
        var value = $(this).val().toLowerCase();
        $("#searchDestinationByName").attr(
            "href",
            destinationUrl + "?value=" + value
        );
    });
    $(".deleteDestinationButton").click(function () {
        const id = $(this).attr("indexNum");
        deleteDestinationId = id;
    });
    $("#destinationDeleteButtonConfirm").click(function () {
        $.ajax({
            url: `/destination/${deleteDestinationId}`,
            method: "DELETE",
            data: { id: deleteDestinationId },
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
            },
            success: function (response) {
                $("#destinationDeleteButtonCancel").click();
                location.reload();
            },
            error: function (xhr, status, error) {
                console.log("error");
            },
        });
    });
    $("#showRowNumber").change(function () {
        var selectedOption = $(this).find(":selected");
        var rowNumber = selectedOption.val();
        $.ajax({
            url: `/destination/changeRowNumber/${rowNumber}`,
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
            },
            success: function (res, status) {
                location.reload();
            },
            error: function (xhr, status, error) {
                console.log("error");
            },
        });
    });

    function validateForm() {
        // Get input field values
        var clientId = $("#clientId").val();
        var destinationName = $("#destinationName").val();
        var locationName = $("#locationName").val();
        var streetAddressName = $("#streetAddressName").val();
        var post_code_suffix = $("#post_code_suffix").val();

        // Initialize an errors object
        var errors = {};

        if (clientId.trim() === "") {
            errors.clientId = "管理IDは必須です。";
        }
        if (destinationName.trim() === "") {
            errors.destinationName = "配送先の名前は必須です。";
        }
        if (post_code_suffix.trim() === "") {
            errors.post_code_suffix = "郵便番号のエントリは必須です。";
        } else if (post_code_suffix.length !== 4) {
            errors.post_code_suffix = "郵便番号を正確に入力してください。";
        }
        if (streetAddressName.trim() === "") {
            errors.streetAddressName = "番地項目フィールドは必須です。";
        }
        if (locationName.trim() === "") {
            errors.locationName = "住所項目フィールドは必須です。";
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
});
