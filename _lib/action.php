<?php

/*
 * Written by Dan David
 * File to handle mostly all forms insert,edit,login and session data
 * 
 */
session_start();

require_once(dirname(dirname(__FILE__)) . '/config/config.php');

include(ROOT . '/_lib/autoload.php');

spl_autoload_register('load_classes');


if (!isset($_GET['action'])) {
    echo "Missing Action";
    die;
}


$_core = new core();
$_db = new db();

$action = $_GET['action'];

if ($action === 'login' || $action === 'logout' || $action === 'forgot_password' || $action === 'checkInstance' || $action === 'autologin') {
    $action = $action;
} else {
    $action = $_core->decode($action);
}

$Action = new Action();

if (!method_exists($Action, $action)) {
    echo "Invalid Action " . $action;
    die;
}

$Action->$action();

class Action {

    private $currentDateTime;

    public function __construct() {
        $this->currentDateTime = date("Y-m-d H:i:s");
    }

    public function login() {
        global $_db;

        $landingPage = (!empty($_POST['landing_page'])) ? $_POST['landing_page'] : '';

        $_db->validateLogin(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL), $_POST['password'], 'org_users', $landingPage);
    }

    public function logout() {
        global $_core;

        $landingPage = (!empty($_SESSION['landing_page'])) ? $_SESSION['landing_page'] : '';

        setcookie('v', "" , time() - 3600, '/', ROOT_DOMAIN);
        setcookie('vcp', "" , time() - 3600, '/', ROOT_DOMAIN);

        $_SESSION = array();
        setcookie(session_name(), '', time() - 42000, '/', ROOT_DOMAIN);
        session_destroy();

        $e = urlencode("Session expired please Sign in.");

        /*if (!empty($landingPage))
        {
            header("Location: " . ROOT_URL . $landingPage);
        } else {
            header("Location: " . ROOT_CMS_URL . "osignin");
        }*/
        
        header("Location: " . ROOT_URL . $landingPage);

    }

    /* Autologin to Community portal Portal from CMS */
    public function autologin()
    {
        global $_db , $_core;
        $uid = $_core->gpGet('uid'); // encoded org id
        $user_id = $_core->decode($uid);
        $oid = $_core->gpGet('oid'); // encoded org id
        $org_id = $_core->decode($oid);
        $portal_type = $_core->gpGet('portal_type');
        

        $dbh = $_db->initDB();
        $sth = $dbh->query("select email, password from org_users where id = '{$user_id}'");
        $f = $sth->fetch(PDO::FETCH_OBJ);
        
        $email = $f->email;
        $saltedPassword = $f->password;
  
        if(!(isset($_SESSION['userID']) && $_SESSION['userID'])){
            // if user session does not exist then generate login session
            $_db->validateLogin($email, $pass = '', 'org_users', $landingPage = '', $saltedPassword, $login_type = 'autologin');
        }

        $_db->switchOrganization($org_id, $user_id, $portal_type);
        $_core->redir('directory/');
    }
    
    public function forgot_password() {
        global $_core, $_db;
        
        $email = $_core->gpGet('email');
        
        if ($_db->checkEmailExists($email, 'org_users')) {
            
            $salt = $_core->generateSalt();
            $pwd = crypt('r31coV3rY' ,$salt );
            
            $dbh = $_db->initDB();
            $qry = "update org_users set password = '$pwd', salt = '$salt' where email = '$email'";
            $sth = $dbh->query($qry);

            $_notification = new notification();
            $_notification->sendToEmail = $email;
            $_notification->sendEmail('reset_password');

            $_core->redir("forgot_password?e=pass");
        } else {
            $_core->redir("forgot_password?e=fail");
        }
        
    }


    public function sendBroadcast() {
        global $_core, $_db;

        $_notification = new notification();

        $qry = "select email from cp_list_all_users where ";

        $qry .= " (";

//        $filterArr = implode(",", $_POST['level_1']);
        $i = 0;
        foreach ($_POST['level_1'] as $find) {
            if ($i > 0) $qry .= " or";
            $qry .= " find_in_set('{$find}', replace(level_1, ';', ','))";
            $i++;
        }

        $qry .= " )";


        if (!empty($_POST['contact_type']))
        {
            $qry .= " and ( ";
            $x = 0;
            foreach ($_POST['contact_type'] as $findPt) {
                if ($x > 0) $qry .= " or";
                $qry .= " find_in_set('{$findPt}', replace(contact_type, ';', ','))";
                $x++;
            }
            $qry .= " )";
        }

        if (!empty($_POST['contact_license_type']))
        {
            $qry .= " and ( ";
            $j = 0;
            foreach ($_POST['contact_license_type'] as $findPt) {
                if ($j > 0) $qry .= " or";
                $qry .= " find_in_set('{$findPt}', replace(contact_license_type, ';', ','))";
                $j++;
            }
            $qry .= " )";
        }

        if (!empty($_POST['partner_type']))
        {
            $qry .= " and ( ";
            $y = 0;
            foreach ($_POST['partner_type'] as $findPt) {
                if ($y > 0) $qry .= " or";
                $qry .= " find_in_set('{$findPt}', replace(partner_type, ';', ','))";
                $y++;
            }
            $qry .= " )";
        }



        $qry .= " and user_status = 'ACTIVE' and cp_notification = 'YES' and parent_agency = '{$_SESSION['parent_agency']}'";

//        echo $qry . '<br><br>';

        $dbh = $_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute();

        $count = $sth->rowCount();

        if ($count > 0)
        {
            $emails = array();
            while ($f = $sth->fetchColumn()) {
                $emails[] = $f;
            }

            $_agency = new agency($_SESSION['agency_id']);
            $agencyName = $_agency->get_agency_name($_SESSION['agency_id']);

            $emails = implode(",", $emails);

//            echo $emails; exit;

            $_user = new users();
            $userData = $_user->getUserData($_SESSION['user_id']);

            $from = $_SESSION['user_name'] . "<br>";
            $from .= $_SESSION['user_email'];
            $from .= (!empty($userData['phone'])) ? "<br>" . $userData['phone'] : '';

            $_notification->fromEmail = $_SESSION['user_email'];
            $_notification->from = $from;
            $_notification->fromName = "{$agencyName} Broadcast";
            $_notification->addReplyTo = $_SESSION['user_email'];
            $_notification->sendToEmail = $emails;
            $_notification->broadCastSubject = $_core->gpGet('title');
            $_notification->broadCastMessage = nl2br($_core->gpGet('message'));
//            $_notification->from = $_SESSION['user_name'];

            //attachment
            if (!empty($_FILES['upload_attachment']['tmp_name'][0])) {
//                $_notification->attachmentFileName = basename($_FILES['upload_attachment']['name']);
//                $_notification->broadCastAttachment = $_FILES['upload_attachment']['tmp_name'];
                $_notification->broadCastAttachment = $_FILES;
            }

            $_notification->sendEmail('send_broadcast');

            $_core->redir("directory/send_broadcast?e=success");

        } else {

            $_core->redir("directory/send_broadcast?e=nocontacts");

        }


    }

    public function searchServices() {
        global $_core, $_db;

        $q = '';
        if (!empty($_core->gpGet('service_id'))) {
            $q .= "&sid=" . $_core->gpGet('service_id');
        }

        if (!empty($_core->gpGet('terrebonne'))) {
            $q .= "&t=yes";
        }

        if (!empty($_core->gpGet('lafourche'))) {
            $q .= "&l=yes";
        }

        $_core->redir("directory/agency_services" . $q);
    }
    
    public function searchServicesPublic() {
        global $_core, $_db;

        $q = '';
        if (!empty($_core->gpGet('service_id'))) {
            $q .= "&sid=" . $_core->gpGet('service_id');
        }

        if (!empty($_core->gpGet('terrebonne'))) {
            $q .= "&t=yes";
        }

        if (!empty($_core->gpGet('lafourche'))) {
            $q .= "&l=yes";
        }

        $_core->redir("agency_services" . $q);
    }

    public function addAgencyService() {
        global $_core, $_db;

        $agency_id = $_core->decode($_core->gpGet('agency_id'));
        unset($_POST['agency_id']);

        $dbh = $_db->initDB();

        //Terrebonne
        if (!empty($_core->gpGet('terrebonne'))) {
            foreach ($_POST['terrebonne'] as $service_id => $ter) {

                $data = array(
                    "service_id" => $service_id,
                    "agency_id" => $agency_id,
                    "terrebonne" => $ter,
                    "updated_by" => $_SESSION['user_id'],
                    "updated_date" => $this->currentDateTime
                );

                $merge_data = array_merge($id, $data);

                $_db->insertUpdateSQL($merge_data, 'cp_services_directory');
            }
        }


        //Lafourche
        if (!empty($_core->gpGet('lafourche'))) {
            foreach ($_POST['lafourche'] as $service_id => $laf) {

                $data = array(
                    "service_id" => $service_id,
                    "agency_id" => $agency_id,
                    "lafourche" => $laf,
                    "updated_by" => $_SESSION['user_id'],
                    "updated_date" => $this->currentDateTime
                );

                $_db->insertUpdateSQL($data, 'cp_services_directory');
            }
        }


        //comments
        if (!empty($_core->gpGet('comments'))) {
            foreach ($_core->gpGet('comments') as $service_id => $com) {

                $data = array(
                    "service_id" => $service_id,
                    "agency_id" => $agency_id,
                    "comments" => $com,
                    "updated_by" => $_SESSION['user_id'],
                    "updated_date" => $this->currentDateTime
                );

                $_db->insertUpdateSQL($data, 'cp_services_directory');
            }
        }

        $_core->redir("directory/agency_summary?id=" . $_core->encode($agency_id));
    }

    public function editAgencyServices() {
        global $_core, $_db;

        $agency_id = $_core->decode($_core->gpGet('agency_id'));
        unset($_POST['agency_id']);

        $dbh = $_db->initDB();

        //Terrebonne
        if (!empty($_core->gpGet('terrebonne'))) {
            foreach ($_POST['terrebonne'] as $service_id => $ter) {
//                if (!empty($ter)) {
                $sid = $this->get_pkid_services($service_id, $agency_id);
                $id = (!empty($sid)) ? array("id" => $sid) : array();
                $data = array(
                    "service_id" => $service_id,
                    "agency_id" => $agency_id,
                    "terrebonne" => $ter,
                    "updated_by" => $_SESSION['user_id'],
                    "updated_date" => $this->currentDateTime
                );

                $merge_data = array_merge($id, $data);

                $_db->insertUpdateSQL($merge_data, 'cp_services_directory');
//                }
            }
        }


        //Lafourche
        if (!empty($_core->gpGet('lafourche'))) {
            foreach ($_POST['lafourche'] as $service_id => $laf) {
//                if (!empty($laf)) {
                $sid = $this->get_pkid_services($service_id, $agency_id);
                $id = (!empty($sid)) ? array("id" => $sid) : array();
                $data = array(
                    "service_id" => $service_id,
                    "agency_id" => $agency_id,
                    "lafourche" => $laf,
                    "updated_by" => $_SESSION['user_id'],
                    "updated_date" => $this->currentDateTime
                );

                $merge_data = array_merge($id, $data);

                $_db->insertUpdateSQL($merge_data, 'cp_services_directory');
//                }
            }
        }


        //comments
        if (!empty($_core->gpGet('comments'))) {
            foreach ($_core->gpGet('comments') as $service_id => $com) {
//                if (!empty($com)) {
                $sid = $this->get_pkid_services($service_id, $agency_id);
                $id = (!empty($sid)) ? array("id" => $sid) : array();
                $data = array(
                    "service_id" => $service_id,
                    "agency_id" => $agency_id,
                    "comments" => $com,
                    "updated_by" => $_SESSION['user_id'],
                    "updated_date" => $this->currentDateTime
                );

                $merge_data = array_merge($id, $data);

                $_db->insertUpdateSQL($merge_data, 'cp_services_directory');
//                }
            }
        }

        $_core->redir("directory/agency_summary?id=" . $_core->encode($agency_id));
    }

    private function get_pkid_services($service_id, $agency_id) {
        global $_core, $_db;
        $dbh = $_db->initDB();
        $qry = "select id from cp_services_directory where service_id = '{$service_id}' and agency_id = '{$agency_id}'";
        $sth = $dbh->query($qry);
        return $sth->fetchColumn();
    }

    public function addVolunteer() {
        global $_core, $_db;
        

        //ADD VOLUNTEER TO DB
        $categories = implode(";", $_POST['category']);
        unset($_POST['category']);

        $merge = array(
            "categories" => $categories,
            "status" => "NEW VOLUNTEER",
            "submitter_ip" => $_SERVER['REMOTE_ADDR'],
            "submitted_date" => $this->currentDateTime
        );

        $data = array_merge($_POST, $merge);

        $_db->insertUpdateSQL($data, 'cp_volunteers');


        //NOTIFICATIONS
        $_notification = new notification();

        //NOTIFY VOLUNTEER
        $_notification->sendToEmail = $_POST['email'];
        $_notification->sendEmail('volunteer_registration');
        
        //NOTIFY AGENCIES
        $dbh = $_db->initDB();
        $qry = "select org_users.email as contact_email
        from org_users 
        join org_contacts oc on oc.user_id = org_users.id
        where (oc.cp_contact_type like ('%VOLUNTEER COORDINATOR%') 
        or oc.cp_contact_type like ('%PRIMARY CONTACT%')) 
        and oc.status = 'ACTIVE' ";
        $sth = $dbh->query($qry); 

        $emails = array();
        while ($f = $sth->fetchColumn()) {
            $emails[] = $f;
        }

        $emails = implode(",", $emails);
        
        $volInfo = $_POST['first_name'] . " " . $_POST['last_name'] . "<br>";
        $volInfo .= $_POST['address'] . "<br>";
        $volInfo .= $_POST['city'] . " " . $_POST['state'] . " " . $_POST['zipcode'] . "<br>";
        $volInfo .= $_POST['phone'] . "<br>";
        $volInfo .= $_POST['email'] . "<br>";
        $volInfo .= "Start Date: " . date('M-d-Y', strtotime($_POST['start_date'])) . " - End Date: " .date('M-d-Y', strtotime($_POST['end_date'])) . "<br><br>";
        
        $volInfo .= "Volunteer Selected Catagories:";
        $volInfo .= "<ul>";
        foreach (explode(";", $categories) as $val) {
            $volInfo .= "<li>{$val}</li>";
        }
        $volInfo .= "</ul>";

        $_notification->sendToEmail = $emails;
        $_notification->volunteer_info = $volInfo;
        $_notification->sendEmail('agency_new_volunteer');
        //END NOTIFICATIONS


        $err = urlencode("Success");
        $_core->redir("volunteer?e={$err}");
    }

    public function editVolunteer() {
        global $_core, $_db;

        $categories = implode(";", $_POST['category']);
        unset($_POST['category']);

        $id = $_core->decode($_core->gpGet('vid'));
        unset($_POST['vid']);

        $merge = array(
            "id" => $id,
            "categories" => $categories,
            "updated_date" => $this->currentDateTime,
            "updated_by" => $_SESSION['user_id']
        );

        $data = array_merge($_POST, $merge);

        $_db->insertUpdateSQL($data, 'cp_volunteers');

        $err = urlencode("Updated!");
        $_core->redir("directory/edit_volunteer?id={$_core->encode($id)}&e={$err}");
    }

    public function editCommittee() {
        global $_core, $_db;

        $committee_id = $_core->decode($_core->gpGet('committee_id'));
        unset($_POST['committee_id']);

        $data = array_merge(array("committee_id" => $committee_id), $_POST);

        $_db->insertUpdateSQL($data, "cp_committee_list");

        if ($_core->gpGet('committee_status') === 'DELETED') {
            $_core->redir("directory/committee_directory");
        } else {
            $_core->redir("directory/committee_summary?id=" . $_core->encode($committee_id));
        }
    }

    public function addCommittee() {
        global $_core, $_db;

        $link = "directory/committee_summary?id=";

        if ($_core->gpGet('add_committee_contacts') === 'yes') {
            $link = "directory/add_committeecontact?id=";
            unset($_POST['add_committee_contacts']);
        }

        $data = array_merge(array("committee_status" => "ACTIVE"), $_POST);

        $_db->insertUpdateSQL($data, "cp_committee_list");
        $cid = $_db->last_inserted_id;

        $_core->redir(($link . $_core->encode($cid)));
    }

    public function delCommitteeContact() {
        global $_core, $_db;

        $dbh = $_db->initDB();
        $sth = $dbh->prepare("delete from cp_committee_contact where committee_contact_id = :committee_contact_id");
        $sth->execute(array(":committee_contact_id" => $_core->gpGet('committee_contact_id')));

        $_core->redir('directory/committee_summary?id=' . $_core->gpGet('committee_id'));
    }

    public function addCommitteeContact() {
        global $_core, $_db;

        $committee_id = $_core->decode($_core->gpGet('committee_id'));
        unset($_POST['committee_id']);

        $dbh = $_db->initDB();

        if (count($_core->gpGet('contact_id')) >= 1) {
            foreach ($_POST['contact_id'] as $val) {
                $qry = "insert into cp_committee_contact (`committee_id`,`contact_id`) values ('$committee_id','$val')";
                $dbh->query($qry);
            }
        }

        $_core->redir('directory/committee_summary?id=' . $_core->encode($committee_id));
    }

