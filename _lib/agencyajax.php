<?php

/*
 * Written by Dan David
 * file to handle most AJAX calls for site
 */
session_start();

require_once(dirname(dirname(__FILE__)) . '/config/config.php');

include(ROOT . '/_lib/autoload.php');

spl_autoload_register('load_classes');

if (!isset($_GET['action'])) {
    echo "Missing Action";
    die;
}


$action = $_GET['action'];
$Action = new Action();

if (!method_exists($Action, $action)) {
    echo "Invalid Action " . $action;
    die;
}

$Action->$action();

class Action
{

    private $_core;
    private $_db;
    private $_dbh;

    public function __construct()
    {
        $this->_core = new core();
        $this->_db = new db();
        $this->_dbh = $this->_db->initDB();
    }

    public function EditAvailableServices()
    {
        $agencyIdEnc = $this->_db->gpGet('aid');
        $agencyId = $this->_db->decode($agencyIdEnc);

        $this->GetAgencyServices($agencyId, 'edit');
    }

    public function EditAvailableLocationServices()
    {
        $locationIdEnc = $this->_db->gpGet('lid');
        $locationId = $this->_db->decode($locationIdEnc);
        $this->GetAgencyServices($locationId, 'edit', true);
    }

    public function AvailableLocationServices()
    {
        $locationIdEnc = $this->_db->gpGet('lid');
        $locationId = $this->_db->decode($locationIdEnc);
//        $this->GetAgencyServices($locationId, 'ro', true);

        $tempQry = "create temporary table SRT_LOCATION_TEMP_{$locationId} select * from cp_view_agency_location_services_test where location_id = :locId";
        $sthTemp = $this->_dbh->prepare($tempQry);
        $sthTemp->execute([':locId' => $locationId]);

        $qry = "select distinct(`service_id`), `category`, `type` from SRT_LOCATION_TEMP_{$locationId}";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute();


        $data = [];
        $_service = new Services();
        $_agency = new agency();

        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $agencyId = $_agency->GetAgencyIdByLocationId($locationId);

            //ITEM NAME
            $q1 = "select distinct(item_id), item from SRT_LOCATION_TEMP_{$locationId} where service_id = :serviceId";
            $s1 = $this->_dbh->prepare($q1);
            $s1->execute([':serviceId' => $f->service_id]);

            $itemName = '';
            while ($f1 = $s1->fetch(PDO::FETCH_OBJ)) {
                $customItemName = $_service->GetCustomItemName($agencyId, $f1->item_id);

                $itemName .= "<ul>";
                $itemName .= (!empty($customItemName)) ? "<li>" . $customItemName . "</li>" : "<li>" . $f1->item . "</li>";


                $q2 = "select distinct(sub_item_id), sub_item from SRT_LOCATION_TEMP_{$locationId} where item_id = :itemId";
                $s2 = $this->_dbh->prepare($q2);
                $s2->execute([':itemId' => $f1->item_id]);

                //SUB ITEM
                while ($f2 = $s2->fetch(PDO::FETCH_OBJ)) {
                    $itemName .= "<ul>";
                    $itemName .= "<li>" . $f2->sub_item . "</li>";

                    //SUBITEM 2
                    $q3 = "select note, sub_item_2_id, sub_item_2 from SRT_LOCATION_TEMP_{$locationId} where sub_item_id = :siid";
                    $s3 = $this->_dbh->prepare($q3);
                    $s3->execute([':siid' => $f2->sub_item_id]);

                    while ($f3 = $s3->fetch(PDO::FETCH_OBJ)) {
                        $itemName .= "<ul><li>";
//                        if (!empty($f3->note)) {
//                            $itemName .= '<a href="#" data-toggle="tooltip" data-placement="left" title="' . htmlspecialchars($f3->note) . '">' . $f3->sub_item_2 . '</a>';
//                        } else {
//                            $itemName .= $f3->sub_item_2;
//                        }

                        $itemName .= $f3->sub_item_2;

                        $itemName .= (!empty($f3->note)) ? '<ul style="list-style-type: circle;"><li>'.htmlspecialchars($f3->note).'</li></ul>' : '';

                        $itemName .= "</li></ul>";

//                        $itemName .= "<ul><li>" . $f3->sub_item_2 . "</li></ul>";
                    }


                    $itemName .= "</ul>";
                }

                $itemName .= "</ul>";
            }


            $data['aaData'][] = array(
                $f->category,
                $f->type,
                $itemName
            );
        }

