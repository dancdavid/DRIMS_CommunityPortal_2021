<?php
$_core = new core();

$volunteer_id = $_core->decode($_core->gpGet('id'));
$_vol = new volunteer($volunteer_id);

$f = $_vol->get_volunteer_data();
?>

<div class="col-sm-12 col-xs-12">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">EDIT VOLUNTEER: <?php echo $f['first_name'] . " " . $f['last_name'] . " ";
if (isset($_GET['e'])) {
    echo $_GET['e'];
} ?></h3>
        </div>
        <div class="panel-body">

            <div class="col-xs-12 col-sm-12">
                <!--<div class="well">-->
                <form class="form-horizontal" id="addVolunteer" role="form" method="post" action="../_lib/action.php?action=<?php echo $_core->encode('editVolunteer'); ?>">


                    <div class="form-group">

                        <div class="col-sm-6  col-xs-6">
                            <label for="first" class="control-label">First Name</label>
                            <input type="input" name="first_name" class="form-control " id="first_name" value="<?php echo $f['first_name']; ?>">
                        </div>

                        <div class="col-sm-6  col-xs-6">
                            <label for="last" class="control-label">Last Name</label>
                            <input type="input" name="last_name" class="form-control " id="last_name" value="<?php echo $f['last_name']; ?>">
                        </div>
                    </div>

                    <input type="hidden" value="<?php echo $_core->gpGet('id'); ?>" name="vid">

                    <div class="form-group">
                        <div class="col-sm-6  col-xs-6">
                            <label for="email" class="control-label">Email</label>
                            <input type="email" name="email" class="form-control " id="email" value="<?php echo $f['email']; ?>">
                        </div>
                        <div class="col-sm-6  col-xs-6">
                            <label for="phone" class="control-label">Phone</label>
                            <input type="input" name="phone" class="form-control " id="phone" value="<?php echo $f['phone']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="address" class="control-label">Address</label>
                            <input type="input" name="address" class="form-control " id="address" value="<?php echo $f['address']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4  col-xs-4">
                            <label for="city" class="control-label">City</label>
                            <input type="input" name="city" class="form-control " id="city" value="<?php echo $f['city']; ?>">
                        </div>

                        <div class="col-sm-4  col-xs-4">
                            <label for="state" class="control-label">State</label>

                            <select name="state" class="form-control" >
<?php echo "<option value='{$f['city']}' selected>{$f['city']}</option>"; ?>
                                <OPTION VALUE='AL'> Alabama
                                <OPTION VALUE='AK'> Alaska
                                <OPTION VALUE='AZ'> Arizona
                                <OPTION VALUE='AR'> Arkansas
                                <OPTION VALUE='CA'> California
                                <OPTION VALUE='CO'> Colorado
                                <OPTION VALUE='CT'> Connecticut
                                <OPTION VALUE='DE'> Delaware
                                <OPTION VALUE='DC'> District of Columbia
                                <OPTION VALUE='FL'> Florida
                                <OPTION VALUE='GA'> Georgia
                                <OPTION VALUE='HI'> Hawaii
                                <OPTION VALUE='ID'> Idaho
                                <OPTION VALUE='IL'> Illinois
                                <OPTION VALUE='IN'> Indiana
                                <OPTION VALUE='IA'> Iowa
                                <OPTION VALUE='KS'> Kansas
                                <OPTION VALUE='KY'> Kentucky
                                <OPTION VALUE='LA'> Louisiana
                                <OPTION VALUE='ME'> Maine
                                <OPTION VALUE='MD'> Maryland
                                <OPTION VALUE='MA'> Massachusetts
                                <OPTION VALUE='MI'> Michigan
                                <OPTION VALUE='MN'> Minnesota
                                <OPTION VALUE='MS'> Mississippi
                                <OPTION VALUE='MO'> Missouri
                                <OPTION VALUE='MT'> Montana
                                <OPTION VALUE='NE'> Nebraska
                                <OPTION VALUE='NV'> Nevada
                                <OPTION VALUE='NH'> New Hampshire
                                <OPTION VALUE='NJ'> New Jersey
                                <OPTION VALUE='NM'> New Mexico
                                <OPTION VALUE='NY'> New York
                                <OPTION VALUE='NC'> North Carolina
                                <OPTION VALUE='ND'> North Dakota
                                <OPTION VALUE='OH'> Ohio
                                <OPTION VALUE='OK'> Oklahoma
                                <OPTION VALUE='OR'> Oregon
                                <OPTION VALUE='PA'> Pennsylvania
                                <OPTION VALUE='RI'> Rhode Island
                                <OPTION VALUE='SC'> South Carolina
                                <OPTION VALUE='SD'> South Dakota
                                <OPTION VALUE='TN'> Tennessee
                                <OPTION VALUE='TX'> Texas
                                <OPTION VALUE='UT'> Utah
                                <OPTION VALUE='VT'> Vermont
                                <OPTION VALUE='VA'> Virginia
                                <OPTION VALUE='WA'> Washington
                                <OPTION VALUE='WV'> West Virginia
                                <OPTION VALUE='WI'> Wisconsin
                                <OPTION VALUE='WY'> Wyoming
                            </select>
                        </div>

                        <div class="col-sm-4  col-xs-4">
                            <label for="zip" class="control-label">Zip Code</label>
                            <input type="input" name="zipcode" class="form-control " id="zipcode" value="<?php echo $f['zipcode']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 col-xs-4">
                            <label class="control-label" for="sdate">Start Date</label>
                            <div class='input-group date' id='dateStart' data-date-format="YYYY-MM-DD">
                                <input type='text' name="start_date" class="form-control" placeholder="YYYY-MM-DD" value="<?php echo date('Y-m-d',strtotime($f['start_date'])); ?>">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-4 col-xs-4">
                            <label class="control-label" for="edate">End Date</label>
                            <div class='input-group date' id='dateEnd' data-date-format="YYYY-MM-DD">
                                <input type='text' name="end_date" class="form-control" placeholder="YYYY-MM-DD" value="<?php echo date('Y-m-d',strtotime($f['end_date'])); ?>">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                        <div class="col-sm-4 col-xs-4"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="notes" class="control-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="10"><?php echo $f['notes']; ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4  col-xs-4">
                            <label for="status" class="control-label">Status</label>
                            <select name="status" class="form-control">
