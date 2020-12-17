<?php
$_level = new Level1();
$_users = new users();
$f = $_users->GetMyProfile();

$notificationChk = ($f['cp_notification'] === 'YES') ? 'checked' : '';
?>


<div class="col-sm-12 col-xs-12">
    <form class="form-horizontal" id="editUser" role="form" method="post" action="../_lib/action.php?action=<?php echo $_users->encode('UpdateMyProfile'); ?>">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <div class="panel-title">
                    EDITING: MY PROFILE
                    <?php
                    if (isset($_GET['e'])) {
                        echo '<span class="text-success glyphicon glyphicon-arrow-right" style="padding-left:20px;"> ' . strtoupper($_GET['e']) . '</span>';
                    }
                    ?>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalUpdatePassword">Update Password</button>
                    <span class="text-primary"><a href="javascript:void(0);" onclick="history.back();" class="pull-right"><span class="glyphicon glyphicon-arrow-left"></span> Back</a></span>
                </div>
            </div>

            <div class="row" style="font-size:12px;">

                <div class="form-group">
                    <div class="col-sm-5 col-sm-offset-1">
                        <label for="first_name" class="control-label">Name</label>
                        <input type="input" name="first_name" class="form-control " id="contact_name" value="<?php echo $f['first_name']; ?>" required>
                    </div>
                    <div class="col-sm-5">
                        <label for="last_name" class="control-label">Last Name</label>
                        <input type="input" name="last_name" class="form-control " id="last_name" value="<?php echo $f['last_name']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-5 col-sm-offset-1">
                        <label class="control-label" for="phone">Phone <span class="text-danger">*</span></label>
                        <input type="input" name="phone" class="form-control" id="contact_telephone" value="<?= $f['phone'] ?>" required>
                    </div>
                    <div class="col-sm-5">
                        <label for="cell" class="control-label">Cell</label>
                        <input type="input" name="alt_phone" class="form-control" id="contact_cellphone" value="<?= $f['alt_phone'] ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-5 col-sm-offset-1" id="emailDiv">

                        <label for="level1" class="control-label"><?= $_SESSION['Level1_Label'] ?></label>
                        <select name="level_1[]" class="form-control" id="level1" multiple required>
                            <?= $_level->BuildLevelDropDown($f['level_1']) ?>
                        </select>
                    </div>
                    <div class="col-sm-2"></div>
                    <div class="col-sm-5">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" class="form-control " id="email" value="<?php echo $f['email']; ?>" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-2 col-md-offset-1">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="cp_notification" value="YES" <?= $notificationChk ?>> <b>Notifications On</b>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2 col-md-offset-6">
<!--                        <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalUpdatePassword">Update Password</button>-->
                    </div>
                </div>


                <div class="col-sm-12">&nbsp;</div>

                <div class="col-sm-10 col-sm-offset-1 text-center">
                    <button type="submit" class="btn btn-danger">EDIT <?php echo $f['first_name'] . ' ' . $f['last_name']; ?></button>
                </div>

                <div class="col-sm-12">&nbsp;</div>

            </div><!--end row-->
        </div><!--panel end-->

    </form>
</div>

<div class="modal fade" id="modalUpdatePassword" tabindex="-1" role="dialog" aria-labelledby="myModalPWDLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalPWDLabel">Update Password</h4>
            </div>
            <form class="form-horizontal" id="editPassword" role="form" method="post" action="../_lib/action.php?action=<?= $_level->encode('UpdatePassword'); ?>" >
                <div class="modal-body">
                    <div class="form-group" id="addError">
                        <div class="form-row">
                            <div class="col-md-8 col-md-offset-2">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" minlength="8" required>
                                <small id="passwordHelpline" class="text-muted">
                                    Use 8 or more characters with a mix of letters, numbers & symbols
                                </small>
                            </div>
                        </div>
                        <div class="form-row" style="padding-top:8rem;">
                            <div class="col-md-8 col-md-offset-2">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                                <span id="confirmPasswordError" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="../js/jquery-input-mask-phone-number.min.js"></script>
<script>
    $(function () {

        $('#contact_telephone, #contact_cellphone').on('input', function (event) {
            this.value = this.value.replace(/[^0-9-]/g, '');
        });

        $("#contact_telephone").usPhoneFormat();
        $("#contact_cellphone").usPhoneFormat();

        $("#level1").select2({
            theme:"bootstrap"
        });

        (function() {
            "use strict";
            //alert('run');
            //window.addEventListener("load", function() {
            //lert('loaed');
            var form = document.getElementById("editPassword");
            form.addEventListener("submit", function(event) {
                if ( document.getElementById("password").value !=
                    document.getElementById("confirm_password").value )
                {
                    $("#addError").addClass("has-error");
                    $("#confirmPasswordError").html("<small>Passwords Don't Match</small>");
                    event.preventDefault();
                    event.stopPropagation();
                }
                else if (form.checkValidity() == false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add("was-validated");
            }, false);
            //}, false);
        }());

        $("#confirm_password").focus(function () {
            $("#addError").removeClass("has-error");
            $("#confirm_password").removeClass("is-invalid");
            $("#confirmPasswordError").html(" ");
        });

        $("#modalUpdatePassword").on('hidden.bs.modal', function() {
            $('#password').val('');
            $('#confirm_password').val('');
            $("#addError").removeClass("has-error");
            $("#confirmPasswordError").html(" ");
        });
    });
</script>
