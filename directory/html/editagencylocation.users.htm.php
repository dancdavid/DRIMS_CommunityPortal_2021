<?php
$_core = new core();
$_level = new Level1();
$_db = new db();
$agency_id = $_core->decode($_core->gpGet('id'));
$_agency = new agency($agency_id);
//$_agency = new agency();
$f = $_agency->GetLocationData($_core->decode($_core->gpGet('lid')));
?>

<div class="col-sm-12 col-xs-12">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">EDIT <?= strtoupper($_agency->get_agency_name($agency_id)); ?> LOCATION
                <span class="pull-right"><a href="agency_summary?id=<?= $_GET['id'] ?>" class="text-primary"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Org Summary</a></span>
            </h3>
        </div>

        <table class="table">
            <form class="form-horizontal" enctype="multipart/form-data" id="editAgencyLocation" role="form" method="post" action="../_lib/agencyaction.php?action=<?php echo $_core->encode('EditAgencyLocation'); ?>">
                <input type="hidden" name="aid" value="<?= $_core->gpGet('id'); ?>">
                <input type="hidden" name="lid" value="<?= $_core->gpGet('lid'); ?>">
                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-12  col-xs-12">
                                <label for="name" class="control-label">Location Name <font class="text-danger">*</font></label>
                                <input type="input" name="location_name" class="form-control" id="location_name" value="<?= $f['location_name'] ?>" required>
                            </div>

                        </div>

                    </td>
                </tr>

                <tr>
                    <td>

                        <div class="form-group">
                            <div class="col-sm-12 col-xs-12">
                                <label for="address" class="control-label">Address <font class="text-danger">*</font></label>
                                <input type="input" name="address" class="form-control" id="address" value="<?= $f['address'] ?>" required>
                            </div>

                        </div>

                        <div class="form-group" style="padding-top:60px;">
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="city">City <font class="text-danger">*</font></label>
                                <input type="input" name="city" class="form-control" id="city" value="<?= $f['city'] ?>" required>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label for="state" class="control-label">State <font class="text-danger">*</font></label>
                                <select class="form-control text-uppercase" id="state" name="state" required>
                                    <?= $_db->buildDropState($f['state']) ?>
                                </select>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="zip">Zipcode <font class="text-danger">*</font></label>
                                <input type="input" name="zip_code" class="form-control" id="zip_code" value="<?= $f['zip_code'] ?>" required>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>

                        <div class="form-group">
                            <div class="col-sm-4 col-xs-4">
                                <label for="level1" class="control-label"><?= $_SESSION['Level1_Label'] ?> <span class="text-danger">*</span></label>
                                <select id="level1" name="level_1[]" class="form-control" multiple required>
                                    <?= $_level->BuildLevelDropDown($f['level_1']) ?>
                                </select>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label class="control-label" for="phone">Phone <font class="text-danger">*</font></label>
                                <input type="input" name="phone" class="form-control" id="phone" value="<?= $f['phone'] ?>" required/>
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                <label for="fax" class="control-label">Fax</label>
                                <input type="input" name="fax" class="form-control" id="fax" value="<?= $f['fax'] ?>">
                            </div>
                        </div>

                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group">
                            <div class="col-sm-4 col-xs-4">
                                <label for="status" class="control-label">Status</label>
                                <select name="status" class="form-control">
                                    <?= $_db->buildDropMenu('cp_user_status', $f['status']) ?>
                                </select>
                            </div>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td>


                        <button type="submit" class="btn btn-danger pull-right">EDIT LOCATION</button>

                    </td>
                </tr>

            </form>

        </table>

    </div>
</div>


<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="../js/jquery-input-mask-phone-number.min.js"></script>
<script src="../js/maskedinput.js"></script>
<script>
    $(function () {
        $("#level1").select2({
            theme: "bootstrap"
        });

        $("#phone").usPhoneFormat();
        $("#fax").usPhoneFormat();
        $("#zip_code").mask("99999", {placeholder: " "});

        $("#location_name").keyup(function () {
            var locationName = $("#location_name").val();
            $("#location_name").val(ucwords(locationName));
        });

    });
</script>