<?php echo "<option value='{$f['status']}' selected>{$f['status']}</option>"; ?>
                                <option value="ACTIVE">ACTIVE</option>
                                <option valeu="IN-ACTIVE">IN-ACTIVE</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8  col-xs-8">

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="category">Volunteer Category</label>

                        </div>

                        <div class="col-sm-12 col-xs-12">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h3 class="panel-title">GENERAL</h3>
                                </div>
                                <div class="panel-body">

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="ADMINISTRATIVE" <?php $_vol->chk_category('ADMINISTRATIVE'); ?>>
                                                ADMINISTRATIVE
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="DONATE GOODS" <?php $_vol->chk_category('DONATE GOODS'); ?>>
                                                DONATE GOODS
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="FINANCIAL" <?php $_vol->chk_category('FINANCIAL'); ?>>
                                                FINANCIAL
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="GENERAL" <?php $_vol->chk_category('GENERAL'); ?>>
                                                GENERAL
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="COOKING/FEEDING" <?php $_vol->chk_category('COOKING/FEEDING'); ?>>
                                                COOKING/FEEDING
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="MEDICAL" <?php $_vol->chk_category('MEDICAL'); ?>>
                                                MEDICAL
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="LEGAL" <?php $_vol->chk_category('LEGAL'); ?>>
                                                LEGAL
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="CHAPLAINS" <?php $_vol->chk_category('CHAPLAINS'); ?>>
                                                CHAPLAINS
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="INTERPRETER" <?php $_vol->chk_category('INTERPRETER'); ?>>
                                                INTERPRETER
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>



                        <div class="col-sm-12 col-xs-12">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 class="panel-title">CARE</h3>
                                </div>
                                <div class="panel-body">

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="ELDERLY CARE" <?php $_vol->chk_category('ELDERLY CARE'); ?>>
                                                ELDERLY CARE
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="CHILD CARE" <?php $_vol->chk_category('CHILD CARE'); ?>>
                                                CHILD CARE
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="ANIMAL CARE" <?php $_vol->chk_category('ANIMAL CARE'); ?>>
                                                ANIMAL CARE
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-xs-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">TECHNOLOGY</h3>
                                </div>
                                <div class="panel-body">

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="SOCIAL MEDIA" <?php $_vol->chk_category('SOCIAL MEDIA'); ?>>
                                                SOCIAL MEDIA
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="IT TECHNICIAN" <?php $_vol->chk_category('IT TECHNICIAN'); ?>>
                                                IT TECHNICIAN
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="PHOTO/VIDEO" <?php $_vol->chk_category('PHOTO/VIDEO'); ?>>
                                                PHOTO/VIDEO
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-xs-12">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h3 class="panel-title">RESPONSE</h3>
                                </div>
                                <div class="panel-body">

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="CHAINSAW CREW" <?php $_vol->chk_category('CHAINSAW CREW'); ?>>
                                                CHAINSAW CREW
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="MOCK OUT" <?php $_vol->chk_category('MOCK OUT'); ?>>
                                                MOCK OUT
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="DEMOLITION" <?php $_vol->chk_category('DEMOLITION'); ?>>
                                                DEMOLITION
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="TARPING" <?php $_vol->chk_category('TARPING'); ?>>
                                                TARPING
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="category[]" value="ROOFING" <?php $_vol->chk_category('ROOFING'); ?>>
                                                ROOFING
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 col-xs-4">

                                    </div>

                                </div>
                            </div>
                        </div>

                    </div><!--  end Volunteer cat group -->



                    <!--   </div>  end well -->

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <button type="submit" class="btn btn-danger pull-right">EDIT <span class="glyphicon glyphicon-inbox" aria-hidden="true"></span></button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<script src="../js/maskedinput.js"></script>
<script type="text/javascript" src="../js/Moment.js"></script>
<script type="text/javascript" src="../js/bootstrap-datetimepicker.min.js"></script>


<script>


    $(function () {

        $("#phone").mask("999-999-9999", {placeholder: " "});

        $("#first_name").keyup(function () {
            var contactName = $("#first_name").val();
            $("#first_name").val(ucwords(contactName));
        });

        $("#address").keyup(function () {
            var address = $("#address").val();
            $("#address").val(ucwords(address));
        });

        $("#city").keyup(function () {
            var contactName = $("#city").val();
            $("#city").val(ucwords(contactName));
        });

        $("#state").keyup(function () {
            $(this).val($(this).val().toUpperCase());
        });

        $("#last_name").keyup(function () {
            var contactName = $("#last_name").val();
            $("#last_name").val(ucwords(contactName));
        });
        
        $('#dateStart').datetimepicker();
        $('#dateEnd').datetimepicker();

    });
</script>