//    public function addAgencyInfo() {
//        global $_core, $_db;
//
//        $data = array_merge(array("status" => "ACTIVE", "agency_level" => "1", "parent_agency" => $_SESSION['agency_id']), $_POST);
//
//        $r = $_db->insertUpdateSQL($data, 'cp_directory_agency');
//
//        if (!$r) {
//            echo $r;
//            exit;
//        } else {
//            $id = $_db->last_inserted_id;
//            $_core->redir('directory/add_agencycontacts?id=' . $_core->encode($id) . '&lvl=' . $_core->encode($_core->gpGet('level_1')));
//        }
//    }

//    public function editAgencyInfo() {
//        global $_core, $_db;
//
//        $agency_id = $_core->decode($_core->gpGet('agency_id'));
//        unset($_POST['agency_id']);
//
//        $data = array_merge(array("agency_id" => $agency_id), $_POST);
//
//        $_db->insertUpdateSQL($data, 'cp_directory_agency');
//
//
//        //FILE UPLOAD
//        if (!empty($_FILES['upload_logo']['tmp_name'])) {
//
//            $upload_dir = UPLOAD_DIR . "agency_logos/" . $agency_id . "/";
//
//            if (!file_exists($upload_dir)) {
//                mkdir($upload_dir);
//            }
//
//            $clean_name = array(" ", "(", ")", "[", "]", "-");
//            $file_name = str_replace($clean_name, "_", basename($_FILES['upload_logo']['name']));
//            $file_path = $upload_dir . $file_name;
//
////            if (move_uploaded_file($_FILES['upload_logo']['tmp_name'], $file_path)) {
//            $upload = new upload_image();
//
//            $upload->load($_FILES['upload_logo']['tmp_name']);
//            $upload->resizeToWidth(150);
//            $upload->save($file_path);
//
//            $type = $_FILES['upload_logo']['type'];
//
//            $data = array(
//                "agency_id" => $agency_id,
//                "type" => $type,
//                "filename" => $file_name,
//                "date_uploaded" => $this->currentDateTime,
//                "uploaded_by" => $_SESSION['user_id']
//            );
//
//            $_db->insertUpdateSQL($data, 'cp_agency_logo');
//
////            }
//        }
//
//        $_core->redir("directory/agency_summary?id=" . $_core->encode($agency_id));
//    }


    public function checkInstance(){
        global $_core, $_db;
        $instance_url = $_core->gpGet('instance'); // instance name / URL

        $dbh = $_db->initDB();
        $qry = "select * from cp_agency_instance where custom_url='$instance_url' ";
        $sth = $dbh->query($qry);
        $rowCount = $sth->rowCount();
        echo $rowCount;
    }

    public function del_logo() {
        global $_core, $_db;

        $agency_id = $_core->decode($_core->gpGet('lid'));
        $dbh = $_db->initDB();
        $qry = "delete from cp_agency_logo where agency_id = :agency_id";
        $sth = $dbh->prepare($qry);
        $sth->execute(array(":agency_id" => $agency_id));

        $_core->redir("directory/agency_summary?id=" . $_core->encode($agency_id));
    }

    public function addAgencyContact() {
        global $_core, $_db;

        $agency_id = $_core->decode($_core->gpGet('agency_id'));
        unset($_POST['agency_id']);

        $contact_type = join(";", $_POST['contact_type']);
        unset($_POST['contact_type']);

        $link = "directory/add_agencyservices?id=" . $_core->encode($agency_id);

        if ($_core->gpGet('add_another_contact') === 'yes') {
            unset($_POST['add_another_contact']);
            $err = urlencode($_POST['first_name'] . ' ' . $_POST['first_name'] . " has been added");
            $link = "directory/add_agencycontacts?id=" . $_core->encode($agency_id) . "&err=" . $err;
        }


        //UPDATED FOR CP
//        $salt = $_core->generateSalt();
//        $temp_password = $_core->randomPassword();
        $temp_password = 'R3coVery1';
        $salt = $_core->generateSalt();
        $password = crypt($temp_password, $salt);

//        $data_merge = array(
//            "agency_id" => $agency_id,
//            "user_type" => "ADMIN",
//            "user_status" => "ACTIVE",
//            "password" => $password,
//            "salt" => $salt,
//            "contact_type" => $contact_type
//        );

        $data = array(
            'first_name' => filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING),
            'last_name' => filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'phone' => $_POST['phone'],
            'alt_phone' => $_POST['alt_phone'],
            'community_portal' => 1,
            'case_management' => 0,
            "status" => "ACTIVE",
            "password" => $password,
            "salt" => $salt,
            'created_date' => $this->currentDateTime,
            'created_by' => 'AG_' . $_SESSION['userID'],
            'ip_addr' => $_SERVER['REMOTE_ADDR'],
            'agency_level' => '1'
        );


