<?php
$_core = new core();

$_db = new db();

$event_id = $_core->decode($_core->gpGet('id'));

$dbh = $_db->initDB();
$qry = "select * from cp_team_events_calendar where id = :id";
$sth = $dbh->prepare($qry);
$sth->execute(array(":id" => $event_id));
$f = $sth->fetch(PDO::FETCH_OBJ);
?>
<div class="col-sm-12 col-xs-12">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">EDIT EVENT</h3>
        </div>
        <div class="panel-body">

            <form class="form-horizontal" enctype="multipart/form-data" id="postMessage" role="form" method="post" action="../_lib/tmaction.php?action=<?php echo $_core->encode('editTeamEvent'); ?>">
                <input type="hidden" name="id" value="<?php echo $_core->gpGet('id'); ?>">
                <input type="hidden" name="tid" value="<?php echo $_core->gpGet('tid'); ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="title" class="control-label">Event Title <span class="text-danger">*</span></label>
                            <input type="input" name="event_title" class="form-control " id="title" value="<?php echo $f->event_title ?>" required>
                        </div>

                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label for="address" class="control-label">Location or Address</label>
                            <input type="input" name="event_address" class="form-control " id="event_address"  value="<?php echo $f->event_address ?>">
                        </div>

                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 col-xs-4">
                            <label class="control-label" for="city">City</label>
                            <input type="input" name="event_city" class="form-control " id="event_city" value="<?php echo $f->event_city ?>">
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <label for="state" class="control-label">State</label>
                            <input type="input" name="event_state" class="form-control " id="event_state" value="<?php echo $f->event_state ?>">
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <label class="control-label" for="zip">Zipcode</label>
                            <input type="input" name="event_zip" class="form-control " id="event_zip" value="<?php echo $f->event_zip ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 col-xs-4">
                            <label class="control-label" for="event_date">Event Date <span class="text-danger">*</span></label>
