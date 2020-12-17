<!DOCTYPE html>
<html lang="en">
<head>
    <title>DRIMS Community Portal</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <link rel="icon" href="../drims_icon.ico"/>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!--<link rel="stylesheet" href="../css/dataTables.bootstrap.min.css">-->
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/fileinput.min.css" media="all" type="text/css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <script src="../js/jquery-1.11.1.min.js"></script>
    <!--    <script src="../js/jquery-3.5.0.min.js"></script>-->
    <script src="../js/_myLib.js"></script>

    <style>
        .dataTables_wrapper .dataTables_length {
            float: right;
        }

        .dataTables_wrapper .dataTables_info {
            float: right;
        }

        .dataTables_wrapper .dataTables_filter {
            float: left;
            text-align: left;
        }

        #loading-img {
            position: fixed;
            background: url(images/image.gif) center center no-repeat;
            display: block;
            height: 100%;
            width: 100%;
            z-index: 20;
        }

        .overlay {
            background: #a9a9a9;
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            opacity: 0.5;
        }
    </style>
</head>
<body>
<!-- Fixed navbar -->

<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?php
            $_agency = new agency($_SESSION['parent_agency']);
            if ($_agency->agency_logo_exists($_SESSION['parent_agency']) >= 1) {
                echo '<img src="' . $_agency->get_agency_logo($_SESSION['parent_agency']) . '" style="max-width:100px; padding-top:5px"">';
            } else {
                echo '<img src="images/DRIMS_logo_dark_bg.png" style="max-width:100px; padding-top:5px">';
            }
            ?>

        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="index">Home</a></li>
                <li><a href="agency_directory">Organization Directory</a></li>

                <?php
                //TEAMS
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

                if ($sth->rowCount() > 0) {

                    echo '<li class="dropdown">';
                    echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">My Team<span class="caret"></span></a>';
                    echo '<ul class="dropdown-menu" role="menu">';

                    while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
                        echo '<li><a href="my_teams?tid=' . $_core->encode($f->team_id) . '">' . $f->team_name . '</a></li>';
                    }

                    echo '</ul>';
                    echo '</li>';

                } else {
                    echo '<li><a href="my_teams">My Team</a></li>';
                }
                ?>



                <?php
                $_core = new core();


                //ORG ADMINS ONLY
                $sendBroadcast = UserAccess::ManageLevel1() ? '<li><a href="send_broadcast">Send Broadcast</a></li>' : '';
                $editMyOrg = (UserAccess::ManageMyOrg($_SESSION['agency_id']) || UserAccess::ManageLevel2()) ? '<li role="separator" class="divider"></li><li><a href="agency_summary?id=' . $_core->encode($_SESSION['agency_id']) . '">Edit My Organization</a></li>' : '';

                echo $sendBroadcast;
                echo $editMyOrg;

                if (UserAccess::ManageLevel1()) {

                    $level2Users = UserAccess::ManageLevel1() ? '<li><a href="edit_level12_users">Users</a></li>' : '';
                    $addAgency = UserAccess::ManageLevel1() ? '<li><a href="add_agency">Add Org</a></li>' : '';
                    $editLvl1List = UserAccess::ManageLevel1() ? '<li><a href="edit_lvl1_list">' . $_SESSION['Level1_Label'] . ' List</a></li>' : '';
                    $editPartnerTypes = UserAccess::ManageLevel1() ? '<li><a href="edit_partner_types">Partner Types</a></li>' : '';
                    $editContactTypes = UserAccess::ManageLevel1() ? '<li><a href="edit_contact_types">Contact Types</a></li>' : '';
                    $editContactLicenseType = UserAccess::ManageLevel1() ? '<li><a href="edit_contact_license_type">Contact License Types</a></li>' : '';
                    $services = UserAccess::ManageLevel1() ? '<li><a href="services_view2">Services Resources Training</a></li>' : '';
                    $teams = UserAccess::ManageLevel1() ? '<li><a href="teams_directory_admin">Teams</a></li>' : '';
                    $reports = UserAccess::ManageLevel1() ? '<li><a href="org_reports">Reports</a></li>' : '';
                    $contactUs = UserAccess::ManageLevel1() ? '<li><a href="#" data-toggle="modal" data-target="#modalContactUs">Set Contact Us</a></li>' : '';

                    $separator = '<li role="separator" class="divider"></li>';

                    echo '
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin Panel<span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        ' . $level2Users . '
                                        ' . $addAgency . '
                                        ' . $teams . '
                                        ' . $contactUs . '
                                        ' . $separator . '
                                        ' . $editContactTypes . '
                                        ' . $editContactLicenseType . '
                                        ' . $editLvl1List . '
                                        ' . $editPartnerTypes . '
                                        ' . $services . '
                                        ' . $separator . '
                                        ' . $reports . '
                                        ' . $separator . '
                                        <li><a href="messageboard_adminlist">Edit Message Board</a></li>
                                        <li><a href="calendar_adminlist">Edit Calendar</a></li>
                                        <li><a href="edit_dashboard_links">Edit Links</a></li>
                                        <li><a href="edit_dashboard_documents">Edit Documents</a></li>
                                        
                                    </ul>
                                </li>
                            ';
                }

                $setContactData = $_agency->SetContactUs();

                $contactOn = '';
                if ($setContactData['turn_on'] === 'YES') {
                    echo '<li><a href="mailto:' . $setContactData['contact_us_email'] . '" target="_blank">Contact Us</a></li>';
                    $contactOn = ($setContactData['turn_on'] === 'YES') ? 'checked' : '';
                }
                ?>

                <?php 

                    $accessData = $_agency->getUserAccess($_SESSION['user_id']);
                    $upper_data = '';
                    $lower_data = '';

                    foreach($accessData as $u_access){
                        $u_id = $u_access['user_id'];
                        $o_id = $u_access['cp_org_id'];
                        $default_org_id = $u_access['default_org_id'];
                        $community_portal_access = $u_access['community_portal'];
                        $case_management_access = $u_access['case_management'];
                        $org_community_portal = $u_access['org_community_portal'];
                        $org_case_management = $u_access['org_case_management'];
                        $org_name = $u_access['org_name'];
                        $encoded_org_id = $_core->encode($o_id);

                        // Root CMS URL
                        $root_cms_url = ROOT_CMS_URL."_lib/oaction.php?action=autologin&uid=$u_id";

                        if($default_org_id == $o_id){
                            // set default org_id in the session
                            $_SESSION['orgID'] = $default_org_id;
                            if($org_case_management){
                                $upper_data = '<li><a class="dropdown-item" href="'.$root_cms_url.'">'.$org_name.' (Case Management)</a><li><hr/>';
                            }
                        }else{
                            if($org_community_portal){
                                $lower_data .= '<li><a class="dropdown-item access-login"  redirect-url="'.ROOT_URL.'directory" attr-oid="'.$encoded_org_id.'" href="javascript:void(0)"> '.$org_name.' (Community Portal)</a><li>';
                            }
                            if($org_case_management){
                                $lower_data .= '<li><a class="dropdown-item access-login"  redirect-url="'.$root_cms_url.'" attr-oid="'.$encoded_org_id.'" href="javascript:void(0)">'.$org_name.' (Case Management) </a><li>';
                            }
                            //$lower_data .= '<br/>';
                        }
                    }
                
                ?>

                <?php if($upper_data || $lower_data){ ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" id="access_management" data-toggle="dropdown">Access Management<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                                <?php 
                                    echo $upper_data;
                                    echo  $lower_data;
                                ?>
                        </ul>
                    </li>
                <?php } ?>

            </ul>


            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> Welcome <?= $_SESSION['user_name']; ?> </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="editmyprofile">My Profile</a></li>
                        <?php
                        if ($_SESSION['cms_access']) {
                            $current_user_id = $_SESSION['userID'];
                            $cmsurl = ROOT_CMS_URL."_lib/oaction.php?action=autologin&uid=$current_user_id";
                            echo '
                            <li class="nav-item ml-3">
                                <a class="nav-link" href="' . $cmsurl . ' ">Case Management</a>
                            </li>';
                        }
                        ?>
                        <li><a href="../_lib/action.php?action=logout">Sign out</a></li>
                    </ul>
                </li>
            </ul>

        </div><!--/.nav-collapse -->
    </div>
