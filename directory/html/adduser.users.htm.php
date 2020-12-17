<?php
$_users = new users();
?>

<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
    <form class="form-horizontal" id="editUser" role="form" method="post" action="../_lib/action.php?action=<?php echo $_users->encode('addUser'); ?>">
        
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">
                    ADD USER:    
                    <a href="javascript:void(0);" onclick="history.back();" class="pull-right"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>
                </div>
            </div>

            <div class="row" style="font-size:12px;">

                <div class="form-group">
                    <div class="col-sm-5 col-sm-offset-1">
                        <label for="first_name" class="control-label">First Name</label>
                        <input type="input" name="first_name" class="form-control input-sm" id="first_name" required>
                    </div>
                    <div class="col-sm-5">
                        <label for="last_name" class="control-label">Last Name</label>
                        <input type="input" name="last_name" class="form-control input-sm" id="last_name" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-5 col-sm-offset-1" id="emailDiv">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" name="email" class="form-control input-sm" id="email" required>  
                        <span id="emailIcon"></span>
                        <div id="error"></div>
                    </div>
                    <div class="col-sm-5">
<!--                        <label for="password" class="control-label">Password</label>
                        <div><a class="btn btn-sm btn-warning">Reset Password</a></div>-->
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-5 col-sm-offset-1">
                        <label class="control-label" for="status">Status</label>
                        <select name="status" id="status" class="form-control input-sm">
                            <option value="ACTIVE">ACTIVE</option>
                            <option value="SUSPENDED">SUSPENDED</option>
                        </select>
                    </div>

                    <div class="col-sm-5">
                        <label class="control-label" for="type">User Privs</label>
                        <select name="type" id="type" class="form-control input-sm">
                            <option value="USER">USER</option>
                            <option value="ADMIN">ADMIN</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-12">&nbsp;</div>

                <div class="col-sm-10 col-sm-offset-1 text-center">
                    <button type="submit" class="btn btn-danger">Add User</button>
                </div>

                <div class="col-sm-12">&nbsp;</div>

            </div><!--end row-->
        </div><!--panel end-->

    </form>
</div>

<script src="../js/validateEmail.js"></script>
<script>
    $(function () {

        $("#first_name").keyup(function () {
            var userFirstName = $("#first_name").val();
            $("#first_name").val(ucwords(userFirstName));
        });

        $("#last_name").keyup(function () {
            var userLastName = $("#last_name").val();
            $("#last_name").val(ucwords(userLastName));
        });

    });
</script>