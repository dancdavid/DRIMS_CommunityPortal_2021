<?php
$_agency = new agency();
$agencyName = $_agency->get_agency_name($_SESSION['agency_id']);

$_level = new Level1();
?>

<style>
    td {
        padding: 10px;
    }
</style>

<div class="col-md-12">
    <table width="100%">
        <tr>
            <td width="60%"><h2 style="padding-left:15px;padding-bottom:10px;"><?= $agencyName ?> - Dashboard</h2></td>
            <td>
                <form>
                    <label for="title" class="control-label">Filter <?= $_SESSION['Level1_Label'] ?></label>
                    <select id="level1filter" class="form-control input-sm" multiple>
                        <?php

                        if (!empty($_GET['filter']) && $_GET['filter'] != 'null') {
                            $filter = $_GET['filter'];
                        } else {
                            $filter = str_replace(";", ',', $_SESSION['level_1_filter']);
                        }

                        echo $_level->BuildLevelDropDownFilter($filter);
                        ?>
                    </select>
                </form>
            </td>
        </tr>
    </table>
</div>

<!--<div class="col-sm-8 col-xs-8">-->
<!---->
<!--</div>-->
<!---->
<!--<div class="col-sm-4 col-xs-4" style="padding-right:30px;">-->
<!---->
<!--</div>-->

<div class="col-md-12">
    <table width="100%" style="table-layout:fixed">
        <tr>
            <td width="60%" valign="top">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Message Board
                            <?php
                            if (UserAccess::ManageLevel1()) {
                                echo '<span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalMessageBoard">+ POST</button></span>';
                            }
                            ?>

                        </h3>
                    </div>
                    <div class="panel-body" style="max-height: 700px;overflow-x: scroll;">
                        <?php
                        $_core = new core();
                        $_db = new db();

                        $dbh = $_db->initDB();

                        $limit = "limit 20";

                        if ($_core->gpGet('mb') === 'view_all') {
                            $limit = '';
                        }

                        //                        $parentAgencyId = $_db->SetParentAgencyId();
                        //                        $filter = $_core->gpGet('filter');

                        $qry = "select * from cp_message_board where";


                        if (!empty($filter) && $filter != 'null') {
                            $qry .= " (";
                            $filterArr = explode(",", $filter);
                            $i = 0;
                            foreach ($filterArr as $find) {
                                if ($i > 0) $qry .= " or";
                                $qry .= " find_in_set('{$find}', replace(level_1, ';', ','))";
                                $i++;
                            }
                            $qry .= " ) and";
                        }

                        $qry .= " parent_agency_id = '{$_SESSION['parent_agency']}'";
                        $qry .= " and status = 'ACTIVE'";


                        $qry .= " order by id desc $limit";


                        $sth = $dbh->prepare($qry);
                        $sth->execute();

                        $i = 1;
                        $_files = new EventFiles();
                        $files = '';
                        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

