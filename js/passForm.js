function adminPassword() {
    
    var postData = $("form#admin_password").serialize();
    
    $.ajax({
        type: "POST",
        url: "_lib/ajax.php?action=adminPassword",
        data: postData,
        success:function(response) {
            $("#adminSuccess").html(response);
        }
    });
}