<!--                            <div class='input-group date' id='datePickerStart' data-date-format="YYYY-MM-DD">-->
<!--                                <input type='text' name="event_date" class="form-control " placeholder="YYYY-MM-DD" value="--><?php //echo $f->event_date ?><!--" required>-->
<!--                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>-->
<!--                                </span>-->
<!--                            </div>-->
                            <input type='text' name="event_date" class="form-control" id='datePickerStart' placeholder="mm-dd-yyyy" value="<?= date('m-d-Y', strtotime($f->event_date)) ?>"  required>
                        </div>

                        <?php
                        $time = explode(":",$f->event_time);
                        $period = explode(" ",$f->event_time);

                        $endTime = explode(":",$f->event_end_time);
                        $endPeriod = explode(" ",$f->event_end_time);
                        ?>

                        <div class="col-sm-2 col-xs-1">
                            <label class="control-label" for="event_time">Time</label>
                            <select name="t1" id="hour" class="form-control ">
                                <?php echo $_core->dropHour($time[0]); ?>
                            </select>
                        </div>

                        <div class="col-sm-2 col-xs-1">
                            <label class="control-label" for="event_time">&nbsp;</label>
                            <select name="t2" id="minute" class="form-control ">
                                <?php echo $_core->dropMinute($time[1]); ?>
                            </select>

                        </div>
                        <div class="col-sm-2 col-xs-1">
                            <label class="control-label" for="event_time">&nbsp;</label>
                            <select name="t3" id="period" class="form-control ">
                                <?= '<option value="'.$period[1].'">'.$period[1].'</option>' ?>
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 col-xs-4"></div>

                        <div class="col-sm-2 col-xs-2">
                            <label class="control-label" for="event_end_time">End Time</label>
                            <select name="t1_end" id="hour_end" class="form-control">
                                <?php echo $_core->dropHour($endTime[0]); ?>
                            </select>
                        </div>

                        <div class="col-sm-2 col-xs-2">
                            <label class="control-label" for="event_end_time">&nbsp;</label>
                            <select name="t2_end" id="minute_end" class="form-control">
                                <?php echo $_core->dropMinute($endTime[1]); ?>
                            </select>

                        </div>
                        <div class="col-sm-2 col-xs-2">
                            <label class="control-label" for="event_end_time">&nbsp;</label>
                            <select name="t3_end" id="period_end" class="form-control">
                                <?= '<option value="'.$endPeriod[1].'">'.$endPeriod[1].'</option>' ?>
                                <option value="AM">AM</option>
                                <option value="PM">PM</option>
                            </select>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 col-xs-4">
                            <label class="control-label reoccuring-box" for="event_date">Reoccuring Event </label>
                            <input type='checkbox' name="is_reoccuring" value="1" id="event_type" <?php echo $f->is_reoccuring=="1"?"checked":"" ?>  >
                        </div>

                        <div class="col-sm-6 col-xs-6 <?php echo $f->is_reoccuring=="1"?"":"hide" ?> " id="event_end_date" >
                            <label class="control-label reoccuring-box" for="event_date">Event End Date<span class="text-danger">*</span> </label>
                            <input type='text' name="event_end_date" class="form-control" id='eventEndDate' placeholder="mm-dd-yyyy" value="<?= is_null($f->event_end_date)?"":date('m-d-Y', strtotime($f->event_end_date)) ?>" <?php echo $f->is_reoccuring=="1"?"required":"" ?> >
                        </div>


                        <div class="col-sm-6 col-xs-6 <?php echo $f->is_reoccuring=="1"?"":"hide" ?>" id="event_reoccuring_type">
                            <label class="control-label" for="event_time">Reoccuring Type<span class="text-danger">*</span></label>
                            <select name="event_reoccuring_type" class="form-control" <?php echo $f->is_reoccuring=="1"?"required":"" ?>>
                                <option value="">Choose Reoccuring Type</option>
                                <option value="Daily" <?php echo $f->event_reoccuring_type=="Daily"?"selected":"" ?> >Daily</option>
                                <option value="Weekly" <?php echo $f->event_reoccuring_type=="Weekly"?"selected":"" ?> >Weekly</option>
                                <option value="Weekly Day" <?php echo $f->event_reoccuring_type=="Weekly Day"?"selected":"" ?> >Weekly Day</option>
                                <option value="Monthly" <?php echo $f->event_reoccuring_type=="Monthly"?"selected":"" ?> >Monthly</option>
                            </select>
                        </div>                        
                    </div>

                    <div class="form-group <?php echo $f->event_reoccuring_type=="Weekly Day"?"":"hide" ?>" id="event_reoccuring_days">
                        <?php
                            $weekdays = array();
                            if($f->event_reoccuring_type=="Weekly Day"){
                                $weekdays = explode(',',$f->event_reoccuring_days);
                            }
                        ?>
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label reoccuring-box" for="event_date">Select Weekdays<span class="text-danger">*</span></label>
                            <select id="select_weekday"  name="event_reoccuring_days[]" class="form-control" multiple  style="width:100%;" <?php echo $f->event_reoccuring_type=="Weekly Day"?"required":"" ?> >
                                <option value="0" <?php echo in_array('0',$weekdays)?"selected":""?> >Sunday</option>
                                <option value="1" <?php echo in_array('1',$weekdays)?"selected":""?> >Monday</option>
                                <option value="2" <?php echo in_array('2',$weekdays)?"selected":""?> >Tuesday</option>
                                <option value="3" <?php echo in_array('3',$weekdays)?"selected":""?> >Wednesday</option>
                                <option value="4" <?php echo in_array('4',$weekdays)?"selected":""?> >Thursday</option>
                                <option value="5" <?php echo in_array('5',$weekdays)?"selected":""?> >Friday</option>
                                <option value="6" <?php echo in_array('6',$weekdays)?"selected":""?> >Saturday</option>
                            </select>
                        </div>         
                    </div>



                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="timezone">Select Timezone</label>
                            <input type="hidden" id="prev_timezone" value="<?= $f->timezone ?>">
                            <select name="timezone" id="timezone" class="form-control">
                                <?= $_db->buildTimezoneDropdown() ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="timezone">Select Timezone</label>
                            <input type="hidden" id="prev_timezone" value="<?= $f->timezone ?>">
                            <select name="timezone" id="timezone" class="form-control">
                                <?= $_db->buildTimezoneDropdown() ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="url">Website</label>
                            <input type="url" name="url" class="form-control" id="event_url" value="<?= $f->url ?>" placeholder="http://">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="description">Short Description</label>
                            <textarea name="event_description"> <?php echo $f->event_description ?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12 attachment_wrapper">
                            <label class="control-label" for="attachments">Add File(s)</label>
                            <input id="upload_attachment" name="upload_attachment[]" type="file" multiple>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php
                        $_files = new EventFiles();
                        echo $_files->DeleteFilesTable($event_id,'cp_team_events_calendar_file_upload');
                        ?>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="message">Status</label>
                            <select name="status">
                                <?php echo "<option value='{$f->status}'>{$f->status}</option>"; ?>
                                <option>______________</option>
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="DELETED">DELETE</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <button type="submit" class="btn btn-primary pull-right">EDIT <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                        </div>
                    </div>

                </div>

            </form>

        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet"/>

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<!--<script type="text/javascript" src="../js/Moment.js"></script>-->
<!--<script type="text/javascript" src="../js/bootstrap-datetimepicker.min.js"></script>-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/tinymce.min.js"></script>
<script type="text/javascript" src="../js/fileinput.min.js"></script>
<script type="text/javascript" src="../js/fileUpload.js"></script>

