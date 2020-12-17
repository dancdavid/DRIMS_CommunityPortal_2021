<?php
$_core = new core();
$_db = new db();

$agency_id = $_core->decode($_core->gpGet('id'));
$_agency = new agency($agency_id);

$agencyData = $_agency->get_agency($agency_id);
$agencyContact = $_agency->get_agency_contact($agency_id);
//$agencyAltContact = $_agency->get_agency_contact($agency_id, 'ALTERNATE');
$agencyServices = $_agency->get_agency_services($agency_id);
?>
<div class="col-sm-12 col-xs-12">

    <?php if(isset($_GET['m']) && $_GET['m']){ ?>
        <div class="alert alert-success">
        <strong>Success!</strong> <?= $_GET['m'] ?>
    </div>
    <?php } ?>
    
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?php echo $agencyData['agency_name']; ?>
                <span class="pull-right"><a href="print_agency?id=<?php echo $_core->gpGet('id'); ?>" target="_blank" class="btn btn-xs btn-danger">Print <span class="glyphicon glyphicon-print"
                                                                                                                                                                aria-hidden="true"></span></a></span>
            </h3>
        </div>
        <div class="panel-body">
            <div class="col-sm-4 col-xs-4">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Info</h3>
                    </div>
                    <div class="panel-body" style="max-height: 700px;overflow-y: scroll;">
                        <!--<div class="col-sm-12 col-xs-12">-->
                        <div class="col-sm-12 col-xs-12">
                            <address>
                                <?php
                                $google_maps_link = "https://www.google.com/maps/place/" . urlencode($agencyData['agency_address'] . ' ' . $agencyData['agency_city'] . ' ' . $agencyData['agency_state'] . ' ' . $agencyData['agency_zipcode']);

                                echo '<strong>' . $agencyData['agency_name'] . '</strong><br>';
                                echo $agencyData['agency_address'] . '<br>';
                                echo $agencyData['agency_city'] . ' ' . $agencyData['agency_state'] . ' ' . $agencyData['agency_zipcode'] . '<br>';

                                echo 'P: ' . $agencyData['agency_telephone'] . '<br>';
                                echo 'F: ' . $agencyData['agency_fax'] . '<br>';
                                echo '<a href="' . $google_maps_link . '" class="btn btn-xs btn-warning" target="blank">Get Directions</a><br>';


                                if ($_agency->CheckIfServicesExist()) {
                                    echo '<br><button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#orgServicesModal">Services Resources Training</button>';
                                }

                                ?>
                            </address>

                            <address>
                                <?php
                                if (!empty($agencyData['partner_type'])) {
                                    $_partnerType = new PartnerType();
                                    $pt = explode(";", $agencyData['partner_type']);

                                    echo '<strong>Partner Type</strong><br>';
                                    echo '<ul>';
                                    foreach ($pt as $val) {
                                        echo '<li>' . $_partnerType->GetPartnerTypeName($val) . '</li>';
                                    }
                                    echo '</ul>';
                                }
                                ?>
                            </address>

                            <address>
                                <?php
                                if (!empty($agencyData['agency_url'])) {
                                    echo '<strong>Website</strong><br>';
                                    echo '<a href="' . $agencyData['agency_url'] . '" target="blank">' . $agencyData['agency_url'] . '</a>';
                                }
                                ?>
                            </address>

                            <address>

                                <?php
                                if (!empty($agencyData['description'])) {
                                    echo '<strong>About Us</strong><br>';
                                    echo $agencyData['description'];
                                }
                                ?>
                            </address>


                        </div>

                        <div class="col-sm-5 col-xs-5">

                            <?php
                            if ($_agency->agency_logo_exists($agency_id) >= 1) {
                                echo '<img src="' . $_agency->get_agency_logo($agency_id) . '" class="img-responsive"><br><br>';
                            }
                            ?>
                            <!--                            <img data-src="holder.js/150x150" class="img-responsive">-->

                        </div>

                        <div class="col-xs-12 col-sm-12">
                            <?php
                            if (UserAccess::ManageLevel1() || UserAccess::ManageMyOrg($agency_id)) {
                                echo ' <a href="add_org_services?id=' . $_GET['id'] . '" class="btn btn-xs btn-primary" style="color:#ffffff">ADD/EDIT SERVICES RESOURCES TRAINING</a>';

                                echo "<div class='pull-right'><a href='edit_agency_info?id={$_core->gpGet('id')}' class='btn btn-xs btn-danger'>EDIT</a></div>";
                            }
                            ?>
                        </div>

                        <!--</div>-->
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-xs-3">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Contacts</h3>
                    </div>
                    <div class="panel-body" style="max-height: 700px;overflow-y: scroll;">

                        <?php
                        foreach ($agencyContact as $col) {

                            $ext = (!empty($col['extension'])) ? ' Ext:' . $col['extension'] : '';

                            echo '<b>' . $col['first_name'] . ' ' . $col['last_name'] . '</b><br>';;
                            echo "Phone: " . $col['phone'] . '&nbsp; &nbsp; ' . $ext . '<br>';
                            echo "Cell: " . $col['alt_phone'] . '<br>';
                            echo "Email: <a href='mailto:{$col['email']}'>" . $col['email'] . "</a><br>";
                            echo "Title: " . $col['title'] . "<br>";

                            //Contact Types
                            $_contactType = new ContactType();
                            $contact_type = $_contactType->GetContactTypeName($col['contact_type']);

                            if (!empty($contact_type)) {
                                echo '<small><b>Contact Type:</b>';
//                                echo '<br>'. str_replace(",", "<br>", $contact_type);

                                echo '<ul>';
                                $ct = explode(',', $contact_type);
                                foreach ($ct as $ctVal) {
                                    echo '<li>' . $ctVal . '</li>';
                                }
                                echo '</ul>';

                                echo '</small>';
                            }


                            //Contact Licnese Types
                            $_licenseType = new ContactLicenseType();
                            $contactLicenseType = $_licenseType->GetContactLicenseName($col['contact_license_type']);

                            if (!empty($contactLicenseType)) {
                                echo '<small><b>Contact License Type:</b>';
//                                echo '<br>' . str_replace(',', '<br>',$contactLicenseType);

                                echo '<ul>';
                                $clt = explode(',', $contactLicenseType);
                                foreach ($clt as $cltVal) {
                                    echo '<li>' . $cltVal . '</li>';
                                }
                                echo '</ul>';

                                echo '</small>';
                            }


                            if (UserAccess::ManageLevel1() || UserAccess::ManageMyOrg($agency_id)) {
                                echo "<small><b>Status:</b> {$col['status']}</small><br>";
                                echo "<div class='pull-right'><a href='edit_agency_contacts?id={$_core->gpGet('id')}&uid={$_core->encode($col['id'])}' class='btn btn-xs btn-danger'>EDIT</a></div>";
                            }

                            echo "<br><hr></hr>";
                        }

                        if (UserAccess::ManageLevel1() || UserAccess::ManageMyOrg($agency_id)) {
                             $orgId = $_agency->getOrgId($agency_id);
                             echo '<a href="add_agencycontacts?id=' . $_core->gpGet('id') . '&oid='.($orgId ? $_core->encode($orgId) : '').'&stat=newcontact" class="btn btn-xs btn-primary pull-right">ADD MORE CONTACTS <span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>';
                        }
                        ?>

                    </div>
                </div>
            </div>
            <style>
                th, td {
                    padding: 7px;
                }
            </style>
            <div class="col-sm-5 col-xs-5">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Locations</h3>
                    </div>
                    <div class="panel-body" style="max-height: 700px;overflow-y: scroll;">
                        <address>
                            <?php
                            $agencyLocation = $_agency->GetAgencyLocations($agency_id);

                            foreach ($agencyLocation as $al) {
                                $google_maps_link = "https://www.google.com/maps/place/" . urlencode($al['address'] . ' ' . $al['city'] . ' ' . $al['state'] . ' ' . $al['zip_code']);

                                echo '<table width="100%"><tr><td width="50%">';
                                echo '<strong>' . $al['location_name'] . '</strong><br>';
                                echo $al['address'] . '<br>';
                                echo $al['city'] . ' ' . $al['state'] . ' ' . $al['zip_code'] . '<br>';

                                echo 'P: ' . $al['phone'] . '<br>';
                                echo 'F: ' . $al['fax'] . '<br>';
                                echo '<a href="' . $google_maps_link . '" class="btn btn-xs btn-warning" target="blank">Get Directions</a><br>';

                                $qry = "select * from cp_agency_location_services where location_id = :lid";
                                $dbh = $_db->initDB();
                                $sth = $dbh->prepare($qry);
                                $sth->execute([':lid' => $al['id']]);

                                if ($sth->rowCount() > 0) {
                                    echo '<br><button type="button" data-id="' . $_core->encode($al['id']) . '" class="btn btn-xs btn-success locServices">Services Resources Training</button><br>';
                                }

                                echo '</td>';

                                //LOCATION
                                echo '<td valign="top">';
                                echo '<b>' . $_SESSION['Level1_Label'] . '</b><br>';

                                $_level = new Level1();
                                $level1 = $_level->GetLevel1Name($al['level_1']);

                                echo str_replace(",", "<br>", $level1);
//                                $levelArr = explode(";", $al['level_1']);
//                                echo '<ul>';
//                                foreach ($levelArr as $lvl1) {
//                                    echo '<li>' . $_level->GetLevelSingleName($lvl1) . '</li>';
//                                }
//                                echo '</ul>';

                                echo '</td></tr>';

                                if (UserAccess::ManageLevel1() || UserAccess::ManageMyOrg($agency_id)) {
                                    echo '<tr>';
                                    echo '<td>';
                                    echo '<a href="add_org_services_locations?id=' . $_GET['id'] . '&lid='.$_core->encode($al['id']).'" class="btn btn-xs btn-primary" style="color:#ffffff">ADD/EDIT SERVICES RESOURCES TRAINING</a>';
                                    echo '</td>';
                                    echo '<td>';
                                    echo '<a href="editagencylocation?id=' . $_core->encode($al['agency_id']) . '&lid=' . $_core->encode($al['id']) . '" class="btn btn-xs btn-danger pull-right">EDIT</a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }

                                echo '<tr><td colspan="2"><hr></hr></td></tr>';
                                echo '</table>';

                            }

                            //IN_ACTIVE LOCATIONS
                            if (UserAccess::ManageLevel1() || UserAccess::ManageMyOrg($agency_id)) {
                                $agencyLocation = $_agency->GetAgencyInActiveLocations($agency_id);

                                foreach ($agencyLocation as $al) {
                                    $google_maps_link = "https://www.google.com/maps/place/" . urlencode($al['address'] . ' ' . $al['city'] . ' ' . $al['state'] . ' ' . $al['zip_code']);

                                    echo '<table width="100%">';
                                    echo '<tr><td width="50%"><span class="text-danger"><b>IN ACTIVE LOCATION</b></span></td></tr>';
                                    echo '<tr><td>';
                                    echo '<strong>' . $al['location_name'] . '</strong><br>';
                                    echo $al['address'] . '<br>';
                                    echo $al['city'] . ' ' . $al['state'] . ' ' . $al['zip_code'] . '<br>';

                                    echo 'P: ' . $al['phone'] . '<br>';
                                    echo 'F: ' . $al['fax'] . '<br>';
                                    echo '<a href="' . $google_maps_link . '" class="btn btn-xs btn-warning" target="blank">Get Directions</a><br>';

                                    $qry = "select * from cp_agency_location_services where location_id = :lid";
                                    $dbh = $_db->initDB();
                                    $sth = $dbh->prepare($qry);
                                    $sth->execute([':lid' => $al['id']]);

                                    if ($sth->rowCount() > 0) {
                                        echo '<br><button type="button" data-id="' . $_core->encode($al['id']) . '" class="btn btn-xs btn-success locServices">Services Resources Training</button><br>';
                                    }

                                    echo '</td>';

                                    //LOCATION
                                    echo '<td valign="top">';
                                    echo '<b>' . $_SESSION['Level1_Label'] . '</b><br>';

                                    $_level = new Level1();
                                    $level1 = $_level->GetLevel1Name($al['level_1']);

                                    echo str_replace(",", "<br>", $level1);

                                    echo '</td></tr>';

                                    echo '<tr>';
                                    echo '<td colspan="2">';
                                    echo '<a href="editagencylocation?id=' . $_core->encode($al['agency_id']) . '&lid=' . $_core->encode($al['id']) . '" class="btn btn-xs btn-danger pull-right">EDIT</a>';
                                    echo '</td>';
                                    echo '</tr>';

                                    echo '<tr><td colspan="2"><hr></hr></td></tr>';
                                    echo '</table>';
                                }
                            }


                            if (UserAccess::ManageLevel1() || UserAccess::ManageMyOrg($agency_id)) {


                                echo "<div class='pull-right'><a href='addagencylocation&id={$_core->gpGet('id')}' class='btn btn-xs btn-primary'>+ ADD LOCATION</a></div>";
                            }


                            ?>
                        </address>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="locationServicesModal" tabindex="-1" role="dialog" aria-labelledby="locationServicesLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="locationServicesLabel">Location Services Resources Training</h4>
            </div>
            <div class="modal-body" style="max-height: 700px;overflow-y: scroll;">
                <table class="table" id="listAvailableLocationServices" width="100%">
                    <thead>
                    <tr>
                        <th>CATEGORY</th>
                        <th>TYPE</th>
                        <th>ITEM</th>
<!--                        <th>SUB-ITEM</th>-->
<!--                        <th>SUB-ITEM 2</th>-->
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="orgServicesModal" tabindex="-1" role="dialog" aria-labelledby="orgServicesLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="locationServicesLabel">Services Resources Training</h4>
            </div>
            <div class="modal-body" style="max-height: 700px;overflow-y: scroll;">
                <table class="table" id="listAvailableServices" width="100%">
                    <thead>
                    <tr>
                        <th>CATEGORY</th>
                        <th>TYPE</th>
                        <th>ITEM</th>
<!--                        <th>SUB-ITEM</th>-->
<!--                        <th>SUB-ITEM 2</th>-->
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>

<script>
    $(function () {
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});

        $("#listAvailableServices").DataTable({
            "bProcessing": false,
            "sDom": '<"top"f>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/agencyajax.php?action=GetAvailableServices&aid=" + '<?= $_GET['id'] ?>',
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search:"
            },
            "bDeferRender": true,
            "iDeferLoading": 200,
            "iDisplayLength": -1,
            "aaSorting": [[0, 'asc']]
            // ,
            // "aoColumnDefs": [
            //     { "aTargets":[3], "bSortable":false }
            // ]
        });


        $('.locServices').on('click', function () {

            var $lid = $(this).data('id');

            $("#listAvailableLocationServices").DataTable().destroy();

            var locServices = $("#listAvailableLocationServices").DataTable({
                "bProcessing": false,
                "sDom": '<"top"f>rt<"clear">',
                "bStateSave": false,
                "cache": false,
                "sAjaxSource": "../_lib/agencyajax.php?action=AvailableLocationServices&lid=" + $lid,
                "oLanguage": {
                    "sZeroRecords": "No records to display",
                    "sSearch": "Search:"
                },
                "bDeferRender": true,
                "iDeferLoading": 200,
                "iDisplayLength": -1,
                "aaSorting": [[0, 'asc']]
            });

            // locServices.ajax.reload();
            $('#locationServicesModal').modal('show');

        });


        $('#locationServicesModal').on('hidden.bs.modal', function () {
            $("#listAvailableLocationServices").DataTable().destroy();
        });

    });
</script>