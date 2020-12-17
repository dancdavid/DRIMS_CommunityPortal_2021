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

$action = $_GET['action'];
$Action = new Action();

$action = $_core->decode($action);

if (!method_exists($Action, $action)) {
    echo "Invalid Action " . $_core->encode($action);
    die;
}

$Action->$action();

class Action
{

    private $currentDateTime;
    private $_db;
    private $_dbh;

    public function __construct()
    {
        $this->currentDateTime = date("Y-m-d H:i:s");
        $this->_db = new db();
        $this->_dbh = $this->_db->initDB();
    }

    public function AddAgencyInfo()
    {

        $level1 = implode(";", $_POST['level_1']);
        $partnerType = implode(";", $_POST['partner_type']);
        $data_org = $this->setOrgData($_SESSION['agency_id']);

        $data = [
            "agency_name" => $_POST['agency_name'],
            "agency_address" => $_POST['agency_address'],
            "agency_city" => $_POST['agency_city'],
            "agency_state" => $_POST['agency_state'],
            "agency_zipcode" => $_POST['agency_zipcode'],
            "agency_telephone" => $_POST['agency_telephone'],
            "agency_fax" => $_POST['agency_fax'],
            "agency_url" => $_POST['agency_url'],
            "description" => $_POST['description'],
            "status" => "ACTIVE",
            "created_by" => $_SESSION['user_id'],
            "created_date" => $this->currentDateTime,
            "agency_level" => "1",
            "parent_agency" => $_SESSION['agency_id'],
            "level_1" => $level1,
            "partner_type" => $partnerType
        ];

        $r = $this->_db->insertUpdateSQL($data, 'cp_directory_agency');

        if (!$r) {
            echo $r;
            exit;
        } else {
            $id = $this->_db->last_inserted_id; // agency_id
            //ADD ORG DETAILS
            $all_org_data = $data_org;
            $all_org_data['related_to_agency'] = $id; // agency_id
            $this->_db->insertUpdateSQL($all_org_data, 'org_information');
            $org_id = $this->_db->last_inserted_id;
            $this->_db->redir('directory/add_agencycontacts?id=' . $this->_db->encode($id) . '&oid=' . $this->_db->encode($org_id));
        }
    }

    private function setOrgData($agency_id) {

        if($agency_id < 0){
            # Parent Agency
            $org_type = 'CP Parent Org';
        }else{
            # Child Agency
            $org_type = 'CP Child Org';
        }

        $data = [
            'name' => $_POST['agency_name'],
            'type' => '',
            'org_phone' => $_POST['agency_telephone'],
            'org_fax' => $_POST['agency_fax'],
            'url' => $_POST['agency_url'],
            'address' => $_POST['agency_address'],
            'city' => $_POST['agency_city'],
            'state' => $_POST['agency_state'],
            'zipcode' => $_POST['agency_zipcode'],
            'description' => $_POST['description'],
            'status' => 'ACTIVE',
            'created_date' => $this->curTimeStamp,
            'created_by' => 'SU_' . $_SESSION['user_id'],
            'ip_addr' => $_SERVER['REMOTE_ADDR'],
            'community_portal' => 0,
            'case_management' => 0,
            'org_type' => $org_type
        ];

        return $data;
    }

    public function EditAgencyInfo()
    {
        global $_core;

        $agency_id = $_core->decode($_core->gpGet('aid'));
        $level1 = implode(";", $_POST['level_1']);
        $partnerType = implode(";", $_POST['partner_type']);

        $data = [
            "agency_id" => $agency_id,
            "agency_name" => $_POST['agency_name'],
            "agency_address" => $_POST['agency_address'],
            "agency_city" => $_POST['agency_city'],
            "agency_state" => $_POST['agency_state'],
            "agency_zipcode" => $_POST['agency_zipcode'],
            "agency_telephone" => $_POST['agency_telephone'],
            "agency_fax" => $_POST['agency_fax'],
            "agency_url" => $_POST['agency_url'],
            "description" => $_POST['description'],
            "created_by" => $_SESSION['user_id'],
            "created_date" => $this->currentDateTime,
            "level_1" => $level1,
            "partner_type" => $partnerType
        ];

        if (UserAccess::ManageLevel1()) {
            $data = array_merge($data, ['status' => $_POST['status']]);
        }

        $this->_db->insertUpdateSQL($data, 'cp_directory_agency');


        //FILE UPLOAD
        if (!empty($_FILES['upload_logo']['tmp_name'])) {

            $upload_dir = UPLOAD_DIR . "agency_logos/" . $agency_id . "/";

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir);
            }

            $clean_name = array(" ", "(", ")", "[", "]", "-");
            $file_name = str_replace($clean_name, "_", basename($_FILES['upload_logo']['name']));
            $file_path = $upload_dir . $file_name;

//            if (move_uploaded_file($_FILES['upload_logo']['tmp_name'], $file_path)) {
            $upload = new upload_image();

            $upload->load($_FILES['upload_logo']['tmp_name']);
//            $upload->resizeToWidth(320);
//            $upload->resizeToHeight(115);
            $upload->resize(350, 125);
            $upload->save($file_path);

