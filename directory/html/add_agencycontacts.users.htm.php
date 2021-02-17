<?php
$_core = new core();
$_agn = new agency($_core->decode($_core->gpGet('id')));
$_contactType = new ContactType();
$_contactLicenseType = new ContactLicenseType();

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
            <form class="form-horizontal" enctype="multipart/form-data" id="addAgencyContact" role="form" method="post" action="../_lib/agencyaction.php?action=<?php echo $_core->encode('AddAgencyContact'); ?>">
                <input type="hidden" name="aid" id="current_agency_id" value="<?php echo $_core->gpGet('id'); ?>">
                <input type="hidden" name="oid" id="organization_id" value="<?php echo $_core->gpGet('oid'); ?>">
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-4 col-xs-4">
                                <label for="fname" class="control-label">First Name <span class="text-danger">*</span></label>
                                <input type="input" name="first_name" class="form-control" id="first_name" required>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label for="lname" class="control-label">Last Name <span class="text-danger">*</span></label>
                                <input type="input" name="last_name" class="form-control" id="last_name" required>
                            </div>
                            <div class="col-sm-4 col-xs-4" id="emailDiv">
                                <label class="control-label" for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" id="contact_email">
                                <span id="emailIcon"></span>
                                <div id="error"></div>
                                <div id="search_results"></div>
                                
                            </div>
                        </div>

                    </td>
                </tr>

                <tr>
                    <td>

                        <div class="form-group">

                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="title">Title <span class="text-danger">*</span></label>
                                <input type="input" name="title" class="form-control" id="title" required>
                            </div>
                            <div class="col-sm-3 col-xs-3">
                                <label class="control-label" for="phone">Phone</label>
                                <input type="input" name="phone" class="form-control" id="contact_telephone"">
                            </div>
                            <div class="col-sm-1 col-xs-1">
                                <label class="control-label" for="ext">Ext</label>
                                <input type="text" name="extension" class="form-control" id="extension"">
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label for="cell" class="control-label">Cell</label>
                                <input type="input" name="alt_phone" class="form-control" id="contact_cellphone"">
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
                                    <?php
                                    $setUserDefault = (!empty($_GET['stat']) && $_GET['stat'] === 'newcontact') ? 'USER' : 'ADMIN';
                                    echo $_contactType->buildDropMenu('cp_user_type', $setUserDefault)
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="contact license type">Contact License Type</label>
                                <select name="contact_license_type[]" id="contact_license_type" class="form-control" multiple>
                                    <?= $_contactLicenseType->BuildContactLicenseDropDown() ?>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="col-sm-2 col-xs-2 col-sm-offset-10 col-xs-offset-10">
                            <button type="submit" class="btn btn-success pull-right">ADD CONTACT</button>
                        </div>

                        <?php
//                        if (!empty($_GET['stat']) && $_GET['stat'] === 'newcontact')
//                        {
//                            echo '<div class="col-sm-2 col-xs-2 col-sm-offset-10 col-xs-offset-10">
//                            <button type="submit" class="btn btn-success pull-right">ADD CONTACT</button>
//                        </div>';
//                        } else {
//                            echo '
//                            <span><button type="submit" name="add_another_contact" value="yes" class="btn btn-warning">Add More Contacts <span class="glyphicon glyphicon-user" aria-hidden="true"></span></button> </span>
//                            <span><button type="submit" name="add_services" value="yes" class="btn btn-danger">Add Services </button></span>
//                            <span><button type="submit" class="btn btn-success pull-right">DONE</button></span>
//                        ';
//                        }

                        ?>

                    </td>
                </tr>

            </form>

        </table>
    </div>
</div>

<!-- modal Inactive Contact -->
<div class="modal fade" id="modalInactiveContact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalLabel">Inactive User</h4>
            </div>
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <p>
                                This email is associated to a user who is no longer active within their associated Organization or DRIMS tools. 
                                Please verify the email address is correct or add a new one to invite the user. 
                                If you feel this is an error, please contact DRIMS technical support for assistance.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="margin-top:50px">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

        $("#contact_type").select2({
            theme: "bootstrap"
        });

        $("#contact_license_type").select2({
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