//                            $level1 = str_replace(";", ", ", $f->level_1);

                            $level1 = $_level->GetLevel1Name($f->level_1);
                            $post_date = date("M-d-Y", strtotime($f->timestamp));
                            $files = $_files->GetFiles($f->id, 'cp_message_board_file_upload', 'message');

                            echo "<b>{$f->title}</b> <br>";
                            echo $f->message . "<br>";
                            echo (!empty($files)) ? '<b>Files</b><br>' : '';
                            echo $files;
                            echo "<br><small class='pull-left'><i>$level1</i></small>";
                            echo "<small class='pull-right'>{$post_date} - <i>{$f->submitted_by}</i></small><hr></hr><br>";
                            $i++;
                        }

                        if ($i === 20) {
                            echo "<small class='pull-right'><a href='index&mb=view_all'>VIEW ALL</a></small>";
                        }
                        ?>
                    </div>
                </div>
            </td>

            <td valign="top">
                <!--            EVENT-->
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Events Calendar
                            <!--                            <button type="button" class="btn btn-default btn-xs" aria-label="Events Cal" data-toggle="collapse" href="#calendar" aria-expanded="false" aria-controls="calendar">-->
                            <!--                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>-->
                            <!--                            </button>-->
                            <?php
                            if (UserAccess::ManageLevel1()) {
                                echo '<span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalEventCalendar">+ ADD EVENT</button></span>';
                            }
                            ?>

                        </h3>
                    </div>
                    <div class="panel-body" style="max-height: 610px;overflow-y: scroll;">
                        <div class="row" style="padding-bottom:15px;">
                            <div class="col-lg-offset-6 col-lg-6">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="eventSearch" placeholder="">
                                    <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" id="eventSearchBtn">Search</button>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div id="calendar" style="padding-bottom:30px;"></div>
                        <?php
                        $qry = "select * from cp_events_calendar where ";


                        if (!empty($filter) && $filter != 'null') {
                            $qry .= " (";
                            $filterArr = explode(",", $filter);
                            $i = 0;
                            foreach ($filterArr as $find) {
                                if ($i > 0) $qry .= " or";
                                $qry .= " find_in_set('{$find}', replace(level_1, ';', ','))";
                                $i++;
                            }
                            $qry .= " ) and";
                        }

                        $searchEvent = '';
                        if (!empty($_GET['searchEvent'])) {
                            $searchEvent = "%{$_GET['searchEvent']}%";
                            $qry .= " event_title like :search and";
                        }

                        $qry .= " status = 'ACTIVE'  and parent_agency_id = '{$_SESSION['parent_agency']}'";

                        $qry .= " order by event_date asc";

                        $sth = $dbh->prepare($qry);
                        $sth->bindParam(':search', $searchEvent);
                        $sth->execute();

                        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {


                            $eventUrl = '';
                            if (!empty($f->url)) {
                                $eventUrl = '<tr><td><b>Website:</b></td><td><a href="' . $f->url . '"  class="btn btn-xs btn-success" target="_blank">Event Website</a></td></tr>';
                            }

                            $eventLevel = $_level->GetLevel1Name($f->level_1);

                            //EVENT TIME
                            if (!empty($f->event_end_time)) {
                                $eventTime = $f->event_time . ' - ' . $f->event_end_time;
                            } else if (!empty($f->event_time)) {
                                $eventTime = $f->event_time;
                            } else {
                                $eventTime = '';
                            }

                            //MailTo:
                            $mail_body = "{$f->event_title}  %0D%0A%0D%0A";
                            $mail_body .= "{$_SESSION['Level1_Label']}: " . $eventLevel . " %0D%0A%0D%0A";
                            $mail_body .= "Date: " . date('M-d-Y', strtotime($f->event_date)) . " %0D%0A";
                            $mail_body .= "Time: {$eventTime}  %0D%0A%0D%0A";
                            $mail_body .= "Address: %0D%0A {$f->event_address} %0D%0A";
                            $mail_body .= $f->event_city . " " . $f->event_state . " " . $f->event_zip . " %0D%0A %0D%0A";
                            $mail_body .= "Website: %0D%0A {$f->url} %0D%0A%0D%0A";
                            $mail_body .= "Description: %0D%0A " . strip_tags($f->event_description) . " %0D%0A%0D%0A";
                            $mail_body .= "Regards,%0D%0A {$_SESSION['user_name']}";

//                            $eventLevel = str_replace(";", ", ", $f->level_1);

                            $google_maps_link = "https://www.google.com/maps/place/" . urlencode($f->event_address . ' ' . ucfirst(strtolower($f->event_city)) . ' ' . $f->event_state . ' ' . $f->event_zip);


                            $eventAddress = '';
                            if (!empty($f->event_zip)) {
                                $eventAddress = $f->event_address . ' ' . ucfirst(strtolower($f->event_city)) . ', ' . $f->event_state . ' ' . $f->event_zip . ' ' . '<a href="' . $google_maps_link . '" class="btn btn-xs btn-warning pull-right" target="_blank">Directions</a>';
                            } else {
                                $eventAddress = $f->event_address;
                            }

                            //FILES
                            $_files = new EventFiles();
                            $eventFiles = $_files->GetFiles($f->id, 'cp_events_calendar_file_upload', 'events');
                            $files = '';
                            if (!empty($eventFiles)) {
                                $files = '<tr><td><b>Files:</b></td><td>' . $eventFiles . '</td></tr>';
                            }

                            if (strtotime($f->event_date) >= strtotime(date('Y-m-d'))) {
                                echo '<div class="panel panel-primary">
  
                                      <div class="panel-heading"><b>' . strtoupper($f->event_title) . '</b> <a href="../_lib/download_ics.php?id=' . $_core->encode($f->id) . '" class="btn btn-xs btn-default" target="_blank"><small>ICS</small> <span class="glyphicon glyphicon-calendar" aria-hidden="true" ></span></a>
                                      <a href="mailto:?subject=' . $agencyName . ' Event-' . strtoupper($f->event_title) . '&body=' . $mail_body . '" class="btn btn-xs btn-default pull-right" target="_blank"><small>Share</small> <span class="glyphicon glyphicon-envelope" aria-hidden="true" ></span></a>
                                          </div>
                                          <div class="panel-body">' . $f->event_description . '</div>
                                        <table class="table">
                                            <tr><td width="1%"><b>Date:</b></td><td>' . date('M-d-Y', strtotime($f->event_date)) . '</td></tr>
                                                <tr><td><b>Time:</b></td><td>' . $eventTime . '   '.($f->timezone ? '('.$f->timezone.')' : '').'</td></tr>
                                                    <tr><td><b>Location:</b></td><td>' . $eventAddress . ' </td></tr>
                                            <tr><td><b>' . $_SESSION['Level1_Label'] . ':</b></td><td>' . $eventLevel . '</td></tr>
                                            ' . $eventUrl . '
                                            ' . $files . '
                                        </table>  
                                        </div>';

                                echo "<hr></hr>";
                            }


                        }
                        ?>
                    </div>
                </div>

                <!--            LINKS-->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Links
                            <?php
                            if (UserAccess::ManageLevel1()) {
                                echo '<span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalLink">+ ADD LINK</button></span>';
                            }
                            ?>

                        </h3>
                    </div>
                    <div class="panel-body">
                        <?php
                        $_dash = new Dashboard();
                        echo $_dash->BuildLinksList($filter);
                        ?>
                    </div>
                </div>

                <!--            DOCUMENTS-->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Documents
                            <?php
                            if (UserAccess::ManageLevel1()) {
                                echo '<span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#addDocumentModal">+ ADD DOCUMENT</button></span>';
                            }
                            ?>

                        </h3>
                    </div>
                    <div class="panel-body">
                        <?= $_dash->BuildDocsList($filter) ?>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>


