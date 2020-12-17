<?php
$_core = new core();
//if ($_SESSION['agency_level'] != '2') {
//    $_core->redir('directory/agency_directory');
//}

$_level = new Level1();
$_partner = new PartnerType();
$_db = new db();
?>

<div class="col-sm-12 col-xs-12">
    <?php if(isset($_GET['m']) && $_GET['m']){ ?>
        <div class="alert alert-success">
        <strong>Success!</strong> <?= $_GET['m'] ?>
    </div>
    <?php } ?>
    <?php if(isset($_GET['e']) && $_GET['e']){ ?>
        <div class="alert alert-error">
        <strong>Failure!</strong> <?= $_GET['e'] ?>
    </div>
    <?php } ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">ADD ORGANIZATION INFO</h3>
        </div>

        <table class="table">
            <form class="form-horizontal" enctype="multipart/form-data" id="addAgencyInfo" role="form" method="post" action="../_lib/agencyaction.php?action=<?php echo $_core->encode('AddAgencyInfo'); ?>">
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-12  col-xs-12">
                                <label for="name" class="control-label">Organization Name <font class="text-danger">*</font></label>
                                <input autocomplete="off" type="input" name="agency_name" class="form-control" id="agency_name" required>
                                <div id="search_results"></div>
                            </div>

                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-6 col-xs-6">
                                <label for="level1" class="control-label"><?= $_SESSION['Level1_Label'] ?> <span class="text-danger">*</span></label>
                                <select id="level1" name="level_1[]" class="form-control" multiple required>
                                    <?= $_level->BuildLevelDropDown() ?>
                                </select>
                            </div>
                            <div class="col-sm-6 col-xs-6">
                                <label for="title" class="control-label">Partner Type <span class="text-danger">*</span></label>
                                <select id="partner_type" name="partner_type[]" class="form-control" multiple required>
                                    <?= $_partner->BuildPartnerDropDown() ?>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>

                        <div class="form-group">
                            <div class="col-sm-12 col-xs-12">
                                <label for="address" class="control-label">Address <font class="text-danger">*</font></label>
                                <input type="input" name="agency_address" class="form-control" id="agency_address" required>
                            </div>

                        </div>

                        <div class="form-group" style="padding-top:60px;">
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="city">City <font class="text-danger">*</font></label>
                                <input type="input" name="agency_city" class="form-control" id="agency_city" required>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label for="state" class="control-label">State <font class="text-danger">*</font></label>
                                <select class="form-control text-uppercase" id="agency_state" name="agency_state" required>
                                    <?= $_db->buildDropState() ?>
                                </select>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="zip">Zipcode <font class="text-danger">*</font></label>
                                <input type="input" name="agency_zipcode" class="form-control" id="agency_zipcode" required>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>

                        <div class="form-group">
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="phone">Phone <font class="text-danger">*</font></label>
                                <input type="input" name="agency_telephone" class="form-control" id="agency_telephone" required/>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label for="fax" class="control-label">Fax</label>
                                <input type="input" name="agency_fax" class="form-control" id="agency_fax">
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="url">Website</label>
                                <input type="url" name="agency_url" class="form-control" id="agency_url" placeholder="http://">
                            </div>
                        </div>

                    </td>
                </tr>
                <!--                <tr><td>

                                        <div class="form-group">
                                            <div class="col-sm-4 col-xs-4">
                                                <label class="control-label" for="latitude">Latitude</label>
                                                <input type="input" name="a_latitude" class="form-control" id="a_latitude">
                                            </div>
                                            <div class="col-sm-4 col-xs-4">
                                                <label for="Longitude" class="control-label">Longitude</label>
                                                <input type="input" name="a_longitude" class="form-control" id="a_longitude">
                                            </div>
                                            <div class="col-sm-4 col-xs-4">
                                                <label class="control-label" for="gps">GPS Coordinates</label>
                                                <input type="input" name="agency_gps" class="form-control" id="agency_gps">
                                            </div>
                                        </div>

                                    </td></tr>-->

                <tr>
                    <td>

                        <div class="form-group">
                            <div class="col-sm-12 col-xs-12">
                                <label class="control-label" for="latitude">About Us</label>
                                <textarea class="form-control" name="description" rows="10"></textarea>
                            </div>

                        </div>

                    </td>
                </tr>

                <tr>
                    <td>


                        <button type="submit" class="btn btn-danger pull-right">SAVE <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></button>

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
<script src="../js/maskedinput.js"></script>
<script>
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
        $("#agency_zipcode").mask("99999", {placeholder: " "});

        $("#agency_name").keyup(function () {
            var agencyName = $("#agency_name").val();
            $("#agency_name").val(ucwords(agencyName));
        });

    });
</script>