<?php
$_core = new core();

$teamName = 'Team';
$admin = '';
$teamEmails = '';

if (!empty($_GET['tid'])) {
    $_team = new Teams($_core->decode($_core->gpGet('tid')));
    $teamName = $_team->GetTeamName();
    $admin = $_team->TeamMemberAdmin();

    //FOR Send Team Email button
    $data = $_team->GetMemberEmailsForNotification();
    $emails = '';
    foreach ($data as $v) {
        $emails .= $v['email'] . ',';
    }
    $teamEmails = rtrim($emails, ",");
}
?>

<style>
    td {
        padding: 10px;
    }
</style>

<div class="col-md-12">
    <ul class="nav nav-pills">
        <?php
        $_db = new db();

        $qry = "select
                ctm.team_id
                ,ctm.role
                ,ct.team_name
                ,ct.parent_agency_id
                from cp_team_members ctm join cp_teams ct on ctm.team_id = ct.id
                where ctm.user_id = :uid and ct.parent_agency_id = :pid";

        $dbh = $_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':uid' => $_SESSION['user_id'], ':pid' => $_SESSION['parent_agency']]);

        if ($sth->rowCount() <= 0) {
            echo '<div class="alert alert-danger" role="alert">You are not affiliated with any Teams at this time.</div>';
        }

        //        if ($sth->rowCount() > 0) {
        //
        //            while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
        //                echo '<li role="presentation">';
        //                echo '<a href="my_teams?tid=' . $_core->encode($f->team_id) . '">' . $f->team_name . '</a>';
        //                echo '</li>';
        //            }
        //
        //        } else {
        //            echo '<div class="alert alert-danger" role="alert">You are not affiliated with any Teams at this time.</div>';
        //        }
        ?>
    </ul>
</div>

<div class="col-sm-12 col-xs-12">
    <h2 style="padding-left:15px;"><?= $teamName ?> - Dashboard
        <a href="mailto:<?= $teamEmails ?>?Subject=Message To <?= $teamName ?> Members" type="button" class="btn btn-default" aria-label="Left Align" target="_blank">
            Send Team Email <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
        </a>
        <button type="button" class="btn btn-default" data-toggle="collapse" href="#collapseShowTeamMembers">Team Members</button>
    </h2>
</div>

<div class="col-sm-12 collapse" id="collapseShowTeamMembers">
    <div class="container-fluid">
        <?= $_team->ShowTeamMembers() ?>
    </div>
</div>

