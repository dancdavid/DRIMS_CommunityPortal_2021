//$(document).ready(function(){
    var instance_url = $('#landing_page').val();
    var default_url = 'mycommunitycares';
    if(instance_url != default_url){
        /*$.ajax({
            url: '../_lib/action.php?action=checkInstance&instance='+instance_url,
            type: 'GET',
            success: function (data) {
                if(data == 0){
                    // if instance do not exist then redirect to default page
                    window.location.href = '//'+window.location.hostname+'/'+default_url;
                }
            }
        });*/
    }

//});
