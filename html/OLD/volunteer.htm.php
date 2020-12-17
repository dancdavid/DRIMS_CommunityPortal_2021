<?php $_core = new core(); ?>
<div class="container" style="padding-top:130px;">
    <div class="col-sm-2 col-xs-2">

    </div>
    <div class="col-sm-8 col-xs-8">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">VOLUNTEER</h3>
            </div>
            <div class="panel-body">

                <div class="col-xs-12 col-sm-12">
                    <!--<img src="images/trac_header6.png" class="img-responsive">-->
                    <img src="images/365_Logo.jpg" class="img-responsive">
                </div>

                <div class="col-xs-12 col-sm-12">
                    <h3>Please complete the form below to volunteer to help BARR with preparedness / recovery activities.</h3>
                </div>

                <div class="col-xs-12 col-sm-12">
                    <div class="well">
                        <form class="form-horizontal" id="addVolunteer" role="form" method="post" action="_lib/action.php?action=<?php echo $_core->encode('addVolunteer'); ?>">


                            <div class="form-group">

                                <div class="col-sm-6  col-xs-6">
                                    <label for="first" class="control-label"><font class="text-danger">*</font>First Name</label>
                                    <input type="input" name="first_name" class="form-control " id="first_name" required>
                                </div>

                                <div class="col-sm-6  col-xs-6">
                                    <label for="last" class="control-label"><font class="text-danger">*</font>Last Name</label>
                                    <input type="input" name="last_name" class="form-control " id="last_name" required>
                                </div>
                            </div>



                            <div class="form-group">
                                <div class="col-sm-6  col-xs-6">
                                    <label for="email" class="control-label"><font class="text-danger">*</font>Email</label>
                                    <input type="email" name="email" class="form-control " id="email" required>
                                </div>
                                <div class="col-sm-6  col-xs-6">
                                    <label for="phone" class="control-label"><font class="text-danger">*</font>Phone</label>
                                    <input type="input" name="phone" class="form-control " id="phone" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12  col-xs-12">
                                    <label for="address" class="control-label"><font class="text-danger">*</font>Address</label>
                                    <input type="input" name="address" class="form-control " id="address" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-4  col-xs-4">
                                    <label for="city" class="control-label"><font class="text-danger">*</font>City</label>
                                    <input type="input" name="city" class="form-control " id="city" required>
                                </div>

                                <div class="col-sm-4  col-xs-4">
                                    <label for="state" class="control-label"><font class="text-danger">*</font>State</label>
                                    <!--<input type="input" name="state" class="form-control " id="state" required>-->
                                    <select name="state" class="form-control" required>
                                        <option value="">Select State</option>
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
                                    <label for="zip" class="control-label"><font class="text-danger">*</font>Zip Code</label>
                                    <input type="input" name="zipcode" class="form-control " id="zipcode" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-4 col-xs-4">
                                    <label class="control-label" for="sdate">Start Date</label>
                                    <div class='input-group date' id='dateStart' data-date-format="YYYY-MM-DD">
                                        <input type='text' name="start_date" class="form-control" placeholder="YYYY-MM-DD">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4 col-xs-4">
                                    <label class="control-label" for="edate">End Date</label>
                                    <div class='input-group date' id='dateEnd' data-date-format="YYYY-MM-DD">
                                        <input type='text' name="end_date" class="form-control" placeholder="YYYY-MM-DD">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4 col-xs-4"></div>
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
                                                        <input type="checkbox" name="category[]" value="ADMINISTRATIVE">
                                                        ADMINISTRATIVE
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="DONATE GOODS">
                                                        DONATE GOODS
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="FINANCIAL">
                                                        FINANCIAL
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="GENERAL">
                                                        GENERAL
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="COOKING/FEEDING">
                                                        COOKING/FEEDING
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="MEDICAL">
                                                        MEDICAL
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="LEGAL">
                                                        LEGAL
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="CHAPLAINS">
                                                        CHAPLAINS
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="INTERPRETER">
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
                                                        <input type="checkbox" name="category[]" value="ELDERLY CARE">
                                                        ELDERLY CARE
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="CHILD CARE">
                                                        CHILD CARE
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="ANIMAL CARE">
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
                                                        <input type="checkbox" name="category[]" value="SOCIAL MEDIA">
                                                        SOCIAL MEDIA
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="IT TECHNICIAN">
                                                        IT TECHNICIAN
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="PHOTO/VIDEO">
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
                                                        <input type="checkbox" name="category[]" value="CHAINSAW CREW">
                                                        CHAINSAW CREW
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="MUCK OUT">
                                                        MUCK OUT
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="DEMOLITION">
                                                        DEMOLITION
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="TARPING">
                                                        TARPING
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="category[]" value="ROOFING">
                                                        ROOFING
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-xs-4">

                                            </div>

                                        </div>
                                    </div>
                                </div>



                            </div><!-- end Volunteer cat group -->





                            <!--                            <div class="form-group">
                                                            <div class="col-sm-12 col-xs-12">
                                                                <label class="control-label" for="vol_desc">Volunteer Description</label>
                                                                <textarea class="form-control" name="description" rows="10" placeholder="Tell us about how you would like to volunteer."></textarea>
                                                            </div>
                                                        </div>-->

                    </div> <!-- end well -->

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <button type="submit" id="submit_volunteer" class="btn btn-danger pull-right">SUBMIT <span class="glyphicon glyphicon-inbox" aria-hidden="true"></span></button>
                        </div>
                    </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<!--<div class="col-sm-2 col-xs-2">

</div>-->
</div>


<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalLabel">Thank you for volunteering!</h4>
            </div>
            <div class="modal-body">
                Thank You for your willingness to volunteer. Your information has been sent to agency contacts and coordinators. If there is a matching need they will be in touch with you to coordinate your volunteer opportunity.
            </div>
        </div>
    </div>
</div>

<div class="overlay">
    <div id="loading-img"></div>
</div>


<script src="js/maskedinput.js"></script>
<script src="js/holder_js.js"></script>
<script type="text/javascript" src="js/Moment.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>

<script>
    var $_GET = '<?php echo $_core->gpGet('e'); ?>';

    $(function () {

        $('#successModal').modal({'show': false});
        if ($_GET === 'Success') {
            $('#successModal').modal('show');
        }

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
        
        $("#submit_volunteer").click(function () {
            $(".overlay").show();
        });
        
        $('#dateStart').datetimepicker();
        $('#dateEnd').datetimepicker();

    });
</script>