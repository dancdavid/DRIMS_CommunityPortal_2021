<?php
$_core = new core();
$_agn = new agency($_core->decode($_core->gpGet('id')));
$_contactType = new ContactType();

$agency_name = $_agn->get_agency_name($_core->decode($_core->gpGet('id')));
?>

<div class="col-sm-12 col-xs-12">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">
                ADD <?php echo strtoupper($agency_name); ?> CONTACTS
                <?php
                if (isset($_GET['err'])) {
                    echo "<font class='text-danger'><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> {$_GET['err']}</font>";
                }
                ?>
            </h3>
        </div>

        <table class="table">
            <form class="form-horizontal" enctype="multipart/form-data" id="addAgencyContact" role="form" method="post" action="../_lib/action.php?action=<?php echo $_core->encode('addAgencyContact_s'); ?>">
                <input type="hidden" name="agency_id" value="<?php echo $_core->gpGet('id'); ?>">
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-6  col-xs-6">
                                <label for="fname" class="control-label">First Name <span class="text-danger">*</span></label>
                                <input type="input" name="first_name" class="form-control" id="first_name" required>
                            </div>
                            <div class="col-sm-6  col-xs-6">
                                <label for="lname" class="control-label">Last Name <span class="text-danger">*</span></label>
                                <input type="input" name="last_name" class="form-control" id="last_name" required>
                            </div>
                        </div>

                    </td>
                </tr>

                <tr>
                    <td>

                        <div class="form-group">
                            <div class="col-sm-4 col-xs-4" id="emailDiv">
                                <label class="control-label" for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" id="contact_email">
                                <span id="emailIcon"></span>
                                <div id="error"></div>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="phone">Phone <span class="text-danger">*</span></label>
                                <input type="input" name="phone" class="form-control" id="contact_telephone" required>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label for="cell" class="control-label">Cell</label>
                                <input type="input" name="alt_phone" class="form-control" id="contact_cellphone">
                            </div>

                        </div>

                    </td></tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="contact type">Contact Type <span class="text-danger">*</span></label>
                                <select name="contact_type[]" id="contact_type" class="form-control" multiple required>
                                    <?= $_contactType->BuildContactDropDown() ?>
                                </select>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="user type">User Type <span class="text-danger">*</span></label>
                                <select name="community_portal_user_type" id="user_type" class="form-control" required>
                                    <option value="">Select User Type</option>
                                    <?= $_contactType->buildDropMenu('cp_user_type', 'ADMIN') ?>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>

                        
                        <div class="col-sm-12 col-xs-12">
                            <button type="submit" class="btn btn-danger pull-right">ADD CONTACT <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></button>
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
<script src="../js/maskedinput.js"></script>
<script src="../js/validateEmail.js"></script>
<script>
    $(function () {
        $("#contact_type").select2({
            theme: "bootstrap"
        });

        $("#contact_telephone").mask("999-999-9999", {placeholder: " "});
        $("#contact_cellphone").mask("999-999-9999", {placeholder: " "});

        $("#contact_name").keyup(function () {
            var contactName = $("#contact_name").val();
            $("#contact_name").val(ucwords(contactName));
        });

    });
</script>