            $type = $_FILES['upload_logo']['type'];

            $data = array(
                "agency_id" => $agency_id,
                "type" => $type,
                "filename" => $file_name,
                "date_uploaded" => $this->currentDateTime,
                "uploaded_by" => $_SESSION['user_id']
            );

            $this->_db->insertUpdateSQL($data, 'cp_agency_logo');

//            }
        }

        $_core->redir("directory/agency_summary?id=" . $_core->encode($agency_id));
    }

    /* add into cp_directory_agency, cp_directory_contacts 
       update org_information(org_type column)
    */
    public function sendInviteToExistingOrg(){
        
        // user id
        $uidEnc = $this->_db->gpGet('uid');
        $userId = $this->_db->decode($uidEnc);
        // org id
        $oidEnc = $this->_db->gpGet('oid');
        $orgId = $this->_db->decode($oidEnc);
        // agency id
        $aidEnc = $this->_db->gpGet('aid');
        $agencyId = $this->_db->decode($aidEnc);

        // check if invite already sent earlier
        $qry = "select count(*) as CNT from cp_directory_contact where agency_id = '$agencyId' and user_id = '$userId' ";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute();
        $inviteAlreadySent = $sth->fetchColumn();
        
        
        // get user email
        $qry = "select email,first_name,last_name from org_users where id = '$userId'";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute();
        $fd = $sth->fetch(PDO::FETCH_OBJ);
        $email = $fd->email;
        $first_name = $fd->first_name;
        $last_name = $fd->last_name;

        // get org data
        $qry = "select * from org_information where id = '$orgId'";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute();
        $f = $sth->fetch(PDO::FETCH_OBJ);

        if(!$agencyId){
            // if agency id does not exist then add a new agency
            $data = [
                "agency_name" => $f->name,
                "agency_address" => $f->address,
                "agency_city" => $f->city,
                "agency_state" => $f->state,
                "agency_zipcode" => $f->zipcode,
                "agency_telephone" => $f->org_phone,
                "agency_fax" => $f->org_fax,
                "agency_url" => $f->url,
                "description" => $f->description,
                "status" => "ACTIVE",
                "created_by" => $_SESSION['user_id'],
                "created_date" => $this->currentDateTime,
                "agency_level" => "1",
                "parent_agency" => $_SESSION['agency_id']
            ];

            $r = $this->_db->insertUpdateSQL($data, 'cp_directory_agency');
            $agencyId = $this->_db->last_inserted_id;

            $agency_id = $_SESSION['agency_id'];
            if($agency_id < 0){
                # Parent Agency
                $org_type = 'CP Parent Org';
            }else{
                # Child Agency
                $org_type = 'CP Child Org';
            }
            $this->_db->insertUpdateSQL(['id' => $orgId, 'org_type' => $org_type, 'related_to_agency' => $agencyId ], 'org_information');
        }

        if($inviteAlreadySent){
            $this->_db->redir('directory/add_agency?e=Invite Already Sent before');
        }

        // save data in cp_directory_contact
        $org_cp_directory_contact = ['agency_id'=> $agencyId, 'user_id' => $userId];
        $this->_db->insertUpdateSQL($org_cp_directory_contact, 'cp_directory_contact');

        //NOTIFICATION
        $_agency = new agency();

        $_user = new users();
        $userData = $_user->getUserData($_SESSION['user_id']);

        $from = $_SESSION['user_name'] . "<br>";
        $from .= $_SESSION['user_email'];
        $from .= (!empty($userData['phone'])) ? "<br>" . $userData['phone'] : '';

        $_notification = new notification();
        $_notification->from = $from;
        $_notification->fromName = $_SESSION['user_name'];
        $_notification->fromEmail = $_SESSION['user_email'];
        $_notification->agencyName = $_agency->get_agency_name($agencyId);
        $_notification->sendToName = $first_name . ' ' . $last_name;
        $_notification->sendToEmail = $email;
        $_notification->tempPassword = 'You already have your Login Password';
        $_notification->sentByName = $_SESSION['user_name'];
        $_notification->sentByAgency = $_agency->get_agency_name($_SESSION['agency_id']);
        $_notification->sendEmail('add_agency_user');
        
        $this->_db->redir('directory/add_agency?m=Invite Sent Successfully');
    }


    /* add into cp_directory_contacts , org_contacts
       update org_information(org_type column)
    */
    public function sendInviteToExistingContact(){
        
        // user id
        $uidEnc = $this->_db->gpGet('uid');
        $userId = $this->_db->decode($uidEnc);

        $aidEnc = $this->_db->gpGet('aid');
        $agencyId = $this->_db->decode($aidEnc);

        $orgIdEnc = $this->_db->gpGet('oid');

        // get user email
        $qry = "select id,email,first_name,last_name,user_level,status from org_users where id = '$userId'";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute();
        $fd = $sth->fetch(PDO::FETCH_OBJ);

        $user_id = $fd->id;
        $email = $fd->email;
        $first_name = $fd->first_name;
        $last_name = $fd->last_name;
        $user_status = $fd->status;
        $user_level = $fd->user_level;

        if($orgIdEnc){
            // update org_user ID in org_information table
            $org_id = $this->_db->decode($orgIdEnc);
            $org_admin_user_id = $user_id;

            if($user_level == 'ADMIN'){

                // check if admin alredy exist
                $qry = "select org_admin_id  from org_information where id = '$org_id'";
                $sth = $this->_dbh->prepare($qry);
                $sth->execute();
                $fd = $sth->fetch(PDO::FETCH_OBJ);
                $org_admin_id = $fd->org_admin_id;
                if(!$org_admin_id){
                    // If no ADMIN exist for the Org
                    $this->_db->insertUpdateSQL(['id' => $org_id, 'org_admin_id' => $org_admin_user_id], 'org_information');
                }
            }
            
            // save data in org_contacts table
            $org_contacts_data = ['org_id'=> $org_id, 'user_id'=> $org_admin_user_id, 'status'=> $user_status, 'user_level'=> $user_level];
            $this->_db->insertUpdateSQL($org_contacts_data, 'org_contacts');    
        
        }

        // save data in cp_directory_contact
        $org_cp_directory_contact = ['agency_id'=> $agencyId, 'user_id' => $userId];
        $this->_db->insertUpdateSQL($org_cp_directory_contact, 'cp_directory_contact');

        //NOTIFICATION
        $_agency = new agency();

        $_user = new users();
        $userData = $_user->getUserData($_SESSION['user_id']);

        $from = $_SESSION['user_name'] . "<br>";
        $from .= $_SESSION['user_email'];
        $from .= (!empty($userData['phone'])) ? "<br>" . $userData['phone'] : '';

        $_notification = new notification();
        $_notification->from = $from;
        $_notification->fromName = $_SESSION['user_name'];
        $_notification->fromEmail = $_SESSION['user_email'];
        $_notification->agencyName = $_agency->get_agency_name($agencyId);
        $_notification->sendToName = $first_name . ' ' . $last_name;
        $_notification->sendToEmail = $email;
        $_notification->tempPassword = 'You already have your Login Password';
        $_notification->sentByName = $_SESSION['user_name'];
        $_notification->sentByAgency = $_agency->get_agency_name($_SESSION['agency_id']);
        $_notification->sendEmail('add_agency_user');
        
        $this->_db->redir('directory/agency_summary?id='.$aidEnc.'&m=Invite Sent to the selected Contact');
    }

    public function AddAgencyContact()
    {

        $agencyEnc = $this->_db->gpGet('aid');
        $agencyId = $this->_db->decode($agencyEnc);

        $orgIdEnc = $this->_db->gpGet('oid');

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $client_temp_password = $this->_db->randomPassword();
        $salt = $this->_db->generateSalt();
        $password = crypt($client_temp_password, $salt);

        $contactType = implode(";", $_POST['contact_type']);

        $_agency = new agency();
        $level_1 = $_agency->GetAgencyLevel1($agencyId);
        $licenseType = (!empty($_POST['contact_license_type'])) ? implode(";", $_POST['contact_license_type']) : '';
        
        $community_portal_user_type = $_POST['community_portal_user_type'];
        $cp_user_level = 0;
        $user_level = NULL;
        if($community_portal_user_type == 'ADMIN'){
            # (CP) Org ADMIN = (CMS) ADMIN
            $user_level = 'ADMIN';
        }
        if($community_portal_user_type == 'USER'){
            # (CP) Org CONTACT = (CMS) PROJECT MANAGER
            $user_level = 'PROJECT MANAGER';
        }

        $user_status = 'ACTIVE'; // by default 'ACTIVE'

        $data = [
            "org_id" => ($orgIdEnc ? $this->_db->decode($orgIdEnc) : NULL),
            "default_org_id" => ($orgIdEnc ? $this->_db->decode($orgIdEnc) : NULL),
            "first_name" => $_POST['first_name'],
            "last_name" => $_POST['last_name'],
            "email" => $email,
            "password" => $password,
            "salt" => $salt,
            "title" => $_POST['title'],
            "phone" => $_POST['phone'],
            "extension" => $_POST['extension'],
            "alt_phone" => $_POST['alt_phone'],
            "status" => $user_status,
            "created_date" => $this->currentDateTime,
            "created_by" => $_SESSION['user_id'],
            "ip_addr" => $_SERVER['REMOTE_ADDR'],
            "community_portal" => 0,
            "case_management" => 0,
            "agency_id" => $agencyId,
            "community_portal_user_type" => $community_portal_user_type,
            "contact_type" => $contactType,
            "level_1" => $level_1,
            "cp_user_level" => $cp_user_level,
            "user_level" => $user_level,
            'contact_license_type' => $licenseType
        ];

//        $this->debugData($data);

        $link = "directory/agency_summary?id={$agencyEnc}";

        if ($this->_db->gpGet('add_services') === 'yes') {
            $link = "directory/add_agencyservices?id={$agencyEnc}";
        }

        if ($this->_db->gpGet('add_another_contact') === 'yes') {
            $err = urlencode($_POST['first_name'] . ' ' . $_POST['first_name'] . " has been added");
            $link = "directory/add_agencycontacts?id=" . $agencyEnc . "&err=" . $err;
        }

        if (!empty($agencyId)) {
            $r = $this->_db->insertUpdateSQL($data, 'org_users');

            if ($r) {

                if($orgIdEnc){
                    // update org_user ID in org_information table
                    $org_id = $this->_db->decode($orgIdEnc);
                    $org_admin_user_id = $this->_db->last_inserted_id;

                    if($user_level == 'ADMIN'){

                        // check if admin alredy exist
                        $qry = "select org_admin_id  from org_information where id = '$org_id'";
                        $sth = $this->_dbh->prepare($qry);
                        $sth->execute();
                        $fd = $sth->fetch(PDO::FETCH_OBJ);
                        $org_admin_id = $fd->org_admin_id;
                        if(!$org_admin_id){
                            // if no org_admin exist for this org
                            $this->_db->insertUpdateSQL(['id' => $org_id, 'org_admin_id' => $org_admin_user_id], 'org_information');
                        }
                    }
                    
                    // save data in org_contacts table
                    $org_contacts_data = ['org_id'=> $org_id, 'user_id'=> $org_admin_user_id, 'status'=> $user_status, 'user_level'=> $user_level];
                    $this->_db->insertUpdateSQL($org_contacts_data, 'org_contacts');

                    // save data in cp_directory_contact
                    $org_cp_directory_contact = ['agency_id'=> $agencyId, 'user_id' => $org_admin_user_id];
                    $this->_db->insertUpdateSQL($org_cp_directory_contact, 'cp_directory_contact');
                
                
                }

                //NOTIFICATION
                $_agency = new agency();

                $_user = new users();
                $userData = $_user->getUserData($_SESSION['user_id']);

                $from = $_SESSION['user_name'] . "<br>";
                $from .= $_SESSION['user_email'];
                $from .= (!empty($userData['phone'])) ? "<br>" . $userData['phone'] : '';

                $_notification = new notification();
                $_notification->from = $from;
                $_notification->fromName = $_SESSION['user_name'];
                $_notification->fromEmail = $_SESSION['user_email'];
                $_notification->agencyName = $_agency->get_agency_name($agencyId);
                $_notification->sendToName = $_POST['first_name'] . ' ' . $_POST['last_name'];
                $_notification->sendToEmail = $email;
                $_notification->tempPassword = $client_temp_password;
                $_notification->sentByName = $_SESSION['user_name'];
                $_notification->sentByAgency = $_agency->get_agency_name($_SESSION['agency_id']);
//                $_notification->from = $_agency->get_agency_name($_SESSION['agency_id']);
                $_notification->sendEmail('add_agency_user');

                $this->_db->redir($link);
            } else {
                if (DEBUG) {
                    echo $r;
                    exit;
                }
            }

        } else {
            die('OOPS: Error 0001. Contact System Administrator');
        }

        $this->_db->redir($link);

    }


    public function EditAgencyContact()
    {
        global $_core , $_db;

        $contact_id = $_core->decode($_core->gpGet('uid'));
        $agency_id_enc = $_core->gpGet('aid');
        $agency_id = $_core->decode($agency_id_enc);

        $contactType = implode(";", $_POST['contact_type']);
        $level_1 = implode(";", $_POST['level_1']);
        $licenseType = implode(";", $_POST['contact_license_type']);


        $community_portal_user_type = $_POST['community_portal_user_type'];
        $user_level = NULL;
        if($community_portal_user_type == 'ADMIN'){
            # (CP) Org ADMIN = (CMS) ADMIN
            $user_level = 'ADMIN';
        }
        if($community_portal_user_type == 'USER'){
            # (CP) Org CONTACT = (CMS) PROJECT MANAGER
            $user_level = 'PROJECT MANAGER';
        }

        $user_status = $_POST['status'];

        $data = [
            'id' => $contact_id,
            'first_name' => $_POST['first_name'],
            'last_name' => $_POST['last_name'],
            'email' => $_POST['email'],
            'title' => $_POST['title'],
            'phone' => $_POST['phone'],
            "extension" => $_POST['extension'],
            'alt_phone' => $_POST['alt_phone'],
            'status' => $user_status,
            'updated_date' => $this->currentDateTime,
            'updated_by' => $_SESSION['user_id'],
            'community_portal_user_type' => $community_portal_user_type,
            'contact_type' => $contactType,
            'level_1' => $level_1,
            'contact_license_type' => $licenseType
        ];

        $this->_db->insertUpdateSQL($data, 'org_users');

        // get org_id
        $qry = "select parent_org_id from cp_directory_agency where agency_id = '$agency_id'";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute();
        $f = $sth->fetch(PDO::FETCH_OBJ);
        $parent_org_id = $f->parent_org_id;

        if($user_level == 'ADMIN'){
            $this->_db->insertUpdateSQL(['id' => $parent_org_id, 'org_admin_id' => $contact_id], 'org_information');
        }
        
        // update data in org_contacts table
       
        $qry = "update org_contacts set status = '$user_status', user_level = '$user_level' where org_id = '$parent_org_id' AND user_id = '$contact_id' ";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute();

        $link = "directory/agency_summary?id={$agency_id_enc}";
        $_core->redir($link);
    }

    public function AddAgencyLocation()
    {
        global $_core;

        $agencyIdEnc = $_core->gpGet('aid');
        $agencyId = $_core->decode($agencyIdEnc);
        $level_1 = implode(";", $_POST['level_1']);


        $data = [
            'agency_id' => $agencyId,
            'location_name' => $_POST['location_name'],
            'address' => $_POST['address'],
            'city' => $_POST['city'],
            'state' => $_POST['state'],
            'zip_code' => $_POST['zip_code'],
            'phone' => $_POST['phone'],
            'fax' => $_POST['fax'],
            'level_1' => $level_1,
            'status' => 'ACTIVE',
            'update_by' => $_SESSION['user_id'],
            'update_date' => $this->currentDateTime
        ];

//        $this->debugData($data);

        $this->_db->insertUpdateSQL($data, 'cp_directory_agency_locations');

        $link = "directory/agency_summary?id={$agencyIdEnc}";
        $_core->redir($link);
    }


    public function EditAgencyLocation()
    {
        global $_core;

        $agencyIdEnc = $_core->gpGet('aid');
        $locationIdEnc = $_core->gpGet('lid');
        $locationId = $_core->decode($locationIdEnc);

        $level_1 = implode(";", $_POST['level_1']);

        $data = [
            'id' => $locationId,
            'location_name' => $_POST['location_name'],
            'address' => $_POST['address'],
            'city' => $_POST['city'],
            'state' => $_POST['state'],
            'zip_code' => $_POST['zip_code'],
            'phone' => $_POST['phone'],
            'fax' => $_POST['fax'],
            'level_1' => $level_1,
            'status' => $_POST['status'],
            'update_by' => $_SESSION['user_id'],
            'update_date' => $this->currentDateTime
        ];

//        $this->debugData($data);

        $this->_db->insertUpdateSQL($data, 'cp_directory_agency_locations');

        $link = "directory/agency_summary?id={$agencyIdEnc}";
        $_core->redir($link);
    }


    private function debugData($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit;
    }


    public function AddLevel1User()
    {
        $agencyEnc = $this->_db->gpGet('aid');
        $agencyId = $this->_db->decode($agencyEnc);

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $client_temp_password = $this->_db->randomPassword();
        $salt = $this->_db->generateSalt();
        $password = crypt($client_temp_password, $salt);

        $level_1 = implode(";", $_POST['level_1']);

        $data = [
            "first_name" => $_POST['first_name'],
            "last_name" => $_POST['last_name'],
            "email" => $email,
            "password" => $password,
            "salt" => $salt,
            "phone" => $_POST['phone'],
            "alt_phone" => $_POST['alt_phone'],
            "status" => 'ACTIVE',
            "created_date" => $this->currentDateTime,
            "created_by" => $_SESSION['user_id'],
            "ip_addr" => $_SERVER['REMOTE_ADDR'],
            "community_portal" => 1,
            "case_management" => 0,
            "agency_id" => $agencyId,
            "community_portal_user_type" => 'ADMIN',
            "level_1" => $level_1,
            "cp_user_level" => '1',
        ];

//        $this->debugData($data);


        if (!empty($agencyId)) {
            $r = $this->_db->insertUpdateSQL($data, 'org_users');

            if ($r) {

                $user_id = $this->_db->last_inserted_id;
                // save data in cp_directory_contact
                $org_cp_directory_contact = ['agency_id'=> $agencyId, 'user_id' => $user_id];
                $this->_db->insertUpdateSQL($org_cp_directory_contact, 'cp_directory_contact');


                //NOTIFICATION
                $_agency = new agency();

                $_user = new users();
                $userData = $_user->getUserData($_SESSION['user_id']);

                $from = $_SESSION['user_name'] . "<br>";
                $from .= $_SESSION['user_email'];
                $from .= (!empty($userData['phone'])) ? "<br>" . $userData['phone'] : '';

                $_notification = new notification();
                $_notification->from = $from;
                $_notification->fromName = $_SESSION['user_name'];
                $_notification->fromEmail = $_SESSION['user_email'];
                $_notification->agencyName = $_agency->get_agency_name($agencyId);
                $_notification->sendToName = $_POST['first_name'] . ' ' . $_POST['last_name'];
                $_notification->sendToEmail = $email;
                $_notification->tempPassword = $client_temp_password;
                $_notification->sentByName = $_SESSION['user_name'];
                $_notification->sentByAgency = $_agency->get_agency_name($_SESSION['agency_id']);
//                $_notification->from = $_agency->get_agency_name($agencyId);
                $_notification->sendEmail('add_agency_user');

                $link = "directory/edit_level12_users";
                $this->_db->redir($link);
            } else {
                if (DEBUG) {
                    echo $r;
                    exit;
                }
            }

        } else {
            die('OOPS: Error 0001. Contact System Administrator');
        }

    }

    public function EditLevel1User()
    {
        $level_1 = implode(";", $_POST['level_1']);

        $userIdEnc = $this->_db->gpGet('uid');
        $userId = $this->_db->decode($userIdEnc);

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        $data = [
            "id" => $userId,
            "first_name" => $_POST['first_name'],
            "last_name" => $_POST['last_name'],
            "email" => $email,
            "phone" => $_POST['phone'],
            "alt_phone" => $_POST['alt_phone'],
            "status" => $_POST['status'],
            "updated_by" => $_SESSION['user_id'],
            "updated_date" => $this->currentDateTime,
            "ip_addr" => $_SERVER['REMOTE_ADDR'],
            "level_1" => $level_1
        ];

        $this->_db->insertUpdateSQL($data, 'org_users');

        $link = "directory/edit_level1_user?uid={$userIdEnc}&e=complete";
        $this->_db->redir($link);
    }

    public function AddLevel2User()
    {
        $agencyEnc = $this->_db->gpGet('aid');
        $agencyId = $this->_db->decode($agencyEnc);

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $client_temp_password = $this->_db->randomPassword();
        $salt = $this->_db->generateSalt();
        $password = crypt($client_temp_password, $salt);

        $level_1 = implode(";", $_POST['level_1']);

        $data = [
            "first_name" => $_POST['first_name'],
            "last_name" => $_POST['last_name'],
            "email" => $email,
            "password" => $password,
            "salt" => $salt,
            "phone" => $_POST['phone'],
            "alt_phone" => $_POST['alt_phone'],
            "status" => 'ACTIVE',
            "created_date" => $this->currentDateTime,
            "created_by" => $_SESSION['user_id'],
            "ip_addr" => $_SERVER['REMOTE_ADDR'],
            "community_portal" => 1,
            "case_management" => 0,
            "agency_id" => $agencyId,
            "community_portal_user_type" => 'ADMIN',
            "level_1" => $level_1,
            "cp_user_level" => '2',
        ];

//        $this->debugData($data);


        if (!empty($agencyId)) {
            $r = $this->_db->insertUpdateSQL($data, 'org_users');

            if ($r) {

                $user_id = $this->_db->last_inserted_id;
                // save data in cp_directory_contact
                $org_cp_directory_contact = ['agency_id'=> $agencyId, 'user_id' => $user_id];
                $this->_db->insertUpdateSQL($org_cp_directory_contact, 'cp_directory_contact');
                
                //NOTIFICATION
                $_agency = new agency();

                $_user = new users();
                $userData = $_user->getUserData($_SESSION['user_id']);

                $from = $_SESSION['user_name'] . "<br>";
                $from .= $_SESSION['user_email'];
                $from .= (!empty($userData['phone'])) ? "<br>" . $userData['phone'] : '';

                $_notification = new notification();
                $_notification->fromName = $_SESSION['user_name'];
                $_notification->fromEmail = $_SESSION['user_email'];
                $_notification->from = $from;
                $_notification->agencyName = $_agency->get_agency_name($agencyId);
                $_notification->sendToName = $_POST['first_name'] . ' ' . $_POST['last_name'];
                $_notification->sendToEmail = $email;
                $_notification->tempPassword = $client_temp_password;
                $_notification->sentByName = $_SESSION['user_name'];
                $_notification->sentByAgency = $_agency->get_agency_name($_SESSION['agency_id']);
//                $_notification->from = $_agency->get_agency_name($_SESSION['agency_id']);
                $_notification->sendEmail('add_agency_user');

                $link = "directory/level2_users";
                $this->_db->redir($link);
            } else {
                if (DEBUG) {
                    echo $r;
                    exit;
                }
            }

        } else {
            die('OOPS: Error 0001. Contact System Administrator');
        }

    }

    public function EditLevel2User()
    {
        $level_1 = implode(";", $_POST['level_1']);

        $userIdEnc = $this->_db->gpGet('uid');
        $userId = $this->_db->decode($userIdEnc);

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        $data = [
            "id" => $userId,
            "first_name" => $_POST['first_name'],
            "last_name" => $_POST['last_name'],
            "email" => $email,
            "phone" => $_POST['phone'],
            "alt_phone" => $_POST['alt_phone'],
            "status" => $_POST['status'],
            "updated_by" => $_SESSION['user_id'],
            "updated_date" => $this->currentDateTime,
            "ip_addr" => $_SERVER['REMOTE_ADDR'],
            "level_1" => $level_1
        ];

        $this->_db->insertUpdateSQL($data, 'org_users');

        $link = "directory/edit_level1_user?uid={$userIdEnc}&e=complete";
        $this->_db->redir($link);
    }

    public function AddAgencyServices()
    {

        $agencyIdEnc = $this->_db->gpGet('aid');
        $agencyId = $this->_db->decode($agencyIdEnc);

        if (!empty($agencyId)) {

//            $this->debugData($_POST);

            //LETS PURGE OLD SERVICES RECORDS
            $delQry = "delete from cp_agency_services where agency_id = :agencyId and parent_agency_id = :parent";
            $sth = $this->_dbh->prepare($delQry);
            $sth->execute([':agencyId' => $agencyId, ':parent' => $_SESSION['parent_agency']]);


            //ADD SERVICES
            if (!empty($_POST['services']))
            {
                $qry = "insert into cp_agency_services (agency_id, parent_agency_id, sub_item_2_id) values ";

                foreach ($_POST['services'] as $serviceId) {

                    $serviceIdDecoded = $this->_db->decode($serviceId);
                    $qry .= "('{$agencyId}', '{$_SESSION['parent_agency']}', '{$serviceIdDecoded}'),";
                }

                $qry = rtrim($qry, ',') . ';';

                $sth = $this->_dbh->prepare($qry);
                $sth->execute();
            }


            //ADD NOTES
            foreach ($_POST['note'] as $subItem2Id => $note) {

                if (!empty($note)) {
                    $subItem2IdDecoded = $this->_db->decode($subItem2Id);
                    $qryNote = "update cp_agency_services set note = :note where agency_id = :agencyId and sub_item_2_id = :subItem2Id";

                    $sth1 = $this->_dbh->prepare($qryNote);
                    $sth1->execute([':note' => $note, ':agencyId' => $agencyId, ':subItem2Id' => $subItem2IdDecoded]);
                }

            }

            //ADD CUSTOM ITEM NAME
            if (!empty($_POST['custom_item_name'])) {
                //LETS PURGE OLD CUSTOM ITEM NAMES
                $delQry = "delete from cp_custom_item_name where agency_id = :agencyId and parent_agency_id = :parent";
                $sth = $this->_dbh->prepare($delQry);
                $sth->execute([':agencyId' => $agencyId, ':parent' => $_SESSION['parent_agency']]);

                $qry = 'insert into cp_custom_item_name (agency_id, parent_agency_id, item_id, custom_item_name, updated_by, updated_date) values ';
                foreach ($_POST['custom_item_name'] as $customItemId => $customName) {
                    $itemIdDecoded = $this->_db->decode($customItemId);

                    $qry .= (!empty($customName)) ?
                        "('{$agencyId}', '{$_SESSION['parent_agency']}', '{$itemIdDecoded}', '{$customName}', '{$_SESSION['user_id']}', '{$this->currentDateTime}'),"
                        : '';
                }
                $qry = rtrim($qry, ',') . ';';

                $sth = $this->_dbh->prepare($qry);
                $sth->execute();
            }

        }

        $this->_db->redir('directory/add_org_services?id=' . $agencyIdEnc);
    }

    public function AddAgencyServices2()
    {
        $agencyIdEnc = $this->_db->gpGet('aid');
        $agencyId = $this->_db->decode($agencyIdEnc);

        $serviceId = $this->_db->decode($this->_db->gpGet('sid'));

        if (!empty($agencyId)) {

//            $this->debugData($_POST);

            //LETS PURGE OLD SERVICES RECORDS
            $delQry = "delete from cp_agency_services where agency_id = :agencyId and parent_agency_id = :parent and service_id = :serviceId";
            $sth = $this->_dbh->prepare($delQry);
            $sth->execute([':agencyId' => $agencyId, ':parent' => $_SESSION['parent_agency'], ':serviceId' => $serviceId]);


            //ADD SERVICES
            if (!empty($_POST['services']))
            {
                $qry = "insert into cp_agency_services (agency_id, parent_agency_id, service_id, sub_item_2_id) values ";

                foreach ($_POST['services'] as $subItem2) {

                    $subItemDecoded = $this->_db->decode($subItem2);
                    $qry .= "('{$agencyId}', '{$_SESSION['parent_agency']}', '{$serviceId}', '{$subItemDecoded}'),";
                }

                $qry = rtrim($qry, ',') . ';';

                $sth = $this->_dbh->prepare($qry);
                $sth->execute();
            }


            //ADD NOTES
            foreach ($_POST['note'] as $subItem2Id => $note) {

                if (!empty($note)) {
                    $subItem2IdDecoded = $this->_db->decode($subItem2Id);
                    $qryNote = "update cp_agency_services set note = :note where agency_id = :agencyId and service_id = :serviceId and sub_item_2_id = :subItem2Id";

                    $sth1 = $this->_dbh->prepare($qryNote);
                    $sth1->execute([':note' => $note, ':agencyId' => $agencyId, ':serviceId' => $serviceId, ':subItem2Id' => $subItem2IdDecoded]);
                }

            }

            //ADD CUSTOM ITEM NAME
            if (!empty($_POST['custom_item_name'])) {
                //LETS PURGE OLD CUSTOM ITEM NAMES
                $delQry = "delete from cp_custom_item_name where agency_id = :agencyId and parent_agency_id = :parent and service_id = :serviceId";
                $sth = $this->_dbh->prepare($delQry);
                $sth->execute([':agencyId' => $agencyId, ':parent' => $_SESSION['parent_agency'], ':serviceId' => $serviceId]);

                $qry = 'insert into cp_custom_item_name (agency_id, parent_agency_id, service_id, item_id, custom_item_name, updated_by, updated_date) values ';
                foreach ($_POST['custom_item_name'] as $customItemId => $customName) {
                    $itemIdDecoded = $this->_db->decode($customItemId);

                    $qry .= (!empty($customName)) ?
                        "('{$agencyId}', '{$_SESSION['parent_agency']}', '{$serviceId}', '{$itemIdDecoded}', '".addslashes($customName)."', '{$_SESSION['user_id']}', '{$this->currentDateTime}'),"
                        : '';
                }
                $qry = rtrim($qry, ',') . ';';

                $sth = $this->_dbh->prepare($qry);
                $sth->execute();
            }

        }

        $filter = (!empty($this->_db->gpGet('filter'))) ? '&filter='.$this->_db->gpGet('filter') : '';

        $this->_db->redir('directory/add_org_services?id=' . $agencyIdEnc . $filter);
    }

    public function AddAgencyLocationServices()
    {
        $agencyIdEnc = $this->_db->gpGet('aid');
        $agencyId = $this->_db->decode($agencyIdEnc);

        $locationIdEnc = $this->_db->gpGet('lid');
        $locationId = $this->_db->decode($locationIdEnc);

        if (!empty($locationId)) {

//            $this->debugData($_POST);

            //LETS PURGE OLD RECORDS
            $delQry = "delete from cp_agency_location_services where agency_id = :agencyId and location_id = :locId";
            $sth = $this->_dbh->prepare($delQry);
            $sth->execute([':agencyId' => $agencyId, ':locId' => $locationId]);


            //ADD SERVICES
            $qry = "insert into cp_agency_location_services (agency_id, location_id, sub_item_2_id) values ";

            foreach ($_POST['services'] as $serviceId) {
                $serviceIdDecoded = $this->_db->decode($serviceId);
                $qry .= "('{$agencyId}', '{$locationId}', '{$serviceIdDecoded}'),";
            }

            $qry = rtrim($qry, ',') . ';';

            $sth = $this->_dbh->prepare($qry);
            $sth->execute();

            //ADD NOTES
            foreach ($_POST['note'] as $subItemId => $note) {

                if (!empty($note)) {
                    $subItem2IdDecoded = $this->_db->decode($subItemId);
                    $qryNote = "update cp_agency_location_services set note = :note where location_id = :locId and sub_item_2_id = :subItemId";

                    $sth1 = $this->_dbh->prepare($qryNote);
                    $sth1->execute([':note' => $note, ':locId' => $locationId, ':subItemId' => $subItem2IdDecoded]);
                }

            }

            $this->_db->redir('directory/editagencylocation?id=' . $agencyIdEnc . '&lid=' . $locationIdEnc);
        }
    }

    public function AddAgencyLocationServices2()
    {
        $agencyIdEnc = $this->_db->gpGet('aid');
        $agencyId = $this->_db->decode($agencyIdEnc);

        $locationIdEnc = $this->_db->gpGet('lid');
        $locationId = $this->_db->decode($locationIdEnc);

        $serviceId = $this->_db->decode($this->_db->gpGet('sid'));

        if (!empty($locationId)) {

//            $this->debugData($_POST);

            //LETS PURGE OLD RECORDS
            $delQry = "delete from cp_agency_location_services where agency_id = :agencyId and location_id = :locId and service_id = :serviceId";
            $sth = $this->_dbh->prepare($delQry);
            $sth->execute([':agencyId' => $agencyId, ':locId' => $locationId, ':serviceId' => $serviceId]);


            //ADD SERVICES
            if (!empty($_POST['services']))
            {
                $qry = "insert into cp_agency_location_services (agency_id, location_id, service_id, sub_item_2_id) values ";

                foreach ($_POST['services'] as $subItem) {
                    $subItemIdDecoded = $this->_db->decode($subItem);
                    $qry .= "('{$agencyId}', '{$locationId}', '{$serviceId}', '{$subItemIdDecoded}'),";
                }

                $qry = rtrim($qry, ',') . ';';

                $sth = $this->_dbh->prepare($qry);
                $sth->execute();
            }


            //ADD NOTES
            foreach ($_POST['note'] as $subItemId => $note) {

                if (!empty($note)) {
                    $subItem2IdDecoded = $this->_db->decode($subItemId);
                    $qryNote = "update cp_agency_location_services set note = :note where location_id = :locId and service_id = :serviceId and sub_item_2_id = :subItemId";

                    $sth1 = $this->_dbh->prepare($qryNote);
                    $sth1->execute([':note' => $note, ':locId' => $locationId, ':serviceId' => $serviceId, ':subItemId' => $subItem2IdDecoded]);
                }

            }

            $filter = (!empty($this->_db->gpGet('filter'))) ? '&filter='.$this->_db->gpGet('filter') : '';

            $this->_db->redir('directory/add_org_services_locations?id=' . $agencyIdEnc . '&lid=' . $locationIdEnc . $filter);
        }
    }

}

//end action