<?php $_core = new core(); ?>

<!-- Modal MESSAGE BOARD-->
<div class="modal fade" id="modalMessageBoard" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalLabel">Post To Message Board</h4>
            </div>
            <form class="form-horizontal" enctype="multipart/form-data" id="postMessage" role="form" method="post" action="../_lib/action.php?action=<?php echo $_core->encode('postMessage'); ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-6  col-xs-6">
                            <label for="title" class="control-label">Title</label>
                            <input type="input" name="title" class="form-control" id="message_title" required>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <label for="level1" class="control-label"><?= $_SESSION['Level1_Label'] ?> <span class="text-danger">*</span></label>
                            <select id="level1messagemodal" name="level_1[]" class="form-control" multiple required style="width:100%;">
                                <?= $_level->BuildLevelDropDown() ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="message">Message</label>
                            <textarea name="message"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12 attachment_wrapper">
                            <label class="control-label" for="attachments">Add File(s)</label>
                            <input id="upload_attachment_message" name="upload_attachment[]" type="file" multiple>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Calendar Events-->
<div class="modal fade" id="modalEventCalendar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalLabel">Add Event to Calendar</h4>
            </div>
            <form class="form-horizontal" enctype="multipart/form-data" id="addProjectContent" role="form" method="post" action="../_lib/action.php?action=<?php echo $_core->encode('addEvent'); ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="title" class="control-label">Event Title <span class="text-danger">*</span></label>
                            <input type="input" name="event_title" class="form-control" id="event_title" required>
                        </div>

                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label for="level1" class="control-label"><?= $_SESSION['Level1_Label'] ?> <span class="text-danger">*</span></label>
                            <select id="eventmodal" name="level_1[]" class="form-control" multiple required style="width:100%;">
                                <?= $_level->BuildLevelDropDown() ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label for="address" class="control-label">Location or Address</label>
                            <input type="input" name="event_address" class="form-control" id="event_address">
                        </div>

                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 col-xs-4">
                            <label class="control-label" for="city">City </label>
                            <input type="input" name="event_city" class="form-control" id="event_city">
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <label for="state" class="control-label">State </label>
                            <select name="event_state" class="form-control" id="event_state">
                                <?= $_db->buildDropState() ?>
                            </select>
                        </div>
                        <div class="col-sm-4 col-xs-4">
                            <label class="control-label" for="zip">Zipcode </label>
                            <input type="input" name="event_zip" class="form-control" id="event_zip">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 col-xs-4">
                            <label class="control-label" for="event_date">Event Date</label>
                            <input type='text' name="event_date" class="form-control" id='datePickerStart' placeholder="mm-dd-yyyy" required>
                        </div>

                        <div class="col-sm-2 col-xs-2">
                            <label class="control-label" for="event_time">Start Time</label>
                            <select name="t1" id="hour" class="form-control">
                                <?php echo $_core->dropHour('12'); ?>
                            </select>
                        </div>

                        <div class="col-sm-2 col-xs-2">
                            <label class="control-label" for="event_time">&nbsp;</label>
                            <select name="t2" id="minute" class="form-control">
                                <?php echo $_core->dropMinute('00'); ?>
                            </select>

                        </div>
                        <div class="col-sm-2 col-xs-2">
                            <label class="control-label" for="event_time">&nbsp;</label>
                            <select name="t3" id="period" class="form-control">
                                <option value="AM">AM</option>
                                <option value="PM" selected>PM</option>
                            </select>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4 col-xs-4"></div>

                        <div class="col-sm-2 col-xs-2">
                            <label class="control-label" for="event_end_time">End Time</label>
                            <select name="t1_end" id="hour_end" class="form-control">
                                <?php echo $_core->dropHour('12'); ?>
                            </select>
                        </div>

                        <div class="col-sm-2 col-xs-2">
                            <label class="control-label" for="event_end_time">&nbsp;</label>
                            <select name="t2_end" id="minute_end" class="form-control">
                                <?php echo $_core->dropMinute('00'); ?>
                            </select>

                        </div>
                        <div class="col-sm-2 col-xs-2">
                            <label class="control-label" for="event_end_time">&nbsp;</label>
                            <select name="t3_end" id="period_end" class="form-control">
                                <option value="AM">AM</option>
                                <option value="PM" selected>PM</option>
                            </select>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3 col-xs-3">
                            <label class="control-label reoccuring-box" for="event_date">Reoccuring Event</label>
                            <input type='checkbox' name="is_reoccuring" value="1" id="event_type"  >
                        </div>


                        <div class="col-sm-4 col-xs-4 hide" id="event_end_date">
                            <label class="control-label" for="event_date">Event End Date<span class="text-danger">*</span></label>
                            <input type='text' name="event_end_date" class="form-control" id='eventEndDate' placeholder="mm-dd-yyyy" required>
                        </div>


                        <div class="col-sm-5 col-xs-5 hide" id="event_reoccuring_type">
                            <label class="control-label" for="event_time">Reoccuring Type<span class="text-danger">*</span></label>
                            <select name="event_reoccuring_type" class="form-control">
                                <option value="">Choose Reoccuring Type</option>
                                <option value="Daily">Daily</option>
                                <option value="Weekly">Weekly</option>
                                <option value="Weekly Day">Weekly Day</option>
                                <option value="Monthly">Monthly</option>
                            </select>
                        </div>                        
                    </div>


                    <div class="form-group hide" id="event_reoccuring_days">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label reoccuring-box" for="event_date">Select Weekdays<span class="text-danger">*</span></label>
                            <select id="select_weekday"  name="event_reoccuring_days[]" class="form-control" multiple  style="width:100%;">
                                <option value="0">Sunday</option>
                                <option value="1">Monday</option>
                                <option value="2">Tuesday</option>
                                <option value="3">Wednesday</option>
                                <option value="4">Thursday</option>
                                <option value="5">Friday</option>
                                <option value="6">Saturday</option>
                            </select>
                        </div>         
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="timezone">Select Timezone</label>
                            <select name="timezone" id="timezone" class="form-control">
                                <?= $_db->buildTimezoneDropdown() ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="url">Website/URL</label>
                            <input type="url" name="url" class="form-control" id="event_url" placeholder="http://">
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="description">Short Description</label>
                            <textarea name="event_description"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12 attachment_wrapper">
                            <label class="control-label" for="attachments">Add File(s)</label>
                            <input id="upload_attachment" name="upload_attachment[]" type="file" multiple>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="addEvent">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalLink" tabindex="-1" role="dialog" aria-labelledby="myModalLinkLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalLinkLabel">Add Link</h4>
            </div>
            <form class="form-horizontal" id="addLink" role="form" method="post" action="../_lib/action.php?action=<?php echo $_core->encode('AddLink'); ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-6  col-xs-6">
                            <label for="title" class="control-label">Title <span class="text-danger">*</span></label>
                            <input type="input" name="title" class="form-control" id="link_title" required>

                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <label for="url" class="control-label">URL <span class="text-danger">*</span></label>
                            <input type="url" name="url" class="form-control" id="url" placeholder="http://" required>
                            <div id="linkError" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-xs-6">
                            <label for="level1" class="control-label"><?= $_SESSION['Level1_Label'] ?> <span class="text-danger">*</span></label>
                            <select id="level1linkmodal" name="level_1[]" class="form-control" multiple required style="width:100%;">
                                <?= $_level->BuildLevelDropDown() ?>
                            </select>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                            <label class="control-label" for="description">Description</label>
                            <input type="input" name="description" class="form-control" id="description">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Document-->
