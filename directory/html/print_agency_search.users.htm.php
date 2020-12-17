<?php
$_core = new core();
$_db = new db();
$dbh = $_db->initDB();

//if (!empty($_core->gpGet('id'))) {
//    $agency_id = $_core->decode($_core->gpGet('id'));
//    $qry = "select * from cp_directory_agency where agency_id = '$agency_id' and parent_agency = '{$_SESSION['parent_agency']}'";
//} else {
////    $_db->CreateSearchTempTable();
//
////    $qry = "select * from cp_search_orgs_and_locations
////    $qry = "select distinct(agency_id)
////            ,agency_name
////            ,agency_address
////            ,agency_city
////            ,agency_state
////            ,agency_zipcode
////            ,agency_telephone
////            from cp_print_friendly_search where ";
//
//    $qry = "select * from cp_print_friendly_search where ";
//
//    if (!empty($_GET['search']))
//    {
//        $qry .= " match(agency_name
//        ,level_1
//        ,contacts
//        ,agency_city
//        ,agency_state
//        ,agency_zipcode
//        ,agency_telephone
//        ,partner_type
//        ,org_type
//        ,resource_type
//        ,item_name) against ('{$_GET['search']}') and";
//    }
//
//    $qry .= " status = 'ACTIVE'
//    and parent_agency = '{$_SESSION['parent_agency']}'
//    and agency_id <> '{$_SESSION['parent_agency']}'
//    order by agency_id";
////    $qry = "select * from cp_directory_agency where status = 'ACTIVE' and parent_agency = '{$_SESSION['parent_agency']}' order by agency_name";
//}
//
////echo $qry; exit;
///
//
//if (!empty($_GET['search']))
//{
//    $agencyIdEncArr = explode(";", $_GET['search']);
//    foreach ($agencyIdEncArr as $agencyIdEnc)
//    {
//        $agencyId = $_core->decode($agencyIdEncArr);
//    }
//}
//
//$_agency = new agency();


//$sth = $dbh->prepare($qry);
//$sth->execute();

//while ($f = $sth->fetch(PDO::FETCH_ASSOC)) :

if (!empty($_GET['print'])) :

    $agencyIdEncArr = explode(";", $_GET['print']);

    foreach ($agencyIdEncArr as $agencyIdEnc) :
        $agencyId = $_core->decode($agencyIdEnc);

        $_agency = new agency($agencyId);
        $agencyData = $_agency->get_agency($agencyId);

        $qry = "select * from cp_search_orgs_and_locations where
                id = :agencyId
                and status = 'ACTIVE'
                and parent_agency = '{$_SESSION['parent_agency']}'
                and agency_id <> '{$_SESSION['parent_agency']}'
                order by agency_id";

        $sth = $dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId]);

        while ($f = $sth->fetch(PDO::FETCH_ASSOC)) :

    ?>

    <style>
        @page :left {
            margin-left: 0cm;
            margin-right: 0cm;
        }

        @page :right {
            margin-left: 0cm;
            margin-right: 0cm;
        }
    </style>

    <img src="images/DRIMS_logo_dark_bg.png" style="max-width:100px !important; padding-top:5px">

<!--    <div class="panel panel-primary">-->
<!--        <div class="panel-heading">-->
<!--            <h3 class="panel-title">--><?php //echo $f['agency_name'] ?><!--</h3>-->
<!--        </div>-->
<!--        <div class="panel-body">-->
    <h3><?php echo $f['agency_name'] ?></h3>
            <div class="col-sm-12 col-xs-12">
                <div class="col-sm-12 col-xs-12">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">Info</h3>
                        </div>
                        <div class="panel-body">
                            <address>
                                <?php

                                echo '<strong>' . $f['agency_name'] . '</strong><br>';
                                echo $f['agency_address'] . '<br>';
                                echo $f['agency_city'] . ' ' . $f['agency_state'] . ' ' . $f['agency_zipcode'] . '<br>';

                                echo 'P: ' . $f['agency_telephone'] . '<br>';
                                echo 'F: ' . $agencyData['agency_fax'] . '<br>';
                                ?>
                            </address>
                            <address>
                                <?php
                                if (!empty($f['partner_type']))
                                {
                                    $_partnerType = new PartnerType();
                                    $pt = explode(";", $f['partner_type']);

                                    echo '<strong>Partner Type</strong><br>';
                                    echo '<ul>';
                                    foreach( $pt as $val )
                                    {
                                        echo '<li>'.$_partnerType->GetPartnerTypeName($val).'</li>';
                                    }
                                    echo '</ul>';
                                }
                                ?>
                            </address>

                            <address>
                                <?php
                                if (!empty($agencyData['agency_url']))
                                {
                                    echo '<strong>Website</strong><br>';
                                    echo '<a href="' . $agencyData['agency_url'] . '" target="blank">' . $agencyData['agency_url'] . '</a>';
                                }
                                ?>
                            </address>

                            <address>

                                <?php
                                if (!empty($agencyData['description']))
                                {
                                    echo '<strong>About Us</strong><br>';
                                    echo $agencyData['description'];
                                }
                                ?>
                            </address>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-sm-12 col-xs-12" style="page-break-before: always;">
                <h3><?php echo $f['agency_name'] ?></h3>
                <div class="col-sm-6 col-xs-6">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">Contacts</h3>
                        </div>
                        <div class="panel-body">
                            <?php