        $this->_dbh->query("drop temporary table SRT_LOCATION_TEMP_{$locationId}");

        echo json_encode($data);

//        $data = [];
//        $_service = new Services();
//        $_agency = new agency();
//        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
//
//            if (!empty($f->note)) {
//                $subItem2Note = '<a href="#" data-toggle="tooltip" data-placement="top" title="' . $f->note . '">' . $f->sub_item_2 . '</a>';
//            } else {
//                $subItem2Note = $f->sub_item_2;
//            }
//
//            $customItemName = $_service->GetCustomItemName($_agency->GetAgencyIdByLocationId($locationId), $f->item_id);
//            $itemName = (!empty($customItemName)) ? $customItemName : $f->item;
//
//            $data['aaData'][] = array(
//                $f->category,
//                $f->type,
////                $f->item,
//                $itemName,
//                $f->sub_item,
//                $subItem2Note
//            );
//        }
//
//        echo json_encode($data);
    }

    public function GetAvailableServices()
    {
        $agencyIdEnc = $this->_db->gpGet('aid');
        $agencyId = $this->_db->decode($agencyIdEnc);

        $tempQry = "create temporary table SRT_TEMP_{$agencyId} select * from cp_search_items where agency_id = :locId";
        $sthTemp = $this->_dbh->prepare($tempQry);
        $sthTemp->execute([':locId' => $agencyId]);

        $qry = "select distinct(`service_id`), `category`, `type` from SRT_TEMP_{$agencyId}";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute();

//        $qry = "select * from cp_search_items where agency_id = :locId";
//        $sth = $this->_dbh->prepare($qry);
//        $sth->execute([':locId' => $locationId]);

        $data = [];
        $_service = new Services();
        $_agency = new agency();

        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

//            $agencyId = (substr($locationId, 0, 2) === 'L-') ? $_agency->GetAgencyIdByLocationId(preg_replace('/L-/', '', $locationId)) : $locationId;

            //ITEM NAME
            $q1 = "select distinct(item_id), item from SRT_TEMP_{$agencyId} where service_id = :serviceId";
            $s1 = $this->_dbh->prepare($q1);
            $s1->execute([':serviceId' => $f->service_id]);

            $itemName = '';
            while ($f1 = $s1->fetch(PDO::FETCH_OBJ)) {
                $customItemName = $_service->GetCustomItemName($agencyId, $f1->item_id);

                $itemName .= "<ul>";
                $itemName .= (!empty($customItemName)) ? "<li>" . $customItemName . "</li>" : "<li>" . $f1->item . "</li>";


                $q2 = "select distinct(sub_item_id), sub_item from SRT_TEMP_{$agencyId} where item_id = :itemId";
                $s2 = $this->_dbh->prepare($q2);
                $s2->execute([':itemId' => $f1->item_id]);

                //SUB ITEM
                while ($f2 = $s2->fetch(PDO::FETCH_OBJ)) {
                    $itemName .= "<ul>";
                    $itemName .= "<li>" . $f2->sub_item . "</li>";

                    //SUBITEM 2
                    $q3 = "select note, sub_item_2_id, sub_item_2 from SRT_TEMP_{$agencyId} where sub_item_id = :siid";
                    $s3 = $this->_dbh->prepare($q3);
                    $s3->execute([':siid' => $f2->sub_item_id]);

                    while ($f3 = $s3->fetch(PDO::FETCH_OBJ)) {
                        $itemName .= "<ul><li>";
//                        if (!empty($f3->note)) {
//                            $itemName .= '<a href="#" data-toggle="tooltip" data-placement="left" title="' . htmlspecialchars($f3->note) . '">' . $f3->sub_item_2 . '</a>';
//                        } else {
//                            $itemName .= $f3->sub_item_2;
//                        }

                        $itemName .= $f3->sub_item_2;

                        $itemName .= (!empty($f3->note)) ? '<ul style="list-style-type: circle;"><li>'.htmlspecialchars($f3->note).'</li></ul>' : '';

                        $itemName .= "</li></ul>";

//                        $itemName .= "<ul><li>" . $f3->sub_item_2 . "</li></ul>";
                    }


                    $itemName .= "</ul>";
                }

                $itemName .= "</ul>";
            }


            $data['aaData'][] = array(
                $f->category,
                $f->type,
                $itemName
            );
        }

        $this->_dbh->query("drop temporary table SRT_TEMP_{$agencyId}");

        echo json_encode($data);

