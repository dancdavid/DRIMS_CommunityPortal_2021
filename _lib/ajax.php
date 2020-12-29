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
    private $_level;

    public function __construct()
    {
        $this->_core = new core();
        $this->_db = new db();
        $this->_level = new Level1();
    }

    public function getVolunteer()
    {

        $dbh = $this->_db->initDB();
        $sth = $dbh->query("select * from cp_volunteers where status != 'IN-ACTIVE' order by id desc");

        $data = array();
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $categories = str_replace(";", "<br>", $f->categories);
            $id = $this->_core->encode($f->id);

            $data['aaData'][] = array(
                $f->first_name . " " . $f->last_name,
                "<a href='mailto:$f->email'>$f->email</a>",
                $f->phone,
                $f->city,
                $f->state,
                $f->status,
                $categories,
                "<center><a href='edit_volunteer?id=$id' class='btn btn-xs btn-danger'>EDIT</a></center>"
            );
        }

        echo json_encode($data);
    }

    public function getAllContacts()
    {

        $committee_id = $this->_core->decode($this->_core->gpGet('cid'));

        $dbh = $this->_db->initDB();
        $qry = " select org_users.id as contact_id,
        oc.cp_org_id as agency_id,
        CONCAT(first_name,last_name) as contact_name
        from org_users 
        join org_contacts oc on oc.user_id = org_users.id
        where oc.status = 'ACTIVE' and oc.cp_org_id is not null ";
        $sth = $dbh->query($qry);

//        $_agncy = new agency();

        $data = array();
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
            $_agncy = new agency($f->agency_id);
            $data['aaData'][] = array(
                $f->contact_name,
                $_agncy->get_agency_name($f->agency_id),
                "<center><input type='checkbox' name='contact_id[]' value='{$f->contact_id}'></center>"
            );
        }

        echo json_encode($data);
    }

    public function getOrgSearchList(){

        $_core = new core();
        $agency_name = $_POST['agency_name'];
        $dbh = $this->_db->initDB();
        $sth = $dbh->query("select org_information.id as organization_id , org_contacts.cms_org_id as org_id,org_contacts.user_id,org_information.name , 
         org_information.cp_parent_child as agency_id, 
         org_users.first_name as user_fname, org_users.last_name as user_lname, org_users.email as user_mail 
         from org_contacts 
         join org_information on org_contacts.cms_org_id = org_information.id 
         join org_users on org_contacts.user_id = org_users.id 
         where org_information.name like '%$agency_name%' and org_contacts.cms_access_level = 'ADMIN'
         
         UNION

         select org_information.id as organization_id , org_contacts.cp_org_id as org_id,org_contacts.user_id,org_information.name
          , org_information.cp_parent_child as agency_id, 
         org_users.first_name as user_fname, org_users.last_name as user_lname, org_users.email as user_mail 
         from org_contacts 
         join org_information on org_contacts.cp_org_id = org_information.cp_parent_child
         join org_users on org_contacts.user_id = org_users.id 
         where org_information.name like '%$agency_name%'
         ");
        $dataExist = false;

        $body = '<table class="table table-hover table-bordered table-sm">';
        $body .= '<thead><tr class="">
                     <th>Org Name</th>
                     <th>Org Admin Name</th>
                     <th>Org Admin Email</th>
                     <th>Action</th>
                  </tr></thead><tbody>';
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $agency_id = ($f->agency_id ? $f->agency_id : 0);
            $encoded_agency_id = $_core->encode($agency_id);
            
            //if(!$agency_name){
                $dataExist = true;
                $org_name = $f->name;
                $user_name = $f->user_fname .' '. $f->user_lname;
                $user_mail = $f->user_mail;
                $oid = $_core->encode($f->organization_id);
                $uid = $_core->encode($f->user_id);
                $body .= "<tr class=''> 
                          <td> $org_name </td>
                          <td> $user_name </td>
                          <td> $user_mail </td>
                          <td><a href='../_lib/agencyaction.php?action=".$_core->encode('sendInviteToExistingOrg')."&oid=$oid&uid=$uid&aid=$encoded_agency_id'>Invite</a></td> </tr>";    
            //}
        }
        $body .= '</tbody></table>';

        echo ($dataExist ? $body : '');
    }

    public function getCalendarList()
    {
        $dbh = $this->_db->initDB();
        $sth = $dbh->query("select * from cp_events_calendar  where parent_agency_id = '{$_SESSION['parent_agency']}'");

        $_level = new Level1();
        $data = array();
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $class = "label label-success";
            if ($f->status === 'DELETED') {
                $class = "label label-warning";
            }

            $level1 = $_level->GetLevel1Name($f->level_1);

            $data['aaData'][] = array(
                strtoupper($f->event_title),
                date('M-d-Y', strtotime($f->event_date)),
                $level1,
                "<span class='{$class}'>" . $f->status . "</span>",
                $f->submitted_by,
                "<a href='edit_event?id={$this->_core->encode($f->id)}' class='btn btn-xs btn-danger'>Edit</a>"
            );
        }

        echo json_encode($data);
    }

    public function getMessageBoardList()
    {
        $dbh = $this->_db->initDB();
//        $sth = $dbh->query("select * from cp_message_board where parent_agency_id = '{$_SESSION['parent_agency']}' and `status` = 'ACTIVE'");
        $sth = $dbh->query("select * from cp_message_board where parent_agency_id = '{$_SESSION['parent_agency']}'");

        $data = array();
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $class = "label label-success";
            if ($f->status === 'DELETED') {
                $class = "label label-warning";
            }

            $level1 = $this->_level->GetLevel1Name($f->level_1);

            $data['aaData'][] = array(
                strtoupper($f->title),
                $level1,
                "<span class='{$class}'>" . $f->status . "</span>",
                $f->submitted_by,
                "<a href='edit_messageboard?id={$this->_core->encode($f->id)}' class='btn btn-xs btn-danger'>Edit</a>"
            );
        }
        echo json_encode($data);
    }

    public function agencyServicesSearch()
    {
        $dbh = $this->_db->initDB();

        $qry = "select agency_id, agency_name, agency_address, agency_city, agency_telephone from directory_services_search where status = 'ACTIVE' ";

        if (!empty($_GET['sid']) && is_numeric($_GET['sid'])) {
            $qry .= "and service_id = '{$_GET['sid']}' ";
        }

        if (!empty($_GET['t']) && $_GET['t'] === 'yes') {
            if (!empty($_GET['l']) && $_GET['l'] === 'yes') {
                $qry .= "and (terrebonne = 'YES' ";
            } else {
                $qry .= "and terrebonne = 'YES' ";
            }
        }

        if (!empty($_GET['l']) && $_GET['l'] === 'yes') {
            if (!empty($_GET['t']) && $_GET['t'] === 'yes') {
                $qry .= "or lafourche = 'YES') ";
            } else {
                $qry .= "and lafourche = 'YES' ";
            }
        }


        $qry .= "group by agency_id";

        $sth = $dbh->query($qry);

        $data = array();
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $_agency = new agency($f->agency_id);

            $services = '<center><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#servicesModal" href="html/services_view.users.htm.php?id=' . $this->_core->encode($f->agency_id) . '" >
  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
</button></center>';


            $data['aaData'][] = array(
                '<a href="agency_summary&id=' . $this->_core->encode($f->agency_id) . '">' . strtoupper($f->agency_name) . '</a>',
                $f->agency_address,
                $f->agency_city,
                $f->agency_telephone,
                $services,
                $_agency->build_services_view()
            );
        }

        echo json_encode($data);
    }

    public function agencyServicesSearchPublic()
    {
        $dbh = $this->_db->initDB();

        $qry = "select agency_id, agency_name, agency_address, agency_city, agency_telephone from directory_services_search where status = 'ACTIVE' ";

        if (!empty($_GET['sid']) && is_numeric($_GET['sid'])) {
            $qry .= "and service_id = '{$_GET['sid']}' ";
        }

        if (!empty($_GET['t']) && $_GET['t'] === 'yes') {
            if (!empty($_GET['l']) && $_GET['l'] === 'yes') {
                $qry .= "and (terrebonne = 'YES' ";
            } else {
                $qry .= "and terrebonne = 'YES' ";
            }
        }

        if (!empty($_GET['l']) && $_GET['l'] === 'yes') {
            if (!empty($_GET['t']) && $_GET['t'] === 'yes') {
                $qry .= "or lafourche = 'YES') ";
            } else {
                $qry .= "and lafourche = 'YES' ";
            }
        }


        $qry .= "group by agency_id";

        $sth = $dbh->query($qry);

        $data = array();
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $_agency = new agency($f->agency_id);

            $services = '<center><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#servicesModal" href="html/services_view.htm.php?id=' . $this->_core->encode($f->agency_id) . '" >
  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
</button></center>';


            $data['aaData'][] = array(
                '<a href="agency_summary&id=' . $this->_core->encode($f->agency_id) . '">' . strtoupper($f->agency_name) . '</a>',
                $f->agency_address,
                $f->agency_city,
                $f->agency_telephone,
                $services,
                $_agency->build_services_view()
            );
        }

        echo json_encode($data);
    }

    public function getAgency()
    {
        $fields = $this->_db->getAgencyFields();
        $dbh = $this->_db->initDB();
        $qry = "select $fields from org_information where";

//        $qry .= " agency_id <> '{$_SESSION['parent_agency']}' or";

        if ($_SESSION['cp_user_level'] == '2' || $_SESSION['cp_user_level'] == '1') {
            $qry .= " (";

            $filterArr = explode(";", $_SESSION['level_1_filter']);
            $i = 0;
            foreach ($filterArr as $find) {
                if ($i > 0) $qry .= " or";
                $qry .= " find_in_set('{$find}', replace(level_1, ';', ','))";
                $i++;
            }

            $qry .= " ) and ";
        }

        if (!UserAccess::ManageLevel1()) {
            $qry .= " status = 'ACTIVE' and ";
        }


        $qry .= " cp_parent_agency = '{$_SESSION['parent_agency']}'";


        $sth = $dbh->query($qry);

        $data = array();
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

//            $_agency = new agency($f->agency_id);
//
//            $services = '<center><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#servicesModal" href="html/services_view.users.htm.php?id=' . $this->_core->encode($f->agency_id) . '" >
//  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
//</button></center>';


//            $data['aaData'][] = array(
//                '<a href="agency_summary&id=' . $this->_core->encode($f->agency_id) . '">' . strtoupper($f->agency_name) . '</a>',
//                $f->agency_address,
//                strtoupper($f->agency_city),
//                $f->agency_telephone,
//                $services,
//                $_agency->build_services_view()
//            );

            $_level = new Level1();
            $level1 = $_level->GetLevel1Name($f->level_1);

            $data['aaData'][] = array(
                '<a href="agency_summary&id=' . $this->_core->encode($f->agency_id) . '">' . strtoupper($f->agency_name) . '</a>',
//                strtoupper(str_replace(';', '<br>', $f->level_1)),
                strtoupper(str_replace(",", "<br>", $level1)),
                strtoupper($f->agency_address),
                strtoupper($f->agency_city),
                strtoupper($f->agency_state),
                $f->agency_zipcode,
                $f->agency_telephone,
                $f->status
            );
        }

        echo json_encode($data);
    }

    public function getPublicAgency()
    {
        $fields = $this->_db->getAgencyFields();
        $dbh = $this->_db->initDB();
        $sth = $dbh->query("select $fields from org_information where status = 'ACTIVE'");

        $data = array();
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $_agency = new agency($f->agency_id);

            $services = '<center><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#servicesModal" href="html/services_view.htm.php?id=' . $this->_core->encode($f->agency_id) . '" >
  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
</button></center>';


            $data['aaData'][] = array(
                '<a href="agency_summary&id=' . $this->_core->encode($f->agency_id) . '">' . strtoupper($f->agency_name) . '</a>',
                $f->agency_address,
                $f->agency_city,
                $f->agency_telephone,
                $services,
                $_agency->build_services_view()
            );
        }

        echo json_encode($data);
    }

    public function editAgencyList()
    {

        $dbh = $this->_db->initDB();
        $fields = $this->_db->getAgencyFields();
        $sth = $dbh->query("select $fields from org_information");

        $data = array();
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $data['aaData'][] = array(
                '<a href="agency_summary&id=' . $this->_core->encode($f->agency_id) . '">' . strtoupper($f->agency_name) . '</a>',
                "<a href='edit_agency_info?id=" . $this->_core->encode($f->agency_id) . "' class='btn btn-xs btn-success'>Edit Info</a>",
                "<a href='edit_agency_contacts?id=" . $this->_core->encode($f->agency_id) . "' class='btn btn-xs btn-warning'>Edit Contacts</a>",
                "<a href='edit_agency_services?id=" . $this->_core->encode($f->agency_id) . "' class='btn btn-xs btn-danger'>Edit Services</a>"
            );
        }

        echo json_encode($data);
    }

    public function getCommittee()
    {

        $dbh = $this->_db->initDB();
        $sth = $dbh->query("select * from cp_committee_list where committee_status = 'ACTIVE'");

        $data = array();
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
            $data['aaData'][] = array(
                "<a href='committee_summary&id=" . $this->_core->encode($f->committee_id) . "'>" . strtoupper($f->committee_name) . "</a>",
                $f->committee_description
            );
        }

        echo json_encode($data);
    }

    public function checkEmail()
    {

        $email = $this->_core->gpGet('email');
        $agency_id = $this->_core->gpGet('agency_id'); // encoded agency id
        $organization_id = $this->_core->gpGet('organization_id'); // encoded org id
        $agencyIdDecoded = $this->_db->decode($agency_id);
        
        if ($this->_db->checkExistingUser($email, $agencyIdDecoded , 'org_users')) {
            echo 'fail';
        } else {
            $_core = new core();
            $dbh = $this->_db->initDB();
            $sth = $dbh->query("select id,email from org_users where email='$email'");
    
            $body = '<table style="margin-bottom:0px" class="table table-hover table-bordered table-sm">';
            $body .= '<thead><tr class="">
                         <th>Email</th>
                         <th>Action</th>
                      </tr></thead><tbody>';
            $dataExist = false;
            while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
                    $dataExist = true;
                    $user_mail = $f->email;
                    $uid = $_core->encode($f->id);
                    $body .= "<tr class=''> 
                              <td> $user_mail </td>
                              <td><a href='../_lib/agencyaction.php?action=".$_core->encode('sendInviteToExistingContact')."&uid=$uid&aid=$agency_id&oid=$organization_id'>Invite</a></td> </tr>";    
            }
            $body .= '</tbody></table>';
    
            echo ($dataExist ? $body : '');
        }
    }

    // change organization
    public function changeOrg()
    {      
        $_core = new core();
        $oid = $_core->gpGet('oid');
        $org_id = $_core->decode($oid);

        $dbh = $this->_db->initDB();
        $qry = "update org_users 
        set default_org_id = '{$org_id}' 
        where id = '{$_SESSION['userID']}'";
        $sth = $dbh->prepare($qry);
        $sth->execute();
        $_SESSION['orgID'] = $org_id;
        echo $qry;

    }

    public function getLevel1Users()
    {

        $qry = "select org_users.id
            ,first_name
            ,last_name
            ,phone
            ,email
            ,oc.cp_level_1 as level_1
            ,oc.status
            from org_users 
            join org_contacts oc on oc.user_id = org_users.id
            where
             oc.cp_org_id = :agencyId and oc.cp_user_level = '1' ";

        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':agencyId' => $_SESSION['agency_id']]);

        $_level = new Level1();
        $data = [];
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
            $name = $f->first_name . ' ' . $f->last_name;
            $level1 = $_level->GetLevel1Name($f->level_1);
            $data['aaData'][] = [
                "<a href='edit_level1_user?uid={$this->_core->encode($f->id)}&aid={$this->_core->encode($_SESSION['agency_id'])}'>{$name}</a>",
                $f->email,
                $f->phone,
                str_replace(",", "<br>", $level1),
                $f->status
            ];
        }

        echo json_encode($data);

    }

    public function getLevel2Users()
    {
        $qry = "select org_users.id
            ,first_name
            ,last_name
            ,phone
            ,email
            ,oc.cp_level_1 as level_1
            ,oc.status
            from org_users 
            join org_contacts oc on oc.user_id = org_users.id
            where
             oc.cp_org_id = :agencyId and oc.cp_user_level = '2' ";

        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':agencyId' => $_SESSION['agency_id']]);

        $_level = new Level1();
        $data = [];
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
            $name = $f->first_name . ' ' . $f->last_name;
            $level1 = $_level->GetLevel1Name($f->level_1);
            $data['aaData'][] = [
                "<a href='edit_level2_user?uid={$this->_core->encode($f->id)}&aid={$this->_core->encode($_SESSION['agency_id'])}'>{$name}</a>",
                $f->email,
                $f->phone,
                str_replace(",", "<br>", $level1),
                $f->status
            ];
        }

        echo json_encode($data);

    }

    public function GetAllDashboardLinks()
    {
//        $qry = "select id,title,description,url,level_1,status from cp_dashboard_links where parent_agency_id = :id and `status` <> 'DELETE' order by id desc";
        $qry = "select id,title,description,url,level_1,status from cp_dashboard_links where parent_agency_id = :id order by id desc";
        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':id' => $_SESSION['parent_agency']]);


        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $class = "label label-success";
            if ($f->status !== 'ACTIVE') {
                $class = "label label-warning";
            }

            $level1 = $this->_level->GetLevel1Name($f->level_1);

            $data['aaData'][] = array(
                $f->title,
                $level1,
                "<span class='{$class}'>" . $f->status . "</span>",
                $f->url,
                $f->description,
                "<a href='edit_link?id={$this->_db->encode($f->id)}&loc=dash' class='btn btn-danger btn-xs'>EDIT</a>"
            );
        }

        echo json_encode($data);
    }

    public function GetAllDashboardDocs()
    {
//        $qry = "select id,title,description,level_1,status,level_1 from cp_file_upload where parent_agency = :id and `status` <> 'DELETE' order by id desc";
        $qry = "select id,title,description,level_1,status,level_1 from cp_file_upload where parent_agency = :id order by id desc";
        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':id' => $_SESSION['parent_agency']]);


        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $class = "label label-success";
            if ($f->status !== 'ACTIVE') {
                $class = "label label-warning";
            }

            $level1 = $this->_level->GetLevel1Name($f->level_1);

            $data['aaData'][] = array(
                $f->title,
                $level1,
                $f->description,
                "<span class='{$class}'>" . $f->status . "</span>",
                "<a href='edit_docs?id={$this->_db->encode($f->id)}&loc=dash' class='btn btn-danger btn-xs'>EDIT</a>"
            );
        }

        echo json_encode($data);
    }

    public function GetServices()
    {
        $qry = "select distinct(service_id), description
                        ,category, title, item_id, item 
                        from cp_all_agency_services 
                        where parent_agency_id = :pid order by title";
        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':pid' => $_SESSION['parent_agency']]);

        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $sid = $this->_db->encode($f->service_id);
            $iid = $this->_db->encode($f->item_id);

            //FOR SUB ITEMS
            $qrySubItems = "select id, sub_item from cp_services_sub_item where item_id = :itemId and `status` = 'ACTIVE'";
            $dbh1 = $this->_db->initDB();
            $sth1 = $dbh1->prepare($qrySubItems);
            $sth1->execute([':itemId' => $f->item_id]);

            $subItems = '<ul>';
            while ($fx = $sth1->fetch(PDO::FETCH_OBJ)) {
                $subItems .= "<li>{$fx->sub_item}</li>";
            }
            $subItems .= '</ul>';

            $data['aaData'][] = array(
                "<a href='add_services?sid={$sid}&iid={$iid}&siid=set' data-toggle='tooltip' data-placement='top' title='{$f->description}' >{$f->title}</a>",
                $f->category,
                $f->item,
                $subItems
            );
        }

        echo json_encode($data);
    }

    public function GetServices2()
    {

        $qry = "select id as service_id, category, type, description, status 
                from cp_services where parent_agency_id = :pid
                order by type";

        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':pid' => $_SESSION['parent_agency']]);


        $data = [];
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $sid = $this->_db->encode($f->service_id);

            //ITEMS
            $qryItem = "select id as item_id, item, status as item_status from cp_services_item where service_id = :sid";
            $sthItem = $dbh->prepare($qryItem);
            $sthItem->execute([':sid' => $f->service_id]);

            $items = '';
            $subItem = '';
            $subItem2 = '';
            $firstItemId = '';
            $firstSubItemId = '';
            $i = 0;
            while ($fItem = $sthItem->fetch(PDO::FETCH_OBJ)) {
                $iid = $this->_db->encode($fItem->item_id);

                if ($i == 0) $firstItemId .= $iid;

                $items .= "<ul><li><a href='add_services2?sid={$sid}&iid={$iid}'>" . strtoupper($fItem->item) . "</a></li></ul>";

                //SUBITEMS
                $qrySubItem = "select id as sub_item_id, sub_item, status as sub_item_status from cp_services_sub_item where item_id = :iid";
                $sthSubItem = $dbh->prepare($qrySubItem);
                $sthSubItem->execute([':iid' => $fItem->item_id]);

                while ($fSubItem = $sthSubItem->fetch(PDO::FETCH_OBJ)) {
                    $siid = $this->_db->encode($fSubItem->sub_item_id);

                    if ($i == 0) $firstSubItemId = $siid;
                    $i++;

                    $subItem .= "<ul><li><a href='add_services2?sid={$sid}&iid={$iid}&siid={$siid}'>" . strtoupper($fSubItem->sub_item) . "</a></li></ul>";

                    //SUBITEMS2
                    $qrySubItem2 = "select id as sub_item_2_id, sub_item_2, status as sub_item_2_status from cp_services_sub_item_2 where sub_item_id = :siid2";
                    $sthSubItem2 = $dbh->prepare($qrySubItem2);
                    $sthSubItem2->execute([':siid2' => $fSubItem->sub_item_id]);


                    $subItem2 .= '<ul><li>' . $fSubItem->sub_item . '</li>';

                    $subItem2 .= '<ul>';
                    while ($fSubItem2 = $sthSubItem2->fetch(PDO::FETCH_OBJ)) {
                        $siid2 = $this->_db->encode($fSubItem2->sub_item_2_id);
                        $subItem2 .= "<li><a href='add_services2?sid={$sid}&iid={$iid}&siid={$siid}'>" . strtoupper($fSubItem2->sub_item_2) . "</a></li>";
                    }
                    $subItem2 .= '</ul></ul>';


                }
            }

            $data['aaData'][] = array(
                $f->category,
                "<a href='add_services2?sid={$sid}&iid={$firstItemId}&siid={$firstSubItemId}' data-toggle='tooltip' data-placement='top' title='{$f->description}' >" . strtoupper($f->type) . "</a>",
                strtoupper($f->status),
                $items,
                $subItem,
                $subItem2
            );
        }

        echo json_encode($data);


