<?php
$_core = new core();
$agency_id = $_core->decode($_core->gpGet('id'));
$_agency = new agency();
$f = $_agency->get_agency($agency_id);

$_level = new Level1();
$_partner = new PartnerType();
$_db = new db();
?>
<div class="col-sm-12 col-xs-12">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title"><font class="text-primary"><?php echo strtoupper($_agency->get_agency_name($agency_id)); ?></font> <span class="glyphicon glyphicon-chevron-right"
                                                                                                                                             aria-hidden="true"></span> EDIT INFO
                <span class="pull-right"><a href="agency_summary?id=<?= $_GET['id'] ?>" class="text-primary"><span class="glyphicon glyphicon-chevron-left"
                                                                                                                   aria-hidden="true"></span> Org Summary</a></span>
            </h3>
        </div>

        <form class="form-horizontal" enctype="multipart/form-data" id="editAgencyInfo" role="form" method="post"
              action="../_lib/agencyaction.php?action=<?php echo $_core->encode('EditAgencyInfo'); ?>">

            <table class="table">

                <input type="hidden" name="aid" value="<?php echo $_core->gpGet('id'); ?>">
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-12  col-xs-12">
                                <label for="name" class="control-label">Organization Name <span class="text-danger">*</span></label>
                                <input type="input" name="agency_name" class="form-control" id="agency_name" value="<?php echo $f['agency_name']; ?>" required>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
                                <label for="title" class="control-label"><?= $_SESSION['Level1_Label'] ?> <span class="text-danger">*</span></label>
                                <select id="level1" name="level_1[]" class="form-control" multiple required>
                                    <?= $_level->BuildLevelDropDown($f['level_1']) ?>
                                </select>
                            </div>
                            <div class="col-sm-6 col-xs-6">
                                <label for="title" class="control-label">Partner Type <span class="text-danger">*</span></label>
                                <select id="partner_type" name="partner_type[]" class="form-control" multiple required>
                                    <?= $_partner->BuildPartnerDropDown($f['partner_type']) ?>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>

                        <div class="form-group">
                            <div class="col-sm-12 col-xs-12">
                                <label for="address" class="control-label">Address <span class="text-danger">*</span></label>
                                <input type="input" name="agency_address" class="form-control" id="agency_address" value="<?php echo $f['agency_address']; ?>" required>
                            </div>

                        </div>

                        <div class="form-group" style="padding-top:10px;">
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="city">City <span class="text-danger">*</span></label>
                                <input type="input" name="agency_city" class="form-control" id="agency_city" value="<?php echo $f['agency_city']; ?>" required>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label for="state" class="control-label">State <span class="text-danger">*</span></label>
                                <select class="form-control text-uppercase" id="agency_state" name="agency_state" required>
                                    <?= $_db->buildDropState($f['agency_state']) ?>
                                </select>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="zip">Zipcode <span class="text-danger">*</span></label>
                                <input type="input" name="agency_zipcode" class="form-control" id="agency_zipcode" value="<?php echo $f['agency_zipcode']; ?>" required>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>

                        <div class="form-group">
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="phone">Phone <span class="text-danger">*</span></label>
                                <input type="input" name="agency_telephone" class="form-control" id="agency_telephone" value="<?php echo $f['agency_telephone']; ?>" required>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label for="fax" class="control-label">Fax</label>
                                <input type="input" name="agency_fax" class="form-control" id="agency_fax" value="<?php echo $f['agency_fax']; ?>">
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="url">Website</label>
                                <input type="url" name="agency_url" class="form-control" id="agency_url" value="<?php echo $f['agency_url']; ?>">
                            </div>
                        </div>

                    </td>
                </tr>

                <?php

                if (UserAccess::ManageLevel1()) {
                    echo '
                    <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="status">Status</label>
                                <select name="status" class="form-control">
                                    ' . $_db->buildDropMenu('cp_user_status', $f['status']) . '
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
                    ';
                }

                ?>


                <tr>
                    <td>

                        <div class="form-group">
                            <div class="col-sm-12 col-xs-12">
                                <label class="control-label" for="latitude">About Us</label>
                                <textarea class="form-control" name="description" rows="10"><?= $f['description'] ?></textarea>
                            </div>

                        </div>

                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-8 col-xs-8">
                                <?php
                                if ($_agency->agency_logo_exists($agency_id) >= 1) {
                                    echo '<label class="control-label" for="add_logo">Logo</label>';
                                    echo '<img src="' . $_agency->get_agency_logo($agency_id) . '" class="img-responsive">';
                                    echo '<a class="btn btn-xs btn-danger" onClick="delLogo();">Delete Logo <span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
                                } else {
                                    echo '
                                <label class="control-label" for="add_logo">Add Logo</label>
                                <input id="upload_logo" name="upload_logo" type="file">';
                                }
                                ?>
                            </div>

                        </div>
                    </td>
                </tr>

                <tr>
                    <td>


                        <button type="submit" class="btn btn-danger pull-right">EDIT <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>

                    </td>
                </tr>


            </table>
        </form>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="../js/fileinput.min.js"></script>
<script src="../js/jquery-input-mask-phone-number.min.js"></script>
<script src="../js/maskedinput.js"></script>
<script>
    $('#upload_logo').fileinput({
        allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif']
    });

    function delLogo() {
        window.location = "../_lib/action.php?action=<?php echo $_core->encode('del_logo'); ?>&lid=<?php echo $_core->gpGet('id'); ?>";
    }

    $(function () {
        $("#agency_url").change(function() {
            if (!/^https*:\/\//.test(this.value)) {
                this.value = "http://" + this.value;
            }
        });

        $("#level1").select2({
            theme: "bootstrap"
        });

        $("#partner_type").select2({
            theme: "bootstrap"
        });

        $('#agency_telephone, #agency_fax').on('input', function (event) {
            this.value = this.value.replace(/[^0-9-]/g, '');
        });

        $("#agency_telephone").usPhoneFormat();
        $("#agency_fax").usPhoneFormat();
        $("#agency_name").keyup(function () {
            var agencyName = $("#agency_name").val();
            $("#agency_name").val(ucwords(agencyName));
        });
    });


</script>