//        $this->GetAgencyServices($agencyId);

//        $qry = "select * from cp_view_agency_services_test where agency_id = :agencyId";
//        $sth = $this->_dbh->prepare($qry);
//        $sth->execute([':agencyId' => $agencyId]);
//
//        $data = [];
//        $_service = new Services();
//        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
//
//            if (!empty($f->note)) {
//                $subItem2Note = '<a href="#" data-toggle="tooltip" data-placement="top" title="' . htmlspecialchars($f->note) . '">' . $f->sub_item_2 . '</a>';
//            } else {
//                $subItem2Note = $f->sub_item_2;
//            }
//
//            $customItemName = $_service->GetCustomItemName($agencyId, $f->item_id);
//            $itemName = (!empty($customItemName)) ? $customItemName : $f->item;
//
//            $data['aaData'][] = array(
//                $f->category,
//                $f->type,
////                $f->item,
//                $itemName,
//                $f->sub_item,
//                $subItem2Note
//            );
//        }
//
//        echo json_encode($data);
    }

    private function GetAgencyServices($id, $view = 'ro', $location = false)
    {

        $qry = "select id as service_id, category, type, description, status 
                from cp_services where parent_agency_id = :pid and `status` = 'ACTIVE' 
                order by type";

        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':pid' => $_SESSION['parent_agency']]);


        $data = [];

        $_service = new Services();
        $_agency = new agency();

        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
            $questionaire = '';

            $sid = $this->_db->encode($f->service_id);

            //ITEMS
            $qryItem = "select id as item_id, item, status as item_status from cp_services_item where service_id = :sid and `status` = 'ACTIVE'";
            $sthItem = $dbh->prepare($qryItem);
            $sthItem->execute([':sid' => $f->service_id]);

            while ($fItem = $sthItem->fetch(PDO::FETCH_OBJ)) {
                $iid = $this->_db->encode($fItem->item_id);

                $questionaire .= "<ul>"; //ITEM ul

                //ADDED INPUT FIELD FOR ORGS TO HAVE A CUSTOM ITEM NAME
//                $questionaire .= "<li><b>" . strtoupper($fItem->item) . "</b></li>";

                $agencyId = ($location) ? $_agency->GetAgencyIdByLocationId($id) : $id;

                $customItemName = $_service->GetCustomItemName($agencyId, $fItem->item_id);
                $itemName = (!empty($customItemName)) ? $customItemName : $fItem->item;

                $questionaire .= '<li>';
                $questionaire .= "<b>" . strtoupper($itemName) . "</b>";
                $questionaire .= ($location) ? "" : "<input type='text' name='custom_item_name[{$this->_db->encode($fItem->item_id)}]' class='form-control input-sm' style='width:60%;' placeholder='Rename " . strtoupper($itemName) . "'>";
                $questionaire .= '</li>';

                //SUBITEMS QRY
                $qrySubItem = "select id as sub_item_id, sub_item, status as sub_item_status from cp_services_sub_item where item_id = :iid and `status` = 'ACTIVE'";
                $sthSubItem = $dbh->prepare($qrySubItem);
                $sthSubItem->execute([':iid' => $fItem->item_id]);

                while ($fSubItem = $sthSubItem->fetch(PDO::FETCH_OBJ)) {
                    $siid = $this->_db->encode($fSubItem->sub_item_id);

                    //SUBITEMS HTM
                    $questionaire .= "<ul style='padding:10px;'>"; //SUB ITEM ul
                    $questionaire .= "<li>" . strtoupper($fSubItem->sub_item) . " - " . "<small>( <i>{$itemName}</i> )</small>" . "</li>";


                    //SUBITEMS2
                    $qrySubItem2 = "select id as sub_item_2_id, sub_item_2, status as sub_item_2_status from cp_services_sub_item_2 where sub_item_id = :siid2 and `status` = 'ACTIVE'";
                    $sthSubItem2 = $dbh->prepare($qrySubItem2);
                    $sthSubItem2->execute([':siid2' => $fSubItem->sub_item_id]);

                    $questionaire .= "<ul>"; //SUB ITEM 2 UL

                    while ($fSubItem2 = $sthSubItem2->fetch(PDO::FETCH_OBJ)) {
                        $siid2 = $this->_db->encode($fSubItem2->sub_item_2_id);

                        if (!$location) {
                            $selectedArr = $this->GetAgencySelectedServices($id);
                            $note = $this->GetAgencySubItemNotes($id, $fSubItem2->sub_item_2_id);
                        } else {
                            $selectedArr = $this->GetAgencyLocationSelectedServices($id);
                            $note = $this->GetAgencyLocationSubItemNotes($id, $fSubItem2->sub_item_2_id);
                        }

                        $checked = (in_array($fSubItem2->sub_item_2_id, $selectedArr)) ? 'checked' : '';
                        $disable = ($view === 'ro') ? 'disabled' : '';

//                        $questionaire .= "<li>";
                        $questionaire .= '<div class="checkbox">';
                        $questionaire .= '<label>';
                        $questionaire .= '<input type="checkbox" name="services[]" ' . $disable . ' value="' . $siid2 . '" ' . $checked . ' >';


                        $questionaire .= '<b>' . ($fSubItem2->sub_item_2) . '</b>';


//                        if ($view === 'edit') {
//                            $questionaire .= '<b>' . ($fSubItem2->sub_item_2) . '</b>';
//                        } else {
//                            if (!empty($note)) {
//                                $questionaire .= "<a href='#' data-toggle='tooltip' data-placement='top' title='{$note}'>" . $fSubItem2->sub_item_2 . "</a>";
//                            } else {
//                                $questionaire .= ($fSubItem2->sub_item_2);
//                            }
//                        }
                        $questionaire .= '</div>';

                        $questionaire .= '<div style="padding-left:20px;"><input type="text" name="note[' . $siid2 . ']" class="form-control input-sm" placeholder="Note" style="width:70%" value="' . $note . '"></div>';


                        //                        $questionaire .= "</li>";
                    }

                    $questionaire .= "</ul>"; //END SUB ITEM 2 ul
                    $questionaire .= "</ul>"; //end SUB ITEM li

                }

                $questionaire .= "</ul>"; //end ITEM ul

            }

            $data['aaData'][] = array(
                $f->category,
                "<a href='#' data-toggle='tooltip' data-placement='top' title='{$f->description}' >" . strtoupper($f->type) . "</a>",
                $questionaire
            );
        }

        echo json_encode($data);

    }

    public function GetAgencyServices2()
    {
        $dbh = $this->_db->initDB();
        $_service = new Services();
        $_agency = new agency();

        $questionaire = '';

//        $sid = $this->_db->encode($f->service_id);

        $id = $this->_db->decode($this->_db->gpGet('id'));
        $sid = $this->_db->decode($this->_db->gpGet('sid'));
        $location = false;
        $view = 'edit';

        //ITEMS
        $qryItem = "select id as item_id, item, status as item_status from cp_services_item where service_id = :sid and `status` = 'ACTIVE'";
        $sthItem = $dbh->prepare($qryItem);
        $sthItem->execute([':sid' => $sid]);

        while ($fItem = $sthItem->fetch(PDO::FETCH_OBJ)) {
            $iid = $this->_db->encode($fItem->item_id);

            $questionaire .= "<ul>"; //ITEM ul

            //ADDED INPUT FIELD FOR ORGS TO HAVE A CUSTOM ITEM NAME
//                $questionaire .= "<li><b>" . strtoupper($fItem->item) . "</b></li>";

            $agencyId = ($location) ? $_agency->GetAgencyIdByLocationId($id) : $id;

            $customItemName = $_service->GetCustomItemName($agencyId, $fItem->item_id);
            $itemName = (!empty($customItemName)) ? htmlentities($customItemName) : $fItem->item;

            $itemValue = (!empty($customItemName)) ? 'value="' . htmlentities($customItemName) . '"' : '';

            $questionaire .= '<li>';
            $questionaire .= "<b>" . strtoupper($itemName) . "</b>";
            $questionaire .= ($location) ? "" : "<input type='text' name='custom_item_name[{$this->_db->encode($fItem->item_id)}]' class='form-control input-sm' style='width:60%;' placeholder='Rename " . strtoupper($itemName) . "' " . $itemValue . ">";

            //ADDED FOR COLLAPSE OF QUESTIONAIRE
            $questionaire .= '<button class="btn btn-xs btn-success quest-view" type="button" data-toggle="collapse" data-target="#questionnaire' . $fItem->item_id . '">Answer Questionniare</button>';

            $questionaire .= '</li>';

            //DIV FOR COLLAPSE
            $questionaire .= "<div class='collapse' id='questionnaire{$fItem->item_id}'>";

            //SUBITEMS QRY
            $qrySubItem = "select id as sub_item_id, sub_item, status as sub_item_status from cp_services_sub_item where item_id = :iid and `status` = 'ACTIVE'";
            $sthSubItem = $dbh->prepare($qrySubItem);
            $sthSubItem->execute([':iid' => $fItem->item_id]);


            while ($fSubItem = $sthSubItem->fetch(PDO::FETCH_OBJ)) {
                $siid = $this->_db->encode($fSubItem->sub_item_id);

                //SUBITEMS HTM
                $questionaire .= "<ul style='padding:10px;'>"; //SUB ITEM ul
                $questionaire .= "<li>" . strtoupper($fSubItem->sub_item) . " - " . "<small>( <i>{$itemName}</i> )</small>" . "</li>";


                //SUBITEMS2
                $qrySubItem2 = "select id as sub_item_2_id, sub_item_2, status as sub_item_2_status from cp_services_sub_item_2 where sub_item_id = :siid2 and `status` = 'ACTIVE'";
                $sthSubItem2 = $dbh->prepare($qrySubItem2);
                $sthSubItem2->execute([':siid2' => $fSubItem->sub_item_id]);

                $questionaire .= "<ul>"; //SUB ITEM 2 UL

                while ($fSubItem2 = $sthSubItem2->fetch(PDO::FETCH_OBJ)) {
                    $siid2 = $this->_db->encode($fSubItem2->sub_item_2_id);

                    if (!$location) {
                        $selectedArr = $this->GetAgencySelectedServices($id);
                        $note = $this->GetAgencySubItemNotes($id, $fSubItem2->sub_item_2_id);
                    } else {
                        $selectedArr = $this->GetAgencyLocationSelectedServices($id);
                        $note = $this->GetAgencyLocationSubItemNotes($id, $fSubItem2->sub_item_2_id);
                    }

                    $checked = (in_array($fSubItem2->sub_item_2_id, $selectedArr)) ? 'checked' : '';
                    $disable = ($view === 'ro') ? 'disabled' : '';

//                        $questionaire .= "<li>";
                    $questionaire .= '<div class="checkbox">';
                    $questionaire .= '<label>';
                    $questionaire .= '<input type="checkbox" name="services[]" ' . $disable . ' value="' . $siid2 . '" ' . $checked . ' >';


                    $questionaire .= '<b>' . ($fSubItem2->sub_item_2) . '</b>';


//                        if ($view === 'edit') {
//                            $questionaire .= '<b>' . ($fSubItem2->sub_item_2) . '</b>';
//                        } else {
//                            if (!empty($note)) {
//                                $questionaire .= "<a href='#' data-toggle='tooltip' data-placement='top' title='{$note}'>" . $fSubItem2->sub_item_2 . "</a>";
//                            } else {
//                                $questionaire .= ($fSubItem2->sub_item_2);
//                            }
//                        }
                    $questionaire .= '</div>';

                    $questionaire .= '<div style="padding-left:20px;"><input type="text" name="note[' . $siid2 . ']" class="form-control input-sm" placeholder="Note" style="width:70%" value="' . $note . '"></div>';


                    //                        $questionaire .= "</li>";
                }

                $questionaire .= "</ul>"; //END SUB ITEM 2 ul
                $questionaire .= "</ul>"; //end SUB ITEM li

            }

            $questionaire .= "</div>";//CLOSE DIV FOR COLLAPSE

            $questionaire .= "</ul>"; //end ITEM ul

        }

        echo $questionaire;
    }

    public function GetAgencyLocationServices2()
    {
        $dbh = $this->_db->initDB();
        $_service = new Services();
        $_agency = new agency();

        $questionaire = '';

//        $sid = $this->_db->encode($f->service_id);

        $id = $this->_db->decode($this->_db->gpGet('id'));
        $lid = $this->_db->decode($this->_db->gpGet('lid'));
        $sid = $this->_db->decode($this->_db->gpGet('sid'));
        $location = true;
        $view = 'edit';

        //ITEMS
        $qryItem = "select id as item_id, item, status as item_status from cp_services_item where service_id = :sid and `status` = 'ACTIVE'";
        $sthItem = $dbh->prepare($qryItem);
        $sthItem->execute([':sid' => $sid]);

        while ($fItem = $sthItem->fetch(PDO::FETCH_OBJ)) {
            $iid = $this->_db->encode($fItem->item_id);

            $questionaire .= "<ul>"; //ITEM ul

            //ADDED INPUT FIELD FOR ORGS TO HAVE A CUSTOM ITEM NAME
//                $questionaire .= "<li><b>" . strtoupper($fItem->item) . "</b></li>";

            $agencyId = $_agency->GetAgencyIdByLocationId($lid);

            $customItemName = $_service->GetCustomItemName($agencyId, $fItem->item_id);
//            $itemName = (!empty($customItemName)) ? $customItemName : $fItem->item;
//
//            $itemValue = (!empty($customItemName)) ? "value='{$customItemName}'" : '';

            $itemName = (!empty($customItemName)) ? htmlentities($customItemName) : $fItem->item;

            $itemValue = (!empty($customItemName)) ? 'value="' . htmlentities($customItemName) . '"' : '';

            $questionaire .= '<li>';
            $questionaire .= "<b>" . strtoupper($itemName) . "</b>";
            $questionaire .= ($location) ? "" : "<input type='text' name='custom_item_name[{$this->_db->encode($fItem->item_id)}]' class='form-control input-sm' style='width:60%;' placeholder='Rename " . strtoupper($itemName) . "' " . $itemValue . ">";

            //ADDED for Questions collapsed
            $questionaire .= '<br><button class="btn btn-xs btn-success" type="button" data-toggle="collapse" data-target="#questionnaire' . $fItem->item_id . '">Answer Questionniare</button>';

            $questionaire .= '</li>';

            //DIV FOR COLLAPSE
            $questionaire .= "<div class='collapse' id='questionnaire{$fItem->item_id}'>";

            //SUBITEMS QRY
            $qrySubItem = "select id as sub_item_id, sub_item, status as sub_item_status from cp_services_sub_item where item_id = :iid and `status` = 'ACTIVE'";
            $sthSubItem = $dbh->prepare($qrySubItem);
            $sthSubItem->execute([':iid' => $fItem->item_id]);

            while ($fSubItem = $sthSubItem->fetch(PDO::FETCH_OBJ)) {
                $siid = $this->_db->encode($fSubItem->sub_item_id);

                //SUBITEMS HTM
                $questionaire .= "<ul style='padding:10px;'>"; //SUB ITEM ul
                $questionaire .= "<li>" . strtoupper($fSubItem->sub_item) . " - " . "<small>( <i>{$itemName}</i> )</small>" . "</li>";


                //SUBITEMS2
                $qrySubItem2 = "select id as sub_item_2_id, sub_item_2, status as sub_item_2_status from cp_services_sub_item_2 where sub_item_id = :siid2 and `status` = 'ACTIVE'";
                $sthSubItem2 = $dbh->prepare($qrySubItem2);
                $sthSubItem2->execute([':siid2' => $fSubItem->sub_item_id]);

                $questionaire .= "<ul>"; //SUB ITEM 2 UL

                while ($fSubItem2 = $sthSubItem2->fetch(PDO::FETCH_OBJ)) {
                    $siid2 = $this->_db->encode($fSubItem2->sub_item_2_id);


                    $selectedArr = $this->GetAgencyLocationSelectedServices($lid);
                    $note = $this->GetAgencyLocationSubItemNotes($lid, $fSubItem2->sub_item_2_id);

                    $checked = (in_array($fSubItem2->sub_item_2_id, $selectedArr)) ? 'checked' : '';
                    $disable = ($view === 'ro') ? 'disabled' : '';

//                        $questionaire .= "<li>";
                    $questionaire .= '<div class="checkbox">';
                    $questionaire .= '<label>';
                    $questionaire .= '<input type="checkbox" name="services[]" ' . $disable . ' value="' . $siid2 . '" ' . $checked . ' >';


                    $questionaire .= '<b>' . ($fSubItem2->sub_item_2) . '</b>';


//                        if ($view === 'edit') {
//                            $questionaire .= '<b>' . ($fSubItem2->sub_item_2) . '</b>';
//                        } else {
//                            if (!empty($note)) {
//                                $questionaire .= "<a href='#' data-toggle='tooltip' data-placement='top' title='{$note}'>" . $fSubItem2->sub_item_2 . "</a>";
//                            } else {
//                                $questionaire .= ($fSubItem2->sub_item_2);
//                            }
//                        }
                    $questionaire .= '</div>';

                    $questionaire .= '<div style="padding-left:20px;"><input type="text" name="note[' . $siid2 . ']" class="form-control input-sm" placeholder="Note" style="width:70%" value="' . $note . '"></div>';


                    //                        $questionaire .= "</li>";
                }

                $questionaire .= "</ul>"; //END SUB ITEM 2 ul
                $questionaire .= "</ul>"; //end SUB ITEM li

            }

            $questionaire .= "</div>"; //Close div for Collapse
            $questionaire .= "</ul>"; //end ITEM ul

        }

        echo $questionaire;
    }


    private function GetAgencySelectedServices($agencyId)
    {
        $qry = "select * from cp_agency_services where agency_id = :agencyId";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId]);

        $data = [];
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
            $data[] = $f->sub_item_2_id;
        }

        return $data;
    }

    private function GetAgencySubItemNotes($agencyId, $subItem2Id)
    {
        $qry = "select note from cp_agency_services where agency_id = :agencyId and sub_item_2_id = :subItemId";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId, ':subItemId' => $subItem2Id]);

        return $sth->fetchColumn();
    }

    private function GetAgencyLocationSelectedServices($agencyId)
    {
        $qry = "select * from cp_agency_location_services where location_id = :agencyId";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId]);

        $data = [];
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
            $data[] = $f->sub_item_2_id;
        }

        return $data;
    }

    private function GetAgencyLocationSubItemNotes($agencyId, $subItemId)
    {
        $qry = "select note from cp_agency_location_services where location_id = :agencyId and sub_item_2_id = :subItemId";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId, ':subItemId' => $subItemId]);

        return $sth->fetchColumn();
    }

    public function SearchSRTModal()
    {
        $locationIdEnc = $this->_db->gpGet('lid');
        $locationId = $this->_db->decode($locationIdEnc);
//        $this->GetAgencyServices($locationId, 'ro', true);

//        $tempQry = "create temporary table SRT_TEMP_{$locationId} select * from cp_search_items where agency_id = :locId";
//        $sthTemp = $this->_dbh->prepare($tempQry);
//        $sthTemp->execute([':locId' => $locationId]);

//        $qry = "select distinct(`service_id`), `category`, `type` from SRT_TEMP_{$locationId}";
        $qry = "select distinct(`service_id`), `category`, `type` from cp_search_items where agency_id = :locId";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':locId' => $locationId]);


        $data = [];
        $_service = new Services();
        $_agency = new agency();

        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $agencyId = (substr($locationId, 0, 2) === 'L-') ? $_agency->GetAgencyIdByLocationId(preg_replace('/L-/', '', $locationId)) : $locationId;

            //ITEM NAME
            $q1 = "select distinct(item_id), item from cp_search_items where service_id = :serviceId and agency_id = :locId";
            $s1 = $this->_dbh->prepare($q1);
            $s1->execute([':serviceId' => $f->service_id, ':locId' => $locationId]);

            $itemName = '';
            while ($f1 = $s1->fetch(PDO::FETCH_OBJ)) {
                $customItemName = $_service->GetCustomItemName($agencyId, $f1->item_id);

                $itemName .= "<ul>";
                $itemName .= (!empty($customItemName)) ? "<li>" . $customItemName . "</li>" : "<li>" . $f1->item . "</li>";


                $q2 = "select distinct(sub_item_id), sub_item from cp_search_items where item_id = :itemId and agency_id = :locId";
                $s2 = $this->_dbh->prepare($q2);
                $s2->execute([':itemId' => $f1->item_id, ':locId' => $locationId]);

                //SUB ITEM
                while ($f2 = $s2->fetch(PDO::FETCH_OBJ)) {
                    $itemName .= "<ul>";
                    $itemName .= "<li>" . $f2->sub_item . "</li>";

                    //SUBITEM 2
                    $q3 = "select note, sub_item_2_id, sub_item_2 from cp_search_items where sub_item_id = :siid and agency_id = :locId";
                    $s3 = $this->_dbh->prepare($q3);
                    $s3->execute([':siid' => $f2->sub_item_id, ':locId' => $locationId]);

                    while ($f3 = $s3->fetch(PDO::FETCH_OBJ)) {
                        $itemName .= "<ul><li>";
//                        if (!empty($f3->note)) {
//                            $itemName .= '<a href="#" data-toggle="tooltip" data-placement="left" title="' . htmlspecialchars($f3->note) . '">' . $f3->sub_item_2 . '</a>';
//                        } else {
//                            $itemName .= $f3->sub_item_2;
//                        }
                        $itemName .= $f3->sub_item_2;

                        $itemName .= (!empty($f3->note)) ? '<ul style="list-style-type: circle;"><li>'.htmlspecialchars($f3->note).'</li></ul>' : '';
                        $itemName .= "</li></ul>";

//                        $itemName .= "<ul><li>" . $f3->sub_item_2 . "</li></ul>";
                    }


                    $itemName .= "</ul>";
                }

                $itemName .= "</ul>";
            }


            $data['aaData'][] = array(
                $f->category,
                $f->type,
                $itemName
            );
        }