<script>
    $(function () {

        var timezone = $('#prev_timezone').val();
        $("#timezone").val(timezone).change();

        $("#event_url").change(function() {
            if (!/^https*:\/\//.test(this.value)) {
                this.value = "http://" + this.value;
            }
        });

        $('#datePickerStart').datepicker({
            dateFormat: "mm-dd-yy"
        });

        tinymce.init({
            selector: 'textarea',
            menubar: false,
            plugins: [
                'advlist autolink lists link charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code'
            ],
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link '
        });

        $('.delete').on('click', function(e) {
            e.preventDefault();
            var fileId = $(this).data('id');
            $.post("../_lib/tmajax.php?action=DeleteEventFile", { id: fileId });
            $(this).closest('tr').remove();
        });

        jQuery('#event_type').change(function(){
            if(jQuery(this).prop('checked')===true){
               jQuery('#event_reoccuring_type,#event_end_date').removeClass('hide');
               jQuery('select[name ="event_reoccuring_type"],#eventEndDate').prop('required',true);
                if(jQuery('select[name ="event_reoccuring_type"]').val()=='Weekly Day'){
                    jQuery('#event_reoccuring_days').removeClass("hide");
                    jQuery("#select_weekday").prop('required',true);
                }
                else{
                    jQuery('#event_reoccuring_days').addClass("hide");
                    jQuery("#select_weekday").prop('required',false);                
                }
            }
            else{
                jQuery('#event_reoccuring_type,#event_end_date').addClass('hide');
                jQuery('select[name ="event_reoccuring_type"],#eventEndDate').prop('required',false);  
                jQuery('#event_reoccuring_days').addClass("hide").prop('required',false);

            }
        });

        jQuery('select[name ="event_reoccuring_type"]').change(function(){
            if(jQuery(this).val()=='Weekly Day'){
                jQuery('#event_reoccuring_days').removeClass("hide");
                jQuery("#select_weekday").prop('required',true);
            }
            else{
                jQuery('#event_reoccuring_days').addClass("hide");
                jQuery("#select_weekday").prop('required',false);                
            }
        });

        jQuery("#select_weekday").select2();

        jQuery('#eventEndDate').datepicker({
            dateFormat: "mm-dd-yy",
            maxDate: "+1y"
        });
    });
</script>