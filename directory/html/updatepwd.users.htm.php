<?php
$_core = new core();
?>
<div class="col-sm-6 col-xs-6 col-sm-offset-3 col-xs-offset-3">
    <div class="jumbotron">
        <h3 class="text-danger"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Update Password </h3>
        Our system shows that you are using a system default password. <br>
        Please update your password.<br><br>
        <b>Recommended</b>:<br>
        Minimum 8 characters<br>
        Use at least 1 number<br>
        Use at least 1 upper case letter<br>
        
        <form class="form-horizontal" id="editUserPassword" role="form" method="post" action="../_lib/action.php?action=<?php echo $_core->encode('editUserPassword'); ?>">

            <div class="form-group">
                <div id="pwdDiv" class="col-sm-10 col-sm-offset-1"></div>
            </div>

            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-1">
                    <label for="pwd1" class="control-label">Password</label>
                    <input type="password" name="password" class="form-control input-sm" id="password" placeholder="password" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-1">
                    <label for="pwd2" class="control-label">Confirm Password</label>
                    <input type="password" name="pwd2" class="form-control input-sm" id="pwd2" placeholder="confirm password" required>
                </div>
            </div>
            
            <div class="form-group">
                <div id="passstrength" class="col-sm-10 col-sm-offset-1"></div>
            </div>

            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-1">
                    <br><button type="submit" class="btn btn-danger pull-right">Update Password</button>
                </div>
            </div>
        </form>

    </div>    
</div>

<script type="text/javascript" src="../js/passStrength.js"></script>

<script>
    $(function () {

        $('#editUserPassword').submit(function () {
            if ($("#password").val() != '') {
                if ($("#password").val() != $("#pwd2").val()) {
                    $("#pwdDiv").html("<b class='text-danger'>Passwords do not match!</b>");
                    return false;
                }
            }  
        });

    });
</script>