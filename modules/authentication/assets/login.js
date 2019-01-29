jQuery(function () {
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
});