//        $qry = "select distinct service_id , description, status
//                        ,category, type, item_id, item, item_status
//                        ,sub_item_id, sub_item, sub_item_status
//                        ,sub_item_2_id, sub_item_2, sub_item_2_status
//                        from cp_all_agency_services
//                        where parent_agency_id = :pid
//                        order by type";
//        $dbh = $this->_db->initDB();
//        $sth = $dbh->prepare($qry);
//        $sth->execute([':pid' => $_SESSION['parent_agency']]);
//
//        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
//
//            $sid = $this->_db->encode($f->service_id);
//            $iid = (!empty($f->item_id)) ? $this->_db->encode($f->item_id) : '';
//
//            //FOR SUB ITEMS 2
//            $qrySubItems2 = "select id, sub_item_2 from cp_services_sub_item_2 where sub_item_id = :subItemId and `status` = 'ACTIVE'";
//            $dbh1 = $this->_db->initDB();
//            $sth1 = $dbh1->prepare($qrySubItems2);
//            $sth1->execute([':subItemId' => $f->sub_item_id]);
//
//            $subItems2 = '<ul>';
//            while ($fx = $sth1->fetch(PDO::FETCH_OBJ)) {
//                $subItems2 .= "<li>{$fx->sub_item_2}</li>";
//            }
//            $subItems2 .= '</ul>';
//
//            $data['aaData'][] = array(
//                $f->category,
//                "<a href='add_services2?sid={$sid}&iid={$iid}' data-toggle='tooltip' data-placement='top' title='{$f->description}' >" . strtoupper($f->type) . "</a>",
//                strtoupper($f->status),
////                $f->category,
////                $f->item,
//                "<a href='add_services2?sid={$sid}&iid={$iid}' data-toggle='tooltip' data-placement='top' title='{$f->description}' >" . strtoupper($f->item) . "</a>",
//                $f->item_status,
//                $f->sub_item,
//                $subItems2
//            );
//        }
//
//        echo json_encode($data);
    }

    public function LoadCalInfo()
    {

        if (!empty($_GET['filter']) && $_GET['filter'] != 'null') {
            $filter = $_GET['filter'];
        } else {
            $filter = str_replace(";", ',', $_SESSION['level_1_filter']);
        }

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

        $qry .= " parent_agency_id = :parent and `status` = 'ACTIVE'";

//        $searchEvent = '';
        if (!empty($_GET['searchEvent'])) {
            $searchEvent = "%{$_GET['searchEvent']}%";
            $qry .= " and event_title like :search";
        }

        $dbh = $this->_db->initDB();

        $sth = $dbh->prepare($qry);

        if (!empty($_GET['searchEvent'])) {
            $sth->bindParam(':search', $searchEvent);
        }

        $sth->bindParam(':parent', $_SESSION['parent_agency']);

        $sth->execute();

        $data = [];
        $_files = new EventFiles();

        $_level = new Level1();
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            //EVENT TIME
            if (!empty($f->event_end_time))
            {
                $eventTime = $f->event_time . ' - ' . $f->event_end_time;
            } else if (!empty($f->event_time)) {
                $eventTime = $f->event_time;
            } else {
                $eventTime = '';
            }

            $title = $f->event_title . ' on ' . date('M-d-Y', strtotime($f->event_date)) . ' @ ' . $f->event_time;
            $address = $f->event_address . ' ' . $f->event_city . ' ' . $f->event_state . ' ' . $f->event_zip;
            $eventWebsite = (!empty($f->url)) ? "<a href='{$f->url}' class='btn btn-success' target='_blank'>Event Website</a>" : "";
            $time = date("H:i", strtotime($f->event_time));
            $endTime = date("H:i", strtotime($f->event_end_time));
            $eventFiles = '<b>Files:</b><br>' . $_files->GetFiles($f->id, 'cp_events_calendar_file_upload', 'events');

            $eventLevel = $_level->GetLevel1Name($f->level_1);

            //MailTo:
            $mail_body = "{$f->event_title}  %0D%0A%0D%0A";
            $mail_body .= "{$_SESSION['Level1_Label']}: " . $eventLevel . " %0D%0A%0D%0A";
            $mail_body .= "Date: " . date('M-d-Y', strtotime($f->event_date)) . " %0D%0A";
            $mail_body .= "Time: {$eventTime}  %0D%0A%0D%0A";
            $mail_body .= "Address: %0D%0A {$f->event_address} %0D%0A";
            $mail_body .= $f->event_city . " " . $f->event_state . " " . $f->event_zip . " %0D%0A %0D%0A";
            $mail_body .= "Website: %0D%0A {$f->url} %0D%0A%0D%0A";
            $mail_body .= "Description: %0D%0A " . strip_tags($f->event_description) ." %0D%0A%0D%0A";
            $mail_body .= "Regards,%0D%0A {$_SESSION['user_name']}";

            $_agency = new agency();
            $agencyName = $_agency->get_agency_name($_SESSION['agency_id']);


            if($f->is_reoccuring=='1'){
                $title = $f->event_title . ' @ ' . $f->event_time;
                if($f->event_reoccuring_type=='Weekly Day'){
                    $startDate = $f->event_date;
                    for($i=1; $i<=52; $i++){
                        $days = explode(',', $f->event_reoccuring_days);
                        $daysArray = [
                                        '0'=>'sunday this week',
                                        '1'=>'monday this week',
                                        '2'=>'tuesday this week',
                                        '3'=>'wednesday this week',
                                        '4'=>'thursday this week',
                                        '5'=>'friday this week',
                                        '6'=>'saturday this week',
                                    ];
                        foreach($days as $day){                    
                            if(array_key_exists($day,$daysArray)){
                                if(!is_null($f->event_end_date) && strtotime($f->event_end_date)>=strtotime($startDate)){
                                    if(strtotime($daysArray[$day], strtotime($startDate))>strtotime($f->event_date)){
                                        $data[] = [
                                            'id' => $f->id,
                                            'title' => $title,
                                            'description' => $f->event_description,
                                            'address' => $address,
                                            'eventWhen' => date("Y-m-d", strtotime($daysArray[$day], strtotime($startDate))) . ' @ ' . $eventTime,
                                            'eventUrl' => $eventWebsite,
                                            'eventFiles' => $eventFiles,
                                            'start' => date("Y-m-d", strtotime($daysArray[$day], strtotime($startDate))) . 'T' . $time,
                                            'end' => date("Y-m-d", strtotime($daysArray[$day], strtotime($startDate))) . 'T' . $endTime,
                                            'ics' => '<a href="../_lib/download_ics.php?id=' . $this->_db->encode($f->id) . '" class="btn btn-default" target="_blank"><small>Download Event ICS File</small> <span class="glyphicon glyphicon-calendar" aria-hidden="true" ></span></a>',
                                            'email' => '<a href="mailto:?subject=' . $agencyName . ' Event-' . strtoupper($f->event_title) . '&body=' . $mail_body . '" class="btn btn-default" target="_blank"><small>Share</small> <span class="glyphicon glyphicon-envelope" aria-hidden="true" ></span></a>',
                                        ];
                                    }                                    
                                }
                            }
                        }
                        $startDate = date('Y-m-d', strtotime($startDate.' +7 day'));                        
                    }
                }
                elseif($f->event_reoccuring_type=='Daily'){
                    $startDate = $f->event_date;
                    for($i=1; $i<=365; $i++){
                        if(!is_null($f->event_end_date) && strtotime($f->event_end_date)>=strtotime($startDate)){
                            $data[] = [
                                'id' => $f->id,
                                'title' => $title,
                                'description' => $f->event_description,
                                'address' => $address,
                                'eventWhen' => date('M-d-Y', strtotime($startDate)) . ' @ ' . $eventTime,
                                'eventUrl' => $eventWebsite,
                                'eventFiles' => $eventFiles,
                                'start' => $startDate . 'T' . $time,
                                'end' => $startDate . 'T' . $endTime,
                                'ics' => '<a href="../_lib/download_ics.php?id=' . $this->_db->encode($f->id) . '" class="btn btn-default" target="_blank"><small>Download Event ICS File</small> <span class="glyphicon glyphicon-calendar" aria-hidden="true" ></span></a>',
                                'email' => '<a href="mailto:?subject=' . $agencyName . ' Event-' . strtoupper($f->event_title) . '&body=' . $mail_body . '" class="btn btn-default" target="_blank"><small>Share</small> <span class="glyphicon glyphicon-envelope" aria-hidden="true" ></span></a>',
                            ];
                            $startDate = date('Y-m-d', strtotime($startDate.' +1 day'));             
                        }
                    }
                }
                elseif($f->event_reoccuring_type=='Weekly'){
                    $startDate = $f->event_date;
                    for($i=1; $i<=52; $i++){
                        if(!is_null($f->event_end_date) && strtotime($f->event_end_date)>=strtotime($startDate)){
                            $data[] = [
                                'id' => $f->id,
                                'title' => $title,
                                'description' => $f->event_description,
                                'address' => $address,
                                'eventWhen' => date('M-d-Y', strtotime($startDate)) . ' @ ' . $eventTime,
                                'eventUrl' => $eventWebsite,
                                'eventFiles' => $eventFiles,
                                'start' => $startDate . 'T' . $time,
                                'end' => $startDate . 'T' . $endTime,
                                'ics' => '<a href="../_lib/download_ics.php?id=' . $this->_db->encode($f->id) . '" class="btn btn-default" target="_blank"><small>Download Event ICS File</small> <span class="glyphicon glyphicon-calendar" aria-hidden="true" ></span></a>',
                                'email' => '<a href="mailto:?subject=' . $agencyName . ' Event-' . strtoupper($f->event_title) . '&body=' . $mail_body . '" class="btn btn-default" target="_blank"><small>Share</small> <span class="glyphicon glyphicon-envelope" aria-hidden="true" ></span></a>',
                            ];
                            $startDate = date('Y-m-d', strtotime($startDate.' +7 day'));
                        }
                    }
                }

                elseif($f->event_reoccuring_type=='Monthly'){
                    $startDate = $f->event_date;
                    $getDate = date('d',strtotime($startDate));
                    for($i=1; $i<=12; $i++){
                        if(!is_null($f->event_end_date) && strtotime($f->event_end_date)>=strtotime($startDate)){
                            $setDate = date('Y-m', strtotime($startDate)).'-'.$getDate;
                            if(date('m',strtotime($setDate))==date('m',strtotime($startDate))){
                                $data[] = [
                                    'id' => $f->id,
                                    'title' => $title,
                                    'description' => $f->event_description,
                                    'address' => $address,
                                    'eventWhen' => date('M-d-Y', strtotime($setDate)) . ' @ ' . $eventTime,
                                    'eventUrl' => $eventWebsite,
                                    'eventFiles' => $eventFiles,
                                    'start' => $setDate . 'T' . $time,
                                    'end' => $setDate . 'T' . $endTime,
                                    'ics' => '<a href="../_lib/download_ics.php?id=' . $this->_db->encode($f->id) . '" class="btn btn-default" target="_blank"><small>Download Event ICS File</small> <span class="glyphicon glyphicon-calendar" aria-hidden="true" ></span></a>',
                                    'email' => '<a href="mailto:?subject=' . $agencyName . ' Event-' . strtoupper($f->event_title) . '&body=' . $mail_body . '" class="btn btn-default" target="_blank"><small>Share</small> <span class="glyphicon glyphicon-envelope" aria-hidden="true" ></span></a>',
                                ];
                            }
                            $startDate = date('Y-m-d', strtotime($startDate.' +1 month'));



                        }
                    }
                }
            }
            else{

                $data[] = [
                    'id' => $f->id,
                    'title' => $title,
                    'description' => $f->event_description,
                    'address' => $address,
                    'eventWhen' => date('M-d-Y', strtotime($f->event_date)) . ' @ ' . $eventTime,
                    'eventUrl' => $eventWebsite,
                    'eventFiles' => $eventFiles,
                    'start' => $f->event_date . 'T' . $time,
                    'end' => $f->event_date . 'T' . $endTime,
                    'ics' => '<a href="../_lib/download_ics.php?id=' . $this->_db->encode($f->id) . '" class="btn btn-default" target="_blank"><small>Download Event ICS File</small> <span class="glyphicon glyphicon-calendar" aria-hidden="true" ></span></a>',
                    'email' => '<a href="mailto:?subject=' . $agencyName . ' Event-' . strtoupper($f->event_title) . '&body=' . $mail_body . '" class="btn btn-default" target="_blank"><small>Share</small> <span class="glyphicon glyphicon-envelope" aria-hidden="true" ></span></a>',
                ];
            }
        }

        echo json_encode($data);
    }

    public function ListOrgs()
    {
        $dbh = $this->_db->initDB();
        $qry = "select * from cp_search_orgs_and_locations where";

//        if ($_SESSION['cp_user_level'] == '2' || $_SESSION['cp_user_level'] == '1') {
        if (!empty($_SESSION['level_1_filter']))
        {
            $qry .= " (";

            $filterArr = explode(";", $_SESSION['level_1_filter']);
            $i = 0;
            foreach ($filterArr as $find) {
                if ($i > 0) $qry .= " or";
                $qry .= " find_in_set('{$find}', replace(level_1, ';', ','))";
                $i++;
            }

            $qry .= " ) and ";
        }

        if (!UserAccess::ManageLevel1()) {
            $qry .= " status = 'ACTIVE' and ";
        }

        $qry .= " parent_agency = '{$_SESSION['parent_agency']}'";
        $qry .= " and agency_id <> '{$_SESSION['parent_agency']}'";

//        $qry .= " status = 'ACTIVE' and parent_agency = '{$_SESSION['parent_agency']}'";

        $sth = $dbh->query($qry);

        $data = array();
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $services = '<center><button type="button" class="btn btn-primary btn-xs locServices" data-id="'.$this->_db->encode($f->id).'" >
                        View Services
                        </button></center>';

            $_level = new Level1();
            $level1 = $_level->GetLevel1Name($f->level_1);

            $_partnerType = new PartnerType();
            $partnerType = $_partnerType->GetPartnerTypeName($f->partner_type);

            $_agency = new agency($f->agency_id);
            $contactArr = $_agency->get_agency_contact($f->agency_id, 2);
            $contacts = '';
            foreach ($contactArr as $ac)
            {
                $contacts .= $ac['first_name'] . ' ' . $ac['last_name'] . '<br/> ';
            }

            $status = (UserAccess::ManageLevel1()) ? $f->status : '';

            $data['aaData'][] = array(
                '<a class="search" href="agency_summary&id=' . $this->_core->encode($f->agency_id) . '" data-id="' . $this->_core->encode($f->id) . '">' . strtoupper($f->agency_name) . '</a>',
                $f->org_type,
                strtoupper(str_replace(",", "<br>", $level1)),
                strtoupper(str_replace(",", "<br>", $partnerType)),
                strtoupper($contacts),
                $f->agency_address,
                strtoupper($f->agency_city),
                strtoupper($f->agency_state),
                $f->agency_zipcode,
                $f->agency_telephone,
                $services,
                $this->GetOrgLocationSRTCategory($f->id),
                $this->GetOrgLocationSRTItems($f->id),
                $this->GetCustomItemName($f->id),
                $this->GetOrgLocationSRTType($f->id),
                $status
            );

        }

        echo json_encode($data);
    }

    private function GetCustomItemName($id)
    {
        $dbh = $this->_db->initDB();
        $qry = "select custom_item_name from cp_custom_item_name where agency_id = :id";
        $sth = $dbh->prepare($qry);
        $sth->execute([':id' => $id]);

        $customItemName = '';
        while($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $customItemName .= $f->custom_item_name . '<br>';
        }

        return $customItemName;
    }

    private function GetOrgLocationSRTType($id)
    {
        $dbh = $this->_db->initDB();
        $qry = "select type from cp_search_items where agency_id = :id";
        $sth = $dbh->prepare($qry);
        $sth->execute([':id' => $id]);

        $type = '';
        while($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $type .= $f->type . '<br>';
        }

        return $type;
    }

    private function GetOrgLocationSRTItems($id)
    {
        $dbh = $this->_db->initDB();
        $qry = "select item from cp_search_items where agency_id = :id";
        $sth = $dbh->prepare($qry);
        $sth->execute([':id' => $id]);

        $items = '';
        while($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $items .= $f->item . '<br>';
        }

        return $items;
    }

    Private function GetOrgLocationSRTCategory($id)
    {
        $dbh = $this->_db->initDB();
        $qry = "select category from cp_search_items where agency_id = :id";
        $sth = $dbh->prepare($qry);
        $sth->execute([':id' => $id]);

        $category = '';
        while($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $category .= $f->category . '<br>';
        }

        return $category;
    }

    public function DeleteEventFile()
    {
        if (!empty($_POST['id']))
        {
            $id = $this->_db->decode($_POST['id']);
            $qry = "update cp_events_calendar_file_upload set `status` = 'DELETE' where id = '{$id}'";
            $sth = $this->_db->initDB();
            $sth->query($qry);
        }
    }


}

?>