//                            $_agency = new agency();
                            $agencyContact = $_agency->get_agency_contact($f['agency_id']);

                            $_contactType = new ContactType();
                            foreach ($agencyContact as $col) {
                                $ext = (!empty($col['extension'])) ? ' Ext:' . $col['extension'] : '';

                                echo '<b>' . $col['first_name'] . ' ' . $col['last_name'] . '</b><br>';;
                                echo "Phone: " . $col['phone'] . '&nbsp; &nbsp; ' . $ext . '<br>';
                                echo "Cell: " . $col['alt_phone'] . '<br>';
                                echo "Email: {$col['email']} <br>";

//                                $contact_type = str_replace(";", "<br>", $col['contact_type']);
                                $contact_type = $_contactType->GetContactTypeName($col['contact_type']);
                                echo "<b><small>" . $contact_type . "</small></b><br>";

                                //Contact Types
                                $_contactType = new ContactType();
                                $contact_type = $_contactType->GetContactTypeName($col['contact_type']);

                                if (!empty($contact_type))
                                {
                                    echo '<small><b>Contact Type:</b>';
//                                echo '<br>'. str_replace(",", "<br>", $contact_type);

                                    echo '<ul>';
                                    $ct = explode(',',$contact_type);
                                    foreach($ct as $ctVal)
                                    {
                                        echo '<li>' . $ctVal . '</li>';
                                    }
                                    echo '</ul>';

                                    echo '</small>';
                                }



                                //Contact Licnese Types
                                $_licenseType = new ContactLicenseType();
                                $contactLicenseType = $_licenseType->GetContactLicenseName($col['contact_license_type']);

                                if (!empty($contactLicenseType))
                                {
                                    echo '<small><b>Contact License Type:</b>';
//                                echo '<br>' . str_replace(',', '<br>',$contactLicenseType);

                                    echo '<ul>';
                                    $clt = explode(',',$contactLicenseType);
                                    foreach($clt as $cltVal)
                                    {
                                        echo '<li>' . $cltVal . '</li>';
                                    }
                                    echo '</ul>';

                                    echo '</small>';
                                }
                                echo '<hr>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xs-6">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">Locations</h3>
                        </div>
                        <div class="panel-body">
                            <address>
                                <?php
                                $agencyLocation = $_agency->GetAgencyLocations($f['agency_id']);

                                $_level = new Level1();
                                foreach ($agencyLocation as $al) {

                                    echo '<table width="100%"><tr><td valign="top" width="50%">';
                                    echo '<strong>' . $al['location_name'] . '</strong><br>';
                                    echo $al['address'] . '<br>';
                                    echo $al['city'] . ' ' . $al['state'] . ' ' . $al['zip_code'] . '<br>';

                                    echo 'P: ' . $al['phone'] . '<br>';
                                    echo 'F: ' . $al['fax'] . '<br>';
                                    echo '</td>';

                                    //LOCATION
                                    echo '<td valign="top">';
                                    echo '<b>' . $_SESSION['Level1_Label'] . '</b><br>';

                                    echo $_level->GetLevel1Name($al['level_1']);
//                                    $levelArr = explode(";", $al['level_1']);
//                                    echo '<ul>';
//                                    foreach ($levelArr as $lvl1) {
//                                        echo '<li>' . $lvl1 . '</li>';
//                                    }
//                                    echo '</ul>';

                                    echo '</td></tr>';


                                    echo '<tr><td colspan="2"><hr></hr></td></tr>';
                                    echo '</table>';

                                }


                                ?>
                            </address>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-sm-12 col-xs-12">
                <div class="col-sm-12 col-xs-12" style="page-break-before: always;">
                    <h3><?php echo $f['agency_name'] ?></h3>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">SERVICES</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table" id="listAvailableServices">
                                <thead>
                                <tr>
                                    <th>CATEGORY</th>
                                    <th>TYPE</th>
                                    <th>ITEM</th>
<!--                                    <th>SUB-ITEM</th>-->
<!--                                    <th>SUB-ITEM 2</th>-->
<!--                                    <th>NOTE</th>-->
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $agencyIdTble = (substr($agency_id, 0, 2) === 'L-') ? preg_replace('/L-/', 'L_', $agencyId) : $agencyId;

                                $tempQry = "create temporary table SRT_TEMP_{$agencyIdTble} select * from cp_search_items where agency_id = :locId";
                                $sthTemp = $dbh->prepare($tempQry);
                                $sthTemp->execute([':locId' => $agencyId]);

                                $qrySRT = "select distinct(`service_id`), `category`, `type` from SRT_TEMP_{$agencyIdTble}";
                                $sthSRT = $dbh->prepare($qrySRT);
                                $sthSRT->execute();


                                $data = [];
                                $_service = new Services();
                                $_agency = new agency();

                                while ($fSRT = $sthSRT->fetch(PDO::FETCH_OBJ)) {

                                    //ITEM NAME
                                    $q1 = "select distinct(item_id), item from SRT_TEMP_{$agencyIdTble} where service_id = :serviceId";
                                    $s1 = $dbh->prepare($q1);
                                    $s1->execute([':serviceId' => $fSRT->service_id]);

                                    $itemName = '';
                                    while ($f1 = $s1->fetch(PDO::FETCH_OBJ)) {
                                        $customItemName = $_service->GetCustomItemName($agencyId, $f1->item_id);

                                        $itemName .= "<ul>";
                                        $itemName .= (!empty($customItemName)) ? "<li>" . $customItemName . "</li>" : "<li>" . $f1->item . "</li>";

                                        $q2 = "select distinct(sub_item_id), sub_item from SRT_TEMP_{$agencyIdTble} where item_id = :itemId";
                                        $s2 = $dbh->prepare($q2);
                                        $s2->execute([':itemId' => $f1->item_id]);

                                        //SUB ITEM
                                        while ($f2 = $s2->fetch(PDO::FETCH_OBJ)) {
                                            $itemName .= "<ul>";
                                            $itemName .= "<li>" . $f2->sub_item . "</li>";

                                            //SUBITEM 2
                                            $q3 = "select note, sub_item_2_id, sub_item_2 from SRT_TEMP_{$agencyIdTble} where sub_item_id = :siid";
                                            $s3 = $dbh->prepare($q3);
                                            $s3->execute([':siid' => $f2->sub_item_id]);

                                            while ($f3 = $s3->fetch(PDO::FETCH_OBJ)) {
                                                $itemName .= "<ul><li>";
                                                $itemName .= $f3->sub_item_2;
                                                $itemName .= (!empty($f3->note)) ? '<ul style="list-style-type: circle;"><li>'.htmlspecialchars($f3->note).'</li></ul>' : '';
                                                $itemName .= "</li></ul>";
                                            }

                                            $itemName .= "</ul>";
                                        }

                                        $itemName .= "</ul>";
                                    }


                                    echo '<tr>';
                                    echo "<td>{$fSRT->category}</td>";
                                    echo "<td>{$fSRT->type}</td>";
                                    echo "<td>{$itemName}</td>";
                                    echo '</tr>';

                                }

                                $dbh->query("drop temporary table SRT_TEMP_{$agencyIdTble}");
//                                    $qry = "select * from cp_search_items
//                                            where agency_id = :agencyId
//                                            and `status` = 'ACTIVE'
//                                            and `item_status` = 'ACTIVE'
//                                            and `sub_item_status` = 'ACTIVE'
//                                            and `sub_item_2_status` = 'ACTIVE'
//                                            ";
//                                    $sthi = $dbh->prepare($qry);
//                                    $sthi->execute([':agencyId' => $agencyId]);
//
//                                    $_service = new Services();
//
//                                    while ($fi = $sthi->fetch(PDO::FETCH_OBJ))
//                                    {
//                                        $id = (substr($agencyId, 0, 2) === 'L-') ? $_agency->GetAgencyIdByLocationId(preg_replace('/L-/','',$agencyId)) : $agencyId;
//                                        $customItemName = $_service->GetCustomItemName($id, $fi->item_id);
//                                        $itemName = (!empty($customItemName)) ? $customItemName : $f->item;
//
//                                        echo '<tr>';
//                                        echo "<td>{$fi->category}</td>";
//                                        echo "<td>{$fi->type}</td>";
//                                        echo "<td>{$itemName}</td>";
////                                        echo "<td>{$fi->item}</td>";
//                                        echo "<td>{$fi->sub_item}</td>";
//                                        echo "<td>{$fi->sub_item_2}</td>";
//                                        echo "<td>{$fi->note}</td>";
//                                        echo '</tr>';
//                                    }

                                ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
<!--        </div>-->
<!--    </div>-->

    <?php
    echo "<p style='page-break-after: always'></p>";
    endwhile;
    endforeach;
    endif;
    ?>
<script>
    function myPrint() {
        window.print();
    }

    $(function () {
        myPrint();
    });
</script>