//        $this->_dbh->query("drop temporary table SRT_TEMP_{$locationId}");

        echo json_encode($data);


//        while ($f = $sth1->fetch(PDO::FETCH_OBJ))
//        {
//            $q1 = "select `category`, `type` from SRT_TEMP{$locationId} where service_id = :serviceId limit 1";
//            $s1 = $this->_dbh->prepare($q1);
//            $s1->execute([':serviceId' => $f->service_id]);
//
//            while ($f1 = $s1->fetch(PDO::FETCH_OBJ))
//            {
//                $category
//            }
//
//            $data['aaData'][] = array(
//                $f1->category,
//                $f1->type,
//                null,
//                null,
//                null
//            );

//        while ($f = $sth1->fetch(PDO::FETCH_OBJ)) {
//
//            $agencyId = (substr($locationId, 0, 2) === 'L-') ? $_agency->GetAgencyIdByLocationId(preg_replace('/L-/', '', $locationId)) : $locationId;
//
//
//            //ITEM NAME
//            $customItemName = $_service->GetCustomItemName($agencyId, $f->item_id);
//            $itemName = (!empty($customItemName)) ? $customItemName : $f->item;
//
//            //NOTES
//            if (!empty($f->note)) {
//                $subItem2Note = '<a href="#" data-toggle="tooltip" data-placement="top" title="'.htmlspecialchars($f->note).'">' . $f->sub_item_2 . '</a>';
//            } else {
//                $subItem2Note = $f->sub_item_2;
//            }
//
//
//            $data['aaData'][] = array(
//                $f->category,
//                $f->type,
////                $f->item,
//                $itemName,
//                $f->sub_item,
//                $subItem2Note
//            );
//
//        }


    }

}