//        $data = array_merge($data_merge, $_POST);

//        $_db->insertUpdateSQL($data, 'cp_directory_contact');

        if (!empty($agency_id))
        {
            $_db->insertUpdateSQL($data, 'org_users');

            $user_id = $_db->last_inserted_id;
            // save data in cp_directory_contact
            $org_cp_directory_contact = [
                'cp_org_id'=> $agency_id,
                'user_id'=> $user_id, 
                'status'=> "ACTIVE", 
                'cp_community_portal_user_type'=>'ADMIN',
                'cp_level_1'=>$_core->decode($_core->gpGet('lvl')),
                'contact_license_type'=>$licenseType,
                'cp_contact_type'=>$contact_type,
                'cp_access' => 1,
                'cms_access' => 0,
            ];
            $_db->insertUpdateSQL($org_cp_directory_contact, 'org_contacts');

        } else {
            die('OOPS: Error 0001. Contact System Administrator');
        }


        $_core->redir($link);
    }

    public function addAgencyContact_s() {
        global $_core, $_db;

        $agency_id = $_core->decode($_core->gpGet('agency_id'));
        unset($_POST['agency_id']);

        $contact_type = join(";", $_POST['contact_type']);
        unset($_POST['contact_type']);

        $link = "directory/agency_summary?id=" . $_core->encode($agency_id);

//        $salt = $_core->generateSalt();

//        $data_merge = array(
//            "agency_id" => $agency_id,
//            "user_type" => "USER",
//            "user_status" => "ACTIVE",
//            "password" => crypt('recovery', $salt),
//            "salt" => $salt,
//            "contact_type" => $contact_type
//        );
//
//        $data = array_merge($data_merge, $_POST);
//
//        $_db->insertUpdateSQL($data, 'cp_directory_contact');

//        $temp_password = $_core->randomPassword();
        $temp_password = 'R3coVery1';
        $salt = $_core->generateSalt();
        $password = crypt($temp_password, $salt);

        $data = array(
            'first_name' => filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING),
            'last_name' => filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING),
            'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
            'phone' => $_POST['phone'],
            'alt_phone' => $_POST['alt_phone'],
            "password" => $password,
            "salt" => $salt,
            'created_date' => $this->currentDateTime,
            'created_by' => 'AG_' . $_SESSION['userID'],
            'ip_addr' => $_SERVER['REMOTE_ADDR'],
        );

        if (!empty($agency_id))
        {
            $_db->insertUpdateSQL($data, 'org_users');

            $user_id = $_db->last_inserted_id;
            // save data in cp_directory_contact
            $org_cp_directory_contact = [
                'cp_org_id' => $agency_id,
                'user_id' => $user_id, 
                'status' => "ACTIVE", 
                'cp_user_level' => 0,
                'cp_community_portal_user_type' => 'ADMIN',
                'cp_contact_type' => $contact_type,
                'cp_access' => 1
            ];
            $_db->insertUpdateSQL($org_cp_directory_contact, 'org_contacts');
            
        } else {
            die('OOPS: Error 0002. Contact System Administrator');
        }

        $_core->redir($link);
    }

