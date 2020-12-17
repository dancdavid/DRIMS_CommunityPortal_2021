$(function() {
    $('#password').blur(function(e) {
        var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
        var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
        var enoughRegex = new RegExp("(?=.{8,}).*", "g");
        if (false == enoughRegex.test($(this).val())) {
            $('#passstrength').addClass('text-danger');
            $('#passstrength').html('<b>Recommend Password to be at least 8 characters<b>');
        } else if (strongRegex.test($(this).val())) {
            $('#passstrength').addClass('text-danger');
            $('#passstrength').html('<b>Password Strength STRONG!</b>');
        } else if (mediumRegex.test($(this).val())) {
            $('#passstrength').addClass('text-danger');
            $('#passstrength').html('<b>Password Strength MEDIUM!</b>');
        } else {
            $('#passstrength').addClass('text-danger');
            $('#passstrength').html('<b>Password Strength WEAK!</b>');
        }
        return true;
    });
});