<div class="modal fade" id="addDocumentModal" tabindex="-1" role="dialog" aria-labelledby="myModalDocumentLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalDocumentLabel">Add Document</h4>
            </div>
            <form class="form-horizontal" enctype="multipart/form-data" id="addDocumentForm" role="form" method="post" action="../_lib/action.php?action=<?php echo $_core->encode('AddDocument'); ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label for="title" class="control-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="doc_title" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="level1" class="control-label"><?= $_SESSION['Level1_Label'] ?> <span class="text-danger">*</span></label>
                            <select id="level1Doc" name="level_1[]" class="form-control" multiple required style="width:100%;">
                                <?= $_level->BuildLevelDropDown() ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="description" class="control-label">Description</label>
                            <input type="text" name="description" id="description" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label class="control-label" for="add_doc">Add Document</label>
                            <input id="upload_docs" name="upload_docs" type="file">
                            <small class="text-danger">* Allowed File Extensions: .pdf, .doc, .docx, .xls, .xlsx, .txt, .zip, .ppt, .pptx</small>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function () {
        $("#level1Doc").select2({
            dropdownParent: $('#addDocumentModal .modal-content'),
            theme: "bootstrap"
        });

        $('#upload_docs').fileinput({
            showPreview: false,
            allowedFileExtensions: ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'zip', 'ppt', 'pptx']
        });
    })