</div>

<!-- Modal MESSAGE BOARD-->
<div class="modal fade" id="modalContactUs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger" id="myModalLabel">Set Contact Us</h4>
            </div>
            <form class="form-horizontal" id="contactUsForm" role="form" method="post" action="../_lib/action.php?action=<?= $_core->encode('SetContactUs'); ?>">
                <div class="modal-body">

                    <div class="form-group">
                        <div class="col-sm-12  col-xs-12">
                            <label for="title" class="control-label">Email Address</label>
                            <input type="input" name="contact_us_email" class="form-control" id="contact_us" value="<?= $setContactData['contact_us_email'] ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="turn_on" value="YES" <?= $contactOn ?>> Turn On
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

<div class="container-fluid" style="margin-top:50px; margin-bottom:10px;">
    <div class="row">
        <div class="col">
            <?php
            if ($_agency->agency_logo_exists($_SESSION['parent_agency']) >= 1) {
                echo '<a href="https://drims.org" target="_blank" alt="Click Here To Learn More About DRIMS"><img src="images/DRIMS_powered_by_LOGO_2_transparent_bg.gif" style="max-width:100px; padding-left:15px;"></a>';
            }
            ?>
        </div>
    </div>
</div>

<!-- SUCCESS ALERT -->
<div class="bg-overlay-saved" id="bgSaved" style="display:none;position:fixed;">
    <div class="alert alert-success text-center" role="alert">
        SAVED!
    </div>
</div> 

<script>
    var sti = '<?php
                    if (isset($_GET['e'])) {
                        echo $_GET['e'];
                    }
                ?>';
</script>

<div class="container-fluid">

