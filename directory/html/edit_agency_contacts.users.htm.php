<?php
$_core = new core();
$_contactType = new ContactType();
$_contactLicenseType = new ContactLicenseType();
$_level = new Level1();

$agency_id = $_core->decode($_core->gpGet('id'));
$_agency = new agency($agency_id);
$contact_id = $_core->decode($_core->gpGet('uid'));
$f = $_agency->edit_agency_contact($contact_id);
?>
<div class="col-sm-12 col-xs-12">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title"><span class="text-primary"><?php echo strtoupper($_agency->get_agency_name($agency_id)); ?> </span> <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> EDIT CONTACTS
                <span class="pull-right"><a href="#" onclick="window.history.go(-1); return false;" class="text-primary"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Org Summary</a></span>
            </h3>
        </div>
        <table class="table">
            <form class="form-horizontal" enctype="multipart/form-data" id="addAgencyContact" role="form" method="post" action="../_lib/agencyaction.php?action=<?php echo $_core->encode('EditAgencyContact'); ?>">
                <input type="hidden" name="uid" value="<?php echo $_core->gpGet('uid'); ?>">
                <input type="hidden" name="aid" value="<?php echo $_core->gpGet('id'); ?>">
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-4  col-xs-4">
                                <label for="fname" class="control-label">First Name <span class="text-danger">*</span></label>
                                <input type="input" name="first_name" class="form-control" id="first_name" value="<?= $f['first_name'] ?>" required>
                            </div>
                            <div class="col-sm-4  col-xs-4">
                                <label for="lname" class="control-label">Last Name <span class="text-danger">*</span></label>
                                <input type="input" name="last_name" class="form-control" id="last_name" value="<?= $f['last_name'] ?>" required>
                            </div>
                            <div class="col-sm-4 col-xs-4" id="emailDiv">
                                <label class="control-label" for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" id="contact_email"  value="<?= $f['email'] ?>" required>
                                <span id="emailIcon"></span>
                                <div id="error"></div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>

                        <div class="form-group">
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="title">Title <span class="text-danger">*</span></label>
                                <input type="input" name="title" class="form-control" id="title" value="<?= $f['title'] ?>" required>
                            </div>
                            <div class="col-sm-3 col-xs-3">
                                <label class="control-label" for="phone">Phone</label>
                                <input type="input" name="phone" class="form-control" id="contact_telephone" value="<?= $f['phone'] ?>">
                            </div>
                            <div class="col-sm-1 col-xs-1">
                                <label class="control-label" for="ext">Ext</label>
                                <input type="text" name="extension" class="form-control" id="extension" value="<?= $f['extension'] ?>">
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label for="cell" class="control-label">Cell</label>
                                <input type="input" name="alt_phone" class="form-control" id="contact_cellphone" value="<?= $f['alt_phone'] ?>">
                            </div>

                        </div>

                    </td></tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="contact type">Contact Type <span class="text-danger">*</span></label>
                                <select name="contact_type[]" id="contact_type" class="form-control" multiple required>
                                    <?= $_contactType->BuildContactDropDown($f['contact_type']) ?>
                                </select>
                            </div>

                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="contact license type">Contact License Type</label>
                                <select name="contact_license_type[]" id="contact_license_type" class="form-control" multiple>
                                    <?= $_contactLicenseType->BuildContactLicenseDropDown($f['contact_license_type']) ?>
                                </select>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="level1"><?= $_SESSION['Level1_Label'] ?>  <span class="text-danger">*</span></label>
                                <select name="level_1[]" id="level_1" class="form-control" multiple required>
                                    <?= $_level->BuildLevelDropDown($f['level_1']) ?>
                                </select>
                            </div>
                        </div>

                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="user type">User Type <span class="text-danger">*</span></label>
                                <select name="community_portal_user_type" id="user_type" class="form-control" required>
                                    <?= $_contactType->buildDropMenu('cp_user_type', $f['community_portal_user_type']) ?>
                                </select>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="status">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control" required>
                                    <?= $_contactType->buildDropMenu('cp_user_status', $f['status']) ?>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>


                        <div class="col-sm-12 col-xs-12">
                            <button type="submit" class="btn btn-danger pull-right">EDIT CONTACT <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                        </div>

                    </td>
                </tr>

            </form>

        </table>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="../js/jquery-input-mask-phone-number.min.js"></script>
<script src="../js/validateEmail.js"></script>
<script>
    $(function () {

        $("#contact_type").select2({
            theme: "bootstrap"
        });

        $("#contact_license_type").select2({
            theme: "bootstrap"
        });

        $("#level_1").select2({
            theme: "bootstrap"
        });

        $('#contact_telephone, #contact_cellphone, #extension').on('input', function (event) {
            this.value = this.value.replace(/[^0-9-]/g, '');
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

        $("#title").keyup(function () {
            var title = $("#title").val();
            $("#title").val(ucwords(title));
        });

    });
</script>