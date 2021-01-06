$(function () {
    if (sti === 'Saved') {
        $("#bgSaved").show();
        $("#bgSaved").fadeOut(5000);
    } else {
        $("#bgSaved").hide();
    }
 });

function ucwords (str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function($1) {
        return $1.toUpperCase();
    });
}

function strtolower (str) {
    return(str + '').toLowerCase();
}

$(document).ready(function(){
    $('body').on('click','.access-login', function(){
        var oid = $(this).attr('attr-oid');
        var redirect_url = $(this).attr('redirect-url');
        if (confirm("You are currently leaving this organization space, do you wish to continue?") == true) {
            $.ajax({
                url: '../_lib/ajax.php?action=changeOrg&oid='+oid,
                type: 'POST',
                async: false,
                data: { },
                success: function(data){
                    console.log(data)
                },
                failure: function(res){
                    console.log(res);
                }
            });
            
            window.location.href = redirect_url;
        }
       
    });

    // success message hide
    $(".alert-success").fadeTo(2000, 500).slideUp(500, function(){
        $(".alert-success").slideUp(500);
    });
    // failure message hide
    $(".alert-error").fadeTo(5000, 500).slideUp(500, function(){
        $(".alert-error").slideUp(500);
    });

    // search org by name 
    $("#agency_name").on('keyup', function(event) {
		if ($("#agency_name").val() !== '')
		{
			$.ajax({
				method: 'POST',
				url: '../_lib/ajax.php?action=getOrgSearchList',
				data: $("#agency_name").serialize(),
				success: function (result) {
                    $('#search_results').show();
					$('#search_results').html(result);
				}
			});
		} else {
			$('#search_results').html(' ');
		}
    });


    $('input').focus(function() {
        $('#search_results').hide();
    });

});