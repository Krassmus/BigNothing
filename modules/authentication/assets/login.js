jQuery(function () {
    jQuery("#content").delay(300).fadeIn(300, function () { jQuery(this).find("#login").focus(); });
    jQuery("#loginform").on("submit", function () {
        console.log(jQuery("#passphrase").val());
        jQuery("#passphrase").val("");
    });
    jQuery("#loginform").on("submit", function () {
        jQuery("#loginform").addClass("waiting");
        jQuery.ajax({
            "url": BN.URL + "authentication/login/authenticate",
            "data": {
                "login" : jQuery("#login").val(),
                "password": jQuery("#password").val()
            },
            "type": "post",
            "dataType": "json",
            "success": function (output) {
                jQuery("#loginform").removeClass("waiting");
                if (output.redirect) {
                    location.href = output.redirect;
                }
            }
        });
        return false;
    });
    jQuery("#register_data input[name=login]").on("change", function () {
        jQuery.ajax({
            "url": BN.URL + "authentication/register/username_exists",
            "data": {
                "login": jQuery("#register_data input[name=login]").val()
            },
            "dataType": "json",
            "success": function (output) {
                if (output.exists) {
                    jQuery("#register_data input[name=login]").addClass("taken").removeClass("free");
                } else {
                    jQuery("#register_data input[name=login]").removeClass("taken").addClass("free");
                }
            }
        });
    });
});