<div class="col-md-12">
    <table width="100%">
        <tr>
            <td width="60%" valign="top">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Message Board
                            <?php
                            if ($admin) {
                                echo '<a href="team_messageboard_list?tid=' . $_core->gpGet('tid') . '" class="btn btn-xs btn-success">EDIT</a>';
                                echo '<span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalMessageBoard">+ POST</button></span>';
                            }
                            ?>

                        </h3>
                    </div>
                    <div class="panel-body" id="messagePanel" style="max-height: 700px;overflow-y: scroll;">
                        <?php
                        if (!empty($_GET['tid'])) {
                            $_team->BuildTeamMessageBoard();
                        }
                        ?>
                    </div>
                </div>
            </td>

            <td valign="top">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Events Calendar
                            <?php
                            if ($admin) {
                                echo '<a href="edit_team_event?tid=' . $_core->gpGet('tid') . '" class="btn btn-xs btn-success" style="color:#fff;">EDIT</a>';
                                echo '<span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalEventCalendar">+ ADD EVENT</button></span>';
                            }
                            ?>

                        </h3>
                    </div>
                    <div class="panel-body" style="max-height: 500px;overflow-y: scroll;">
                        <div id="calendar" style="padding-bottom:30px;"></div>
                        <?php

                        //TODO MOVE THIS TO TEAM CLASS
                        $teamId = $_core->decode($_core->gpGet('tid'));

                        $qry = "select * from cp_team_events_calendar where ";
                        $qry .= " status = 'ACTIVE' and team_id = :teamId";
                        $qry .= " order by event_date asc limit 20";

                        $sth = $dbh->prepare($qry);
                        $sth->execute([':teamId' => $teamId]);

                        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

                            $eventAddress = '';

                            $mail_body = "{$f->event_title}  %0D%0A%0D%0A";
                            $mail_body .= "Date: " . date('M-d-Y', strtotime($f->event_date)) . " %0D%0A";
                            $mail_body .= "Time: {$f->event_time}  %0D%0A%0D%0A";
                            $mail_body .= "Address: %0D%0A {$f->event_address} %0D%0A";
                            $mail_body .= $f->event_city . " " . $f->event_state . " " . $f->event_zip . " %0D%0A %0D%0A";
                            $mail_body .= "Regards,%0D%0A {$_SESSION['user_name']}";


                            $google_maps_link = "https://www.google.com/maps/place/" . urlencode($f->event_address . ' ' . ucfirst(strtolower($f->event_city)) . ' ' . $f->event_state . ' ' . $f->event_zip);

                            if (!empty($f->event_zip)) {
                                $eventAddress = $f->event_address . ' ' . ucfirst(strtolower($f->event_city)) . ', ' . $f->event_state . ' ' . $f->event_zip . ' ' . '<a href="' . $google_maps_link . '" class="btn btn-xs btn-warning pull-right" target="_blank">Directions</a>';
                            } else {
                                $eventAddress = $f->event_address;
                            }

                            $eventUrl = '';
                            if (!empty($f->url)) {
                                $eventUrl = '<tr><td><b>Website:</b></td><td><a href="' . $f->url . '"  class="btn btn-xs btn-success" target="_blank">Event Website</a></td></tr>';
                            }

                            $_files = new TeamEventFiles();
                            $eventFiles = $_files->GetFiles($f->id, 'cp_team_events_calendar_file_upload', 'events');
                            $files = '';
                            if (!empty($eventFiles)) {
                                $files = '<tr><td><b>Files:</b></td><td>' . $eventFiles . '</td></tr>';
                            }

                            //EVENT TIME
                            if (!empty($f->event_end_time)) {
                                $eventTime = $f->event_time . ' - ' . $f->event_end_time;
                            } else if (!empty($f->event_time)) {
                                $eventTime = $f->event_time;
                            } else {
                                $eventTime = '';
                            }


                            if (strtotime($f->event_date) >= strtotime(date('Y-m-d'))) {
                                echo '<div class="panel panel-default">
  
                      <div class="panel-heading"><b>' . strtoupper($f->event_title) . '</b> <a href="../_lib/download_ics.php?id=' . $_core->encode($f->id) . '&loc=team" class="btn btn-xs btn-default" target="_blank"><small>ICS</small> <span class="glyphicon glyphicon-calendar" aria-hidden="true" ></span></a>
                      <a href="mailto:?subject=Event-' . strtoupper($f->event_title) . '&body=' . $mail_body . '" class="btn btn-xs btn-default pull-right" target="_blank"><small>Share</small> <span class="glyphicon glyphicon-envelope" aria-hidden="true" ></span></a>
                          </div>
                          <div class="panel-body">' . $f->event_description . '</div>
                        <table class="table">
                            <tr><td width="1%"><b>Date:</b></td><td>' . date('M-d-Y', strtotime($f->event_date)) . '</td></tr>
                            <tr><td><b>Time:</b></td><td>' . $eventTime . '   '.($f->timezone ? '('.$f->timezone.')' : '').'</td></tr>
                                    <tr><td><b>Location:</b></td><td>' . $eventAddress . ' </td></tr>
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
                            if ($admin) {
                                echo '<a href="edit_team_links?tid=' . $_core->gpGet('tid') . '" class="btn btn-xs btn-success" style="color:#fff;">EDIT</a>';
                                echo '<span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#modalLink">+ ADD LINK</button></span>';
                            }
                            ?>

                        </h3>
                    </div>
                    <div class="panel-body">
                        <?php
                        if (!empty($_GET['tid'])) {
                            $_team->BuildTeamLinks();
                        }
                        ?>
                    </div>
                </div>

                <!--            DOCUMENTS-->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            Documents
                            <?php
                            if ($admin) {
                                echo '<a href="edit_team_documents?tid=' . $_core->gpGet('tid') . '" class="btn btn-xs btn-success" style="color:#fff;">EDIT</a>';
                                echo '<span class="pull-right"><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#addDocumentModal">+ ADD DOCUMENT</button></span>';
                            }
                            ?>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <?php
                        if (!empty($_GET['tid'])) {
                            $_team->BuildTeamDocuments();
                        }
                        ?>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>

<!-- Modal MESSAGE BOARD-->
<div class="modal fade" id="modalMessageBoard" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalLabel">Post Team Message</h4>
            </div>
            <form class="form-horizontal" id="postMessage" enctype="multipart/form-data" role="form" method="post" action="../_lib/tmaction.php?action=<?= $_core->encode('PostTeamMessage'); ?>">
                <input type="hidden" name="tid" value="<?= $_core->gpGet('tid') ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="title" class="control-label">Title</label>
                            <input type="input" name="title" class="form-control" id="message_title" required>
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
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="notify_members" value="YES"><b>Notify Members</b>
                                </label>
                            </div>
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
            <form class="form-horizontal" enctype="multipart/form-data" id="addProjectContent" role="form" method="post"
                  action="../_lib/tmaction.php?action=<?php echo $_core->encode('addTeamEvent'); ?>">
                <input type="hidden" name="tid" value="<?= $_core->gpGet('tid') ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="title" class="control-label">Event Title <span class="text-danger">*</span></label>
                            <input type="input" name="event_title" class="form-control" id="event_title" required>
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
                            <!--                            <div class='input-group date' id='datePickerStart' data-date-format="YYYY-MM-DD">-->
                            <!--                                <input type='text' name="event_date" class="form-control" placeholder="YYYY-MM-DD" required>-->
                            <!--                                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>-->
                            <!--                                </span>-->
                            <!--                            </div>-->
                            <input type='text' name="event_date" class="form-control" id='datePickerStart' placeholder="mm-dd-yyyy" required>
                        </div>

                        <div class="col-sm-2 col-xs-2">
                            <label class="control-label" for="event_time">Time</label>
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
                            <label class="control-label" for="url">Website</label>
                            <input type="url" name="url" class="form-control" id="event_url" placeholder="http://">
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

                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="notify_members" value="YES"> <b>Notify Members</b>
                                </label>
                            </div>
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

<div class="modal fade" id="modalLink" tabindex="-1" role="dialog" aria-labelledby="myModalLinkLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalLinkLabel">Add Team Link</h4>
            </div>
            <form class="form-horizontal" id="addLink" role="form" method="post" action="../_lib/tmaction.php?action=<?= $_core->encode('AddTeamLink'); ?>">
                <input type="hidden" name="tid" value="<?= $_core->gpGet('tid') ?>">
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
                        <div class="col-sm-12 col-xs-12">
                            <label class="control-label" for="description">Description</label>
                            <input type="input" name="description" class="form-control" id="description">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="notify_members" value="YES"> <b>Notify Members</b>
                                </label>
                            </div>
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
                <h4 class="modal-title text-danger" id="myModalDocumentLabel">Add Team Document</h4>
            </div>
            <form class="form-horizontal" enctype="multipart/form-data" id="addDocumentForm" role="form" method="post" action="../_lib/tmaction.php?action=<?= $_core->encode('AddTeamDocument'); ?>">
                <input type="hidden" name="tid" value="<?= $_core->gpGet('tid') ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12">
                            <label for="title" class="control-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="doc_title" class="form-control" required>
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
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="notify_members" value="YES"> <b>Notify Members</b>
                                </label>
                            </div>
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

<!--<script type="text/javascript" src="../js/Moment.js"></script>-->
<!--<script type="text/javascript" src="../js/bootstrap-datetimepicker.min.js"></script>-->


<script type="text/javascript" src="../js/tinymce.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css"/>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
<script type="text/javascript" src="../js/fileinput.min.js"></script>
<script type="text/javascript" src="../js/fileUpload.js"></script>

<script>
    $(function () {

        $('#close-members').on('click', function() {
            $('#collapseShowTeamMembers').collapse('hide');
        });

        $("#url, #event_url").change(function () {
            if (!/^https*:\/\//.test(this.value)) {
                this.value = "http://" + this.value;
            }
        });

        $('#upload_docs').fileinput({
            showPreview: false,
            allowedFileExtensions: ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'zip', 'ppt', 'pptx']
        });

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

        $('[data-toggle="tooltip"]').tooltip();

        $("#link_title").keyup(function () {
            var title = $("#link_title").val();
            $("#link_title").val(ucwords(title));
        });

        $("#doc_title").keyup(function () {
            var title = $("#doc_title").val();
            $("#doc_title").val(ucwords(title));
        });

        $("#message_title").keyup(function () {
            var title = $("#message_title").val();
            $("#message_title").val(ucwords(title));
        });

        $("#event_title").keyup(function () {
            var title = $("#event_title").val();
            $("#event_title").val(ucwords(title));
        });

        $('#datePickerStart').datepicker({
            dateFormat: "mm-dd-yy"
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
                right: 'month,agendaWeek,agendaDay'
            },
            events: '../_lib/tmajax.php?action=LoadTeamCalInfo&tid=<?= $_GET['tid'] ?>',
            eventClick: function (event, jsEvent, view) {
                $('#eventTitle').html(event.title);
                $('#eventDescription').html(event.description);
                $('#eventAddress').html(event.address);
                $('#eventDate').html(event.eventWhen);
                $('#eventUrl').html(event.eventUrl);
                $('#eventFiles').html(event.eventFiles);
                $('#eventICS').html(event.ics);
                $('#calInfoModal').modal('show');
            },

        });

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