</script>

<div class="modal fade" id="calInfoModal" tabindex="-1" role="dialog" aria-labelledby="myCalInfoModal">
    <div class="modal-dialog" role="document" style="width:50%;">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="eventTitle"></h4>
            </div>
            <div class="modal-body">
                <b>Description:</b>
                <div id="eventDescription"></div>

                <b>Where:</b>
                <div id="eventAddress"></div>
                <br>

                <b>When:</b>
                <div id="eventDate"></div>
                <br>

                <div id="eventUrl"></div>
                <br>

                <div id="eventFiles"></div>
                <br>

                <div id="eventEmail"></div>
                <br>

                <div id="eventICS"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet"/>

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<script type="text/javascript" src="../js/fileinput.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
<script type="text/javascript" src="../js/tinymce.min.js"></script>
<script type="text/javascript" src="../js/fileUpload.js"></script>

<script>
    $(function () {
        $('#modalEventCalendar').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        });

        $("#url, #event_url, #mceu_61-inp").change(function () {
            if (!/^https*:\/\//.test(this.value)) {
                this.value = "http://" + this.value;
            }
        });

        $('[data-toggle="tooltip"]').tooltip();

        $("#level1filter").select2({
            theme: "bootstrap"
        });

        $("#eventmodal").select2({
            dropdownParent: $('#modalEventCalendar .modal-content'),
            theme: "bootstrap"
        });

        $("#level1messagemodal").select2({
            dropdownParent: $('#modalMessageBoard .modal-content'),
            theme: "bootstrap"
        });

        $("#level1linkmodal").select2({
            dropdownParent: $('#modalLink .modal-content'),
            theme: "bootstrap"
        });

        


        // $('body').on('shown.bs.modal', '.modal', function (e) {
        //     $(this).find('select').each(function () {
        //         var dropdownParent = $(document.body);
        //         if ($(this).parents('.modal.in:first').length !== 0)
        //             dropdownParent = $(this).parents('.modal.in:first');
        //         $(this).select2({
        //             dropdownParent: dropdownParent,
        //             theme: "bootstrap"
        //         });
        //
        //     });
        //     e.preventDefault();
        // });

        tinymce.init({
            selector: 'textarea',
            menubar: false,
            branding: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code'
            ],
            toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
            default_link_target: "_blank",
            target_list: false,
            link_assume_external_targets: true
        });

        $(document).on('focusin', function (e) {
            var target = $(e.target);
            if (target.closest(".mce-window").length || target.closest(".tox-dialog").length) {
                e.stopImmediatePropagation();
                target = null;
            }
        });

        $('#datePickerStart').datepicker({
            dateFormat: "mm-dd-yy"
        });

        $("#link_title").keyup(function () {
            var title = $("#link_title").val();
            $("#link_title").val(ucwords(title));
        });

        $("#event_title").keyup(function () {
            var title = $("#event_title").val();
            $("#event_title").val(ucwords(title));
        });

        $("#doc_title").keyup(function () {
            var title = $("#doc_title").val();
            $("#doc_title").val(ucwords(title));
        });

        $("#message_title").keyup(function () {
            var title = $("#message_title").val();
            $("#message_title").val(ucwords(title));
        });

        $("#level1filter").on('change', function () {
            $val = $("#level1filter").val();
            if ($val == 'null') {
            }
            window.location.href = "&filter=" + $val;
        })

        $("#eventSearchBtn").on("click", function() {
            var eventSearchVal = $("#eventSearch").val();
            window.location.href = '<?= (!empty($_GET['filter'])) ? "&filter={$_GET['filter']}" : "" ?>&searchEvent=' + eventSearchVal;
        });


        $('#calendar').on('shown.bs.collapse', function () {
            $('#calendar').fullCalendar('today');
        });

        //CAL
        var calendar = $('#calendar').fullCalendar({
            selectable: true,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listMonth'
            },
            events: '../_lib/ajax.php?action=LoadCalInfo<?= (!empty($_GET['filter'])) ? "&filter={$_GET['filter']}" : "" ?><?= (!empty($_GET['searchEvent'])) ? "&searchEvent={$_GET['searchEvent']}" : "" ?>',
            eventClick: function (event, jsEvent, view) {
                $('#eventTitle').html(event.title);
                $('#eventDescription').html(event.description);
                $('#eventAddress').html(event.address);
                $('#eventDate').html(event.eventWhen);
                $('#eventUrl').html(event.eventUrl);
                $('#eventFiles').html(event.eventFiles);
                $('#eventICS').html(event.ics);
                $('#eventEmail').html(event.email);
                $('#calInfoModal').modal('show');
            },

        });

        var $searchEventVal = "<?= (!empty($_GET['searchEvent'])) ? $_GET['searchEvent'] : '' ?>";
        if ($searchEventVal != '')
        {
            $("#eventSearch").val($searchEventVal);
        }


        jQuery('#event_type').change(function(){
            if(jQuery(this).prop('checked')===true){
               jQuery('#event_reoccuring_type,#event_end_date').removeClass('hide');
               jQuery('select[name ="event_reoccuring_type"],#eventEndDate').prop('required',true);
            }
            else{
                jQuery('#event_reoccuring_type,#event_end_date').addClass('hide');
                jQuery('select[name ="event_reoccuring_type"],#eventEndDate').prop('required',false);  
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