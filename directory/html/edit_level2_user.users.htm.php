<?php
$_core = new core();
if (!UserAccess::ManageLevel1()) {
    $_core->redir('directory');
}

$_db = new db();
$_level = new Level1();
$_user = new users();
$f = $_user->getUserData($_core->decode($_core->gpGet('uid')));

?>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="edit_level12_users">Level 1 Users</a></li>
            <li role="presentation" class="active"><a href="#">EDIT User</a></li>
            <?php
            if (UserAccess::ManageLevel2()) {
                echo '<li role="presentation"><a href="level2_users">Level 2 Users</a></li>';
            }
            ?>

        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body">

                <div class="well">
                    <?php
                    if (!empty($_GET['e']) && $_GET['e'] === 'complete')
                    {
                        echo '<span class="text-danger"><h5>UPDATED!</h5></span>';
                    }
                    ?>

                    <table width="100%" id="level2EditForm" class="table table-bordered">
                        <form class="form-horizontal" enctype="multipart/form-data" id="editLevel2User" role="form" method="post"
                              action="../_lib/agencyaction.php?action=<?= $_core->encode('EditLevel2User'); ?>">
                            <input type="hidden" name="uid" value="<?= $_core->gpGet('uid') ?>">
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <div class="col-sm-6  col-xs-6">
                                            <label for="fname" class="control-label">First Name <span class="text-danger">*</span></label>
                                            <input type="input" name="first_name" class="form-control" id="first_name" value="<?= $f['first_name'] ?>" required>
                                        </div>
                                        <div class="col-sm-6  col-xs-6">
                                            <label for="lname" class="control-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="input" name="last_name" class="form-control" id="last_name" value="<?= $f['last_name'] ?>" required>
                                        </div>
                                    </div>

                                </td>
                            </tr>

                            <tr>
                                <td>

                                    <div class="form-group">
                                        <div class="col-sm-4 col-xs-4" id="emailDiv">
                                            <label class="control-label" for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" id="contact_email" value="<?= $f['email'] ?>" >
                                            <span id="emailIcon"></span>
                                            <div id="error"></div>
                                        </div>
                                        <div class="col-sm-4 col-xs-4">
                                            <label class="control-label" for="phone">Phone <span class="text-danger">*</span></label>
                                            <input type="input" name="phone" class="form-control" id="contact_telephone" value="<?= $f['phone'] ?>" required>
                                        </div>
                                        <div class="col-sm-4 col-xs-4">
                                            <label for="cell" class="control-label">Cell</label>
                                            <input type="input" name="alt_phone" class="form-control" id="contact_cellphone" value="<?= $f['alt_phone'] ?>" >
                                        </div>

                                    </div>

                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-group">
                                        <div class="col-sm-4 col-xs-4">
                                            <label for="level1" class="control-label">Level <span class="text-danger">*</span></label>
                                            <select id="level1" name="level_1[]" class="form-control" style="width: 100%" multiple required>
                                                <?= $_level->BuildLevelDropDown($f['level_1']) ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-4 col-xs-4">
                                            <label for="status" class="control-label">Status <span class="text-danger">*</span></label>
                                            <select id="status" name="status" class="form-control" required>
                                                <?= $_db->buildDropMenu('cp_user_status',$f['status']) ?>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="col-sm-2 col-xs-2 col-sm-offset-10 col-xs-offset-10">
                                        <button type="submit" class="btn btn-danger pull-right">EDIT USER</button>
                                    </div>
                                </td>
                            </tr>

                        </form>
                    </table>
                </div>


            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="../js/jquery-input-mask-phone-number.min.js"></script>
<script src="../js/validateEmail.js"></script>
<script>
    $(function () {

        $("#level1").select2({
            theme: "bootstrap"
        });

        $("#contact_telephone").usPhoneFormat();
        $("#contact_cellphone").usPhoneFormat();

        $("#first_name").keyup(function () {
            var firstName = $("#first_name").val();
            $("#first_name").val(ucwords(firstName));
        });

        $("#last_name").keyup(function () {
            var lastName = $("#last_name").val();
            $("#last_name").val(ucwords(lastName));
        });


    });
</script>

