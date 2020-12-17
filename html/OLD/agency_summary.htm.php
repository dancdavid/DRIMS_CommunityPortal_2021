<?php
$_core = new core();

$agency_id = $_core->decode($_core->gpGet('id'));

$_agency = new agency($agency_id);

$agencyData = $_agency->get_agency($agency_id);
$agencyPrimaryContact = $_agency->get_agency_contact($agency_id);
$agencyAltContact = $_agency->get_agency_contact($agency_id, 'ALTERNATE');
$agencyServices = $_agency->get_agency_services($agency_id);
?>

<div class="col-sm-12 col-xs-12" style="padding-top:130px;">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">
                <?php echo $agencyData['agency_name']; ?>
                <span class="pull-right"><button onclick="myPrint()" class="btn btn-xs btn-danger">Print <span class="glyphicon glyphicon-print" aria-hidden="true"></span></button></span>
            </h3>
        </div>
        <div class="panel-body">
            <div class="col-sm-4 col-xs-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Info</h3>
                    </div>
                    <div class="panel-body">
                        <address>
                            <?php
                            $google_maps_link = "https://www.google.com/maps/place/" . urlencode($agencyData['agency_address'] . ' ' . $agencyData['agency_city'] . ' ' . $agencyData['agency_state'] . ' ' . $agencyData['agency_zipcode']);

                            echo '<strong>' . $agencyData['agency_name'] . '</strong><br>';
                            echo $agencyData['agency_address'] . '<br>';
                            echo $agencyData['agency_city'] . ' ' . $agencyData['agency_state'] . ' ' . $agencyData['agency_zipcode'] . '<br>';

                            echo 'P: ' . $agencyData['agency_telephone'] . '<br>';
                            echo 'F: ' . $agencyData['agency_fax'] . '<br>';
                            echo '<a href="' . $google_maps_link . '" class="btn btn-xs btn-warning" target="blank">Get Directions</a><br>';
                            ?>
                        </address>
                        <address>
                            <strong>Website</strong><br>
                            <?php
                            echo '<a href="' . $agencyData['agency_url'] . '" target="blank">' . $agencyData['agency_url'] . '</a>';
                            ?>
                        </address>
                        <address>
                            <strong>Coordinates</strong><br>
                            <?php
                            echo "Latitude: {$agencyData['a_latitude']} <br>";
                            echo "Longitude: {$agencyData['a_longitude']} <br>";
                            echo "GPS: {$agencyData['agency_gps']}";
                            ?>
                        </address>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Contacts</h3>
                    </div>
                    <div class="panel-body">
                        <strong>Primary Contacts</strong><br>
                        <?php
                        foreach ($agencyPrimaryContact as $col) {
                            echo $col['contact_name'] . '<br>';
                            ;
                            echo "Phone: " . $col['contact_telephone'] . '<br>';
                            echo "Email: <a href='mailto:{$col['contact_email']}'>" . $col['contact_email'] . '</a><br><hr></hr>';
                        }
                        ?>


                        <?php
                        if (count($agencyAltContact) > 0) {
                            echo "<br><strong>Alternate Contacts</strong><br>";
                            foreach ($agencyAltContact as $col) {
                                echo $col['contact_name'] . '<br>';
                                ;
                                echo "Phone: " . $col['contact_telephone'] . '<br>';
                                echo "Email: <a href='mailto:{$col['contact_email']}'>" . $col['contact_email'] . '</a><br><hr></hr>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Services</h3>
                    </div>
                    <div class="panel-body">
                        <?php
                        echo $_agency->build_services_view();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function myPrint() {
        window.print();
    }

    $(function () {
        $('[data-toggle="popover"]').popover({
            'trigger': 'hover',
            'placement': 'right'
        });
    });
</script>