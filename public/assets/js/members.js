$(document).ready(function () {
    var isCreate = false;
    var memberId = 0;
    var deleteMemberId = false;

    $("#showPassword").click(function () {
        if ($("#password").attr("type") == "password") {
            $("#password").attr("type", "text");
        } else {
            $("#password").attr("type", "password");
        }
    });
    $("#deleteConfirm").click(function () {
        $.ajax({
            url: `/members/${deleteMemberId}`,
            method: "DELETE",
            data: { id: deleteMemberId },
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
            },
            success: function (response) {
                $("#deleteCancelButton").click();
                location.reload();
            },
            error: function (xhr, status, error) {
                console.log("error");
            },
        });
    });

    $("#newMember").click(function (event) {
        isCreate = false;
        $("#company_name").val("");
        $("#name").val("");
        $("#furigana_name").val("");
        $("#email").val("");
        $("#password").val("");
        $("#phone_number").val("");
        $("#post_code_prefix").val("");
        $("#post_code_suffix").val("");
        $("#location").val("");
        $("#street_adress").val("");
        $("#building_name").val("");

        $("#company_name_Error").text("");
        $("#name_Error").text("");
        $("#furigana_name_Error").text("");
        $("#email_Error").text("");
        $("#phone_number_Error").text("");
        $("#password_Error").text("");
        $("#post_code_suffix_Error").text("");
        $("#location_Error").text("");
        $("#street_adress_Error").text("");
        $("#building_name_Error").text("");
    });
    $("#createAndEditButton").click(function (event) {
        event.preventDefault();
        var errors = validateForm();
        var errors = {};

        if (Object.keys(errors).length > 0) {
            console.log(errors);
            validationHandle(errors);
        } else {
            if (isCreate) {
                $.ajax({
                    url: `/members/${memberId}`,
                    method: "PUT",
                    data: $("#myForm").serialize(),
                    headers: {
                        "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr(
                            "content"
                        ),
                    },
                    success: function (res, status) {
                        if (status == "success" && res.message == "success") {
                            $("#cancelButton").click();
                            location.reload();
                        } else {
                            console.log("error");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("error");
                    },
                });
            } else {
                $.ajax({
                    url: "/members",
                    method: "POST",
                    data: $("#myForm").serialize(),
                    headers: {
                        "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr(
                            "content"
                        ),
                    },
                    success: function (res, status) {
                        console.log(res.users);
                        if (status == "success" && res.message == "success") {
                            $("#cancelButton").click();
                            location.reload();
                        } else {
                            console.log("error");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.log("error");
                    },
                });
            }
        }
    });
    $(".editButton").click(function (event) {
        const id = this.getAttribute("memberId");
        const member_role = this.getAttribute("member_role");
        isCreate = true;
        memberId = id;
        const post_code = this.getAttribute("post_code");
        $("#selecetPermission").val(member_role);
        $("#company_name").val($("#company_name_" + id).text());
        $("#name").val($("#name_" + id).text());
        $("#furigana_name").val($("#furigana_name_" + id).text());
        $("#email").val($("#email_" + id).text());
        // $("#password").val($("#password_" + id).text());
        $("#phone_number").val($("#phone_number_" + id).text());
        $("#post_code_prefix").val(post_code.slice(0, 3));
        $("#post_code_suffix").val(post_code.slice(4, 8));
        $("#location").val($("#location_" + id).text());
        $("#street_adress").val($("#street_adress_" + id).text());
        $("#building_name").val($("#building_name_" + id).text());
    });
    $(".deleteButton").click(function (event) {
        const id = this.getAttribute("memberId");
        deleteMemberId = id;
    });
    $("#search_field").on("input", function () {
        var value = $(this).val().toLowerCase();
        $("#searchButton").attr("href", "/members?value=" + value);
        // $("#memberTableBody tr").filter(function () {
        //     $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        // });
        // $.ajax({
        //     url: `/members`,
        //     method: "GET",
        //     data: { value: value },
        //     headers: {
        //         "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
        //     },
        //     success: function (response) {

        //     },
        //     error: function (xhr, status, error) {
        //         console.log("error");
        //     },
        // });
    });
    /* =================================================== */

    $("#userPersonalInforEditButton").click(function () {
        var userId = $("#memberIdInput").val();
        $.ajax({
            url: `/members/${userId}`,
            method: "PUT",
            data: $("#userPersonalInForForm").serialize(),
            headers: {
                "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content"),
            },
            success: function (res, status) {
                if (res.message == "success") {
                    // $("#toastmember").addClass("text-bg-success");
                    $("#memberToast").show();
                    $("#toastValueContent").text("正しく更新されました！");
                    setTimeout(function () {
                        $("#memberToast").fadeOut(1000);
                    }, 2000);
                    // location.reload();
                } else {
                    console.log("error");
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr);
            },
        });
    });

    function validateForm() {
        // Get input field values
        var companyName = $("#company_name").val();
        var name = $("#name").val();
        var furiganaName = $("#furigana_name").val();
        var phoneNumber = $("#phone_number").val();
        var post_code_suffix = $("#post_code_suffix").val();
        var location = $("#location").val();
        var streetAddress = $("#street_adress").val();
        var email = $("#email").val();
        // var password = $("#password").val();

        // Initialize an errors object
        var errors = {};

        // Check each field and add corresponding error message to the errors object if needed
        if (companyName.trim() === "") {
            errors.company_name = "法人名の入力は必須です。";
        }
        if (name.trim() === "") {
            errors.name = "担当者氏名の入力は必須項目です。";
        }
        if (furiganaName.trim() === "") {
            errors.furigana_name = "担当者のふりがなの入力は必要です。";
        }
        if (phoneNumber.trim() === "") {
            errors.phone_number = "電話番号の入力は必須です。";
        } else if (phoneNumber.length < 10 || phoneNumber.length > 11) {
            errors.phone_number = "電話番号を正確に入力してください。";
        }
        if (post_code_suffix.trim() === "") {
            errors.post_code_suffix = "郵便番号のエントリは必須です。";
        } else if (post_code_suffix.length !== 4) {
            errors.post_code_suffix = "郵便番号を正確に入力してください。";
        }
        if (location.trim() === "") {
            errors.location = "住所項目フィールドは必須です。";
        }
        if (streetAddress.trim() === "") {
            errors.street_adress = "番地項目フィールドは必須です。";
        }
        if (email.trim() === "") {
            errors.email = "電子メールフィールドは必須です。";
        } else if (
            !/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/.test(email)
        ) {
            errors.email = "有効な電子メール アドレスを入力してください。";
        }
        if (isCreate == true) {
            if (password) {
                if (password.length < 8) {
                    errors.password =
                        "パスワードは少なくとも 8 文字である必要があります。";
                }
            }
        } else {
            if (password.trim() === "") {
                errors.password = "パスワードフィールドは必須です。";
            } else if (password.length < 8) {
                errors.password =
                    "パスワードは少なくとも 8 文字である必要があります。";
            }
        }

        // Return the errors object
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