//    public function editAgencyContact() {
//        global $_core, $_db;
//
//        $contact_id = $_core->decode($_core->gpGet('contact_id'));
//        $agency_id = $_core->gpGet('agency_id');
//        $contact_type = join(";", $_POST['contact_type']);
//        unset($_POST['contact_type']);
//        unset($_POST['contact_id']);
//        unset($_POST['agency_id']);
//
//        $link = "directory/agency_summary?id={$agency_id}";
//
//        $data = array_merge(array("contact_id" => $contact_id, "contact_type" => $contact_type), $_POST);
//
//
//        $_db->insertUpdateSQL($data, 'cp_directory_contact');
//
//        $_core->redir($link);
//    }

    public function postMessage() {
        global $_core, $_db;

        $parentAgencyId = $_db->SetParentAgencyId();
        $level1 = implode(";",$_POST['level_1']);

        $data = array(
            "agency_id" => $_SESSION['agency_id'],
            "parent_agency_id" => $parentAgencyId,
            "title" => $_POST['title'],
            "message" => $_POST['message'],
            "timestamp" => $this->currentDateTime,
            "submitted_by" => $_SESSION['user_name'],
            "status" => "ACTIVE",
            "level_1" => $level1
        );

        $_db->insertUpdateSQL($data, 'cp_message_board');
        $messageId = $_db->last_inserted_id;

        if (!empty($_FILES['upload_attachment']['tmp_name'][0]))
        {
            //UPDATED to handle multiple attachments
            $this->UploadFiles($_FILES, $messageId, 'message');
        }

        $_core->redir('directory');
    }

    public function editMessage() {
        global $_core, $_db;

        $level1 = implode(";",$_POST['level_1']);
        $encodedMsgId = $_core->gpGet('id');
        $messageId = $_core->decode($encodedMsgId);

        $data = array(
            "id" => $messageId,
            "title" => $_POST['title'],
            "message" => $_POST['message'],
            "status" => $_POST['status'],
            "level_1" => $level1,
            "update_by" => $_SESSION['user_id'],
            "update_date" => $this->currentDateTime
        );

        $_db->insertUpdateSQL($data, 'cp_message_board');

        if (!empty($_FILES['upload_attachment']['tmp_name'][0]))
        {
            //UPDATED to handle multiple attachments
            $this->UploadFiles($_FILES, $messageId, 'message');
        }

        $_core->redir("directory/edit_messageboard?id=$encodedMsgId&e=Saved");
    }

    public function addEvent() {

        global $_core, $_db;

        $parentAgencyId = $_db->SetParentAgencyId();
        $level1 = implode(";",$_POST['level_1']);

        $eventDate = DateTime::createFromFormat('m-d-Y', $_POST['event_date']);
        $eventDateClean = $eventDate->format('Y-m-d');

        $data = array(
            "event_title" => $_POST['event_title'],
            "event_description" => $_POST['event_description'],
            "event_address" => $_POST['event_address'],
            "event_city" => $_POST['event_city'],
            "event_state" => $_POST['event_state'],
            "event_zip" => $_POST['event_zip'],
            "event_date" => $eventDateClean,
            "event_time" => $_POST['t1'] . ":" . $_POST['t2'] . " " . $_POST['t3'],
            "event_end_time" => $_POST['t1_end'] . ":" . $_POST['t2_end'] . " " . $_POST['t3_end'],
            "url" => $_POST['url'],
            "timezone" => $_POST['timezone'],
            "status" => "ACTIVE",
            "submitted_by" => $_SESSION['user_name'],
            "timestamp" => $this->currentDateTime,
            "level_1" => $level1,
            "agency_id" => $_SESSION['agency_id'],
            "parent_agency_id" => $parentAgencyId
        );


        //REOCCURING SECTION
        if(isset($_POST['is_reoccuring']) && $_POST['is_reoccuring']=='1'){
            $data['is_reoccuring'] = '1';
            $data['event_reoccuring_type'] = $_POST['event_reoccuring_type'];

            $endDate = explode('-',$_POST['event_end_date']);
            $data['event_end_date'] = $endDate[2].'-'.$endDate[0].'-'.$endDate[1];

            //FOR WEEKLY DAY
            if($_POST['event_reoccuring_type']=='Weekly Day' ){
                $data['event_reoccuring_days'] = implode(',',$_POST['event_reoccuring_days']);
            }

        }

        $_db->insertUpdateSQL($data, 'cp_events_calendar');
        $eventId = $_db->last_inserted_id;



        if (!empty($_FILES['upload_attachment']['tmp_name'][0]))
        {
            //UPDATED to handle multiple attachments
            $this->UploadFiles($_FILES, $eventId, 'events');
        }

        $_core->redir('directory');
    }

    public function editEvent() {
        global $_core, $_db;

        $encodedId = $_core->gpGet('id');
        $eventId = $_core->decode($encodedId);
        $level1 = implode(";",$_POST['level_1']);

        $eventDate = DateTime::createFromFormat('m-d-Y', $_POST['event_date']);
        $eventDateClean = $eventDate->format('Y-m-d');

        $data = array(
            "id" => $eventId,
            "event_title" => $_POST['event_title'],
            "event_description" => $_POST['event_description'],
            "event_address" => $_POST['event_address'],
            "event_city" => $_POST['event_city'],
            "event_state" => $_POST['event_state'],
            "event_zip" => $_POST['event_zip'],
            "event_date" => $eventDateClean,
            "timezone" => $_POST['timezone'],
            "event_time" => $_POST['t1'] . ":" . $_POST['t2'] . " " . $_POST['t3'],
            "event_end_time" => $_POST['t1_end'] . ":" . $_POST['t2_end'] . " " . $_POST['t3_end'],
            "url" => $_POST['url'],
            "status" => $_POST['status'],
            "update_by" => $_SESSION['user_id'],
            "update_date" => $this->currentDateTime,
            "timestamp" => $this->currentDateTime,
            "level_1" => $level1
        );

        //REOCCURING SECTION
        if(isset($_POST['is_reoccuring']) && $_POST['is_reoccuring']=='1'){
            $data['is_reoccuring'] = '1';
            $data['event_reoccuring_type'] = $_POST['event_reoccuring_type'];
            

            $endDate = explode('-',$_POST['event_end_date']);
            $data['event_end_date'] = $endDate[2].'-'.$endDate[0].'-'.$endDate[1];
            
            //FOR WEEKLY DAY
            if($_POST['event_reoccuring_type']=='Weekly Day' ){
                $data['event_reoccuring_days'] = implode(',',$_POST['event_reoccuring_days']);
            }
            else{
                $data['event_reoccuring_days'] = NULL;
            }

        }
        else{
            $data['is_reoccuring'] = '0';
            $data['event_reoccuring_type'] = NULL;            
            $data['event_reoccuring_days'] = NULL;
        }

        $_db->insertUpdateSQL($data, 'cp_events_calendar');

        if (!empty($_FILES['upload_attachment']['tmp_name'][0]))
        {
            //UPDATED to handle multiple attachments
            $this->UploadFiles($_FILES, $eventId, 'events');
        }
        
        $_core->redir("directory/edit_event?id=$encodedId&e=Saved");
    }



    public function adminPassword() {
        global $_core, $_db;

        $salt = $_core->generateSalt();
        $password = $_core->gpGet('password');

        $data_array = array(
            'email' => 'admin@admin',
            'password' => crypt($password, $salt),
            'salt' => $salt,
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'type' => 'ADMIN',
            'status' => 'ACTIVE'
        );

        $r = $_db->insertUpdateSQL($data_array, 'users');

        if (!$r) {
            echo $r;
        } else {
            echo "<p>Setup Complete!</p>";
        }
    }

    public function editUser() {
        global $_core, $_db;

        $id = $_core->decode($_POST['id']);
        $encoded_id = $_POST['id'];
        unset($_POST['id']);

        $data = array_merge(array("id" => $id), $_POST);

        $r = $_db->insertUpdateSQL($data, 'users');

        if (!$r) {
            $error = urlencode('ERROR: Database Error!');
        } else {
            $error = urlencode('user updated!');
        }

        $_core->redir('directory/edituser&id=' . $encoded_id . '&e=' . $error);
    }

    public function editUserPassword() {
        global $_core, $_db;

        $salt = $_core->generateSalt();
        $password = $_core->gpGet('password');

        $data = array(
            'id' => $_SESSION['user_id'],
            'password' => crypt($password, $salt),
            'salt' => $salt
        );

        $_db->insertUpdateSQL($data, 'org_users');

        $error = urlencode('Password Updated');
        $_core->redir('directory/index&e=' . $error);
    }

    private function debugData($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit;
    }

    public function UpdatePassword()
    {
        global $_core, $_db;

        $salt = $_core->generateSalt();
        $password = crypt($_POST['password'], $salt);

        $data = [
            'id' => $_SESSION['userID'],
            'password' => $password,
            'salt' => $salt,
            'ip_addr' => $_SERVER['REMOTE_ADDR']
        ];

//        $this->debugData($_POST);

        $_db->insertUpdateSQL($data, 'org_users');

        $err = urlencode("Password Updated");
        $_core->redir('directory/editmyprofile?e=' . $err);
    }

    public function UpdateMyProfile()
    {
        global $_db;

        $level1 = implode(";",$_POST['level_1']);
        $user_id = $_SESSION['user_id'];
        $default_agency_id = '';
        $default_homescreen = isset($_POST['default_homescreen']) ? $_POST['default_homescreen'] : '';
        $data = [
            'id' => $user_id,
            //'default_agency_id' => $default_agency_id,
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'phone' => $_POST['phone'],
            'alt_phone' => $_POST['alt_phone'],
            //'level_1' => $level1,
            'updated_by' => $user_id,
            'updated_date' => $this->currentDateTime,
            //'cp_notification' => $_POST['cp_notification']
        ];

        if($default_homescreen){
            $temp = explode('_',$default_homescreen);
            $default_portal_type = $temp[0];
            $homescreen_org_id = $temp[1];

            $data['default_portal_type'] = $default_portal_type;
            if($default_portal_type == 'CP'){
                $data['default_agency_id'] = $homescreen_org_id;
                $default_agency_id = $homescreen_org_id;
            }
            if($default_portal_type == 'CMS'){
                $data['default_org_id'] = $homescreen_org_id;
            }
            
            $data['homescreen_org_id'] = $homescreen_org_id;
        }

        $_db->insertUpdateSQL($data, 'org_users');

        $_SESSION['level_1_filter'] = $level1;

        if($default_agency_id){
            // if default agency id exist then update lvel_1 and notification data in contacts table and update SESSION values
            $dbh = $_db->initDB();
            $qry = "SELECT c.id , c.cp_user_level, c.cp_access_level , c.cp_community_portal_user_type, c.cms_access , c.cp_access
                    FROM org_users as u 
                    LEFT JOIN org_contacts as c ON u.id =  c.user_id  
                    WHERE u.id = {$user_id} and cp_org_id = {$default_agency_id} and cp_org_id IS NOT NULL";
            $sth = $dbh->query($qry);
            $f = $sth->fetch(PDO::FETCH_OBJ);
            // update session
            /*$_SESSION['userLevel'] = $f->cp_access_level;
            $_SESSION['cp_access'] = $f->cp_access;
            $_SESSION['cms_access'] = $f->cms_access;
            $_SESSION['user_type'] = $f->cp_community_portal_user_type;
            $_SESSION['level_1'] = $level1;
            $_SESSION['cp_user_level'] = $f->cp_user_level;
            $_SESSION['agency_id'] = $default_agency_id;*/

            // update org_contacts table
            $org_contacts_id = $f->id;
            $u_data = [
                'id' => $org_contacts_id,
                'cp_level_1' => $level1,
                'cp_notification' => $_POST['cp_notification']
            ];   
           
            $_db->insertUpdateSQL($u_data, 'org_contacts');
    
        }

        $err = urlencode("Profile Updated");
        $_db->redir('directory/editmyprofile?e=' . $err);
    }

    public function AddLink()
    {
        global $_db;

        if(!preg_match('#^https?://#', $_POST['url'])){
            $url = 'http://' .  $_POST['url'];
        } else {
            $url = $_POST['url'];
        }

        $parentAgencyId = $_db->SetParentAgencyId();
        $level1 = implode(";",$_POST['level_1']);

        $data = [
            'agency_id' => $_SESSION['agency_id'],
            'parent_agency_id' => $parentAgencyId,
            'title' => $_POST['title'],
            'url' => $url,
            'description' => $_POST['description'],
            'status' => 'ACTIVE',
            'level_1' => $level1,
            'update_by' => $_SESSION['user_id'],
            'update_date' => $this->currentDateTime,
        ];

//        $this->debugData($data);

        $_db->insertUpdateSQL($data, 'cp_dashboard_links');

        $_db->redir('directory');
    }

    public function EditLink()
    {
        global $_db;

        if(!preg_match('#^https?://#', $_POST['url'])){
            $url = 'http://' .  $_POST['url'];
        } else {
            $url = $_POST['url'];
        }

        $level1 = ($_db->gpGet('loc') === 'dash') ? implode(";",$_POST['level_1']) : '';

        $data = [
            'id' => $_db->decode($_db->gpGet('lid')),
            'title' => $_POST['title'],
            'url' => $url,
            'description' => $_POST['description'],
            'status' => $_POST['status'],
            'update_by' => $_SESSION['user_id'],
            'update_date' => $this->currentDateTime,
        ];

        if ($_db->gpGet('loc') === 'dash')
        {
            $data = array_merge($data, ['level_1' => $level1]);
        }


        $table = ($_db->gpGet('loc') === 'dash') ? "cp_dashboard_links" : "cp_team_dashboard_links";

        $_db->insertUpdateSQL($data, $table);

        $_db->redir('directory/edit_link?id=' . $_db->gpGet('lid') . '&loc=' . $_db->gpGet('loc') . '&e=Saved');
    }

    public function AddDocument()
    {
        global $_db;

        if (!empty($_FILES['upload_docs']['tmp_name'])) {

            $upload_dir = UPLOAD_DIR . "dashboard/" . $_SESSION['parent_agency'] . "/";

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir,0777,true);
            }

            $clean_name = array(" ", "(", ")", "[", "]", "-");
            $file_name = str_replace($clean_name, "_", basename($_FILES['upload_docs']['name']));
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['upload_docs']['tmp_name'], $file_path)) {

                $type = $_FILES['upload_docs']['type'];

                $level1 = implode(";",$_POST['level_1']);

                $data = array(
                    "agency_id" => $_SESSION['agency_id'],
                    "parent_agency" => $_SESSION['parent_agency'],
                    "type" => $type,
                    "file_name" => $file_name,
                    "title" => $_POST['title'],
                    "description" => $_POST['description'],
                    "upload_date" => $this->currentDateTime,
                    "upload_by" => $_SESSION['user_id'],
                    "status" => 'ACTIVE',
                    "level_1" => $level1
                );

                $_db->insertUpdateSQL($data, 'cp_file_upload');

                $_db->redir('directory/');

            }
        }
    }

    public function EditDocument()
    {
        global $_db;
        $docId = $_db->gpGet('did');
        $data = array(
            "id" => $_db->decode($docId),
            "title" => $_POST['title'],
            "description" => $_POST['description'],
            "upload_date" => $this->currentDateTime,
            "upload_by" => $_SESSION['user_id'],
            "status" => $_POST['status']
        );


        if ($_db->gpGet('loc') === 'dash')
        {
            $level1 = ($_db->gpGet('loc') === 'dash') ? implode(";",$_POST['level_1']) : '';
            $data = array_merge($data, ['level_1' => $level1]);
        }


        $table = ($_db->gpGet('loc') === 'dash') ? "cp_file_upload" : "cp_team_file_upload";

        if (!empty($_FILES['upload_docs']['tmp_name'])) {

            $upload_dir = UPLOAD_DIR . "dashboard/" . $_SESSION['parent_agency'] . "/";

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir,0777,true);
            }

            $clean_name = array(" ", "(", ")", "[", "]", "-");
            $file_name = str_replace($clean_name, "_", basename($_FILES['upload_docs']['name']));
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['upload_docs']['tmp_name'], $file_path)) {

                $type = $_FILES['upload_docs']['type'];

                $level1 = implode(";",$_POST['level_1']);

                $fileData = array(
                    "type" => $type,
                    "file_name" => $file_name,
                );

                $data = array_merge($data, $fileData);

            }
        }

        $_db->insertUpdateSQL($data, $table);

        $_db->redir('directory/edit_docs?id=' . $_db->gpGet('did') . '&loc=' . $_db->gpGet('loc') . '&e=Saved');
    }

    private function UploadFiles($filesArr = [], $id, $section)
    {
        global $_db;

        $upload_dir = UPLOAD_DIR . "dashboard/" . $_SESSION['parent_agency'] . "/".$section."/";

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir,0777,true);
        }

        $cnt = (count($filesArr['upload_attachment']['name']) - 1);
        for ($i = 0; $i <= $cnt; $i++) {

            $clean_name = array(" ", "(", ")", "[", "]", "-");
            $file_name = str_replace($clean_name, "_", basename($filesArr['upload_attachment']['name'][$i]));
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($filesArr['upload_attachment']['tmp_name'][$i], $file_path))
            {
                $type = $filesArr['upload_attachment']['tmp_name'][$i];
                $fileData = [
                    'file_name' => $file_name,
                    'type' => $type,
                    'upload_date' => $this->currentDateTime,
                    'upload_by' => $_SESSION['user_id'],
                    'status' => 'ACTIVE'
                ];
                $fileDataInput = ($section === 'events') ? array_merge(['event_id' => $id], $fileData) : array_merge(['message_id' => $id], $fileData);

                $dbTable = ($section === 'events') ? 'cp_events_calendar_file_upload' : 'cp_message_board_file_upload';

                $_db->insertUpdateSQL($fileDataInput, $dbTable);
            }
        }
    }

    public function SetContactUs()
    {
        global $_db;
        if (isset($_POST['contact_us_email']))
        {
            $turnOn = (isset($_POST['turn_on'])) ? $_POST['turn_on'] : '';

            $data = [
                'parent_agency_id' => $_SESSION['parent_agency'],
                'contact_us_email' => $_POST['contact_us_email'],
                'turn_on' => $turnOn,
                'update_date' => $this->currentDateTime,
                'update_by' => $_SESSION['user_id']
            ];

            $_db->insertUpdateSQL($data, 'cp_contact_us');

            $_db->redir('directory');
        }
    }


}

//end action

