$(function () {

    $("#contact_email").blur(function () {
        var email = $("#contact_email").val();
        var agency_id = $("#current_agency_id").val();
        var organization_id = $("#organization_id").val();

        if (email.length > 2) {
            $.ajax({
                cache: "false",
                url: "../_lib/ajax.php?action=checkEmail&email=" + email + '&agency_id=' + agency_id + '&organization_id=' + organization_id,
                success: function (response) {
                    if (response == "fail") {
                        $("#emailDiv").addClass("has-error has-feedback");
                        $("#emailIcon").addClass("glyphicon glyphicon-remove form-control-feedback");
                        $("#error").addClass("text-danger");
                        $("#error").html("<strong>This Contact is already added! </strong>");
                        $("#submit,button").attr("disabled", true);
                        // $("input,select,#submit,button").attr("disabled", true);
                    } else {
                        $('#search_results').show();
					    $('#search_results').html(response);
                        $("#emailDiv").removeClass("has-error has-feedback");
                        $("#emailIcon").removeClass("glyphicon glyphicon-remove form-control-feedback");
                        $("#error").html("");
                        $("#emailDiv").addClass("has-success has-feedback");
                        $("#emailIcon").addClass("glyphicon glyphicon-ok form-control-feedback");
                        $("#submit,button").attr("disabled", false);
                    }
                }
            })
        }
    });

    $('input').focus(function() {
        $('#search_results').hide();
    });

});