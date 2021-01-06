<?php

class users extends core {

    private $_db;
    private $_dbh;

    function __construct() {
        $this->_db = new db();
        $this->_dbh = $this->_db->initDB();
    }

    public function validateAdmin($type = 'USER') {
        if ($type !== 'ADMIN') {
            $this->redir('index');
        }
    }

    public function getUserData($user_id) {

    //  $qry = "select * from org_users where id = :user_id"
        $result = $this->GetCurrentuser($user_id);
        return $result;
    }

    public function GetMyProfile()
    {
        $user_id = $_SESSION['user_id'];
        $result = $this->GetCurrentuser($user_id);
        //echo "<pre>";print_r($_SESSION);die;
        return $result;
    }

    private function GetCurrentuser($user_id){

        $agency_id = $_SESSION['agency_id'];

        if($agency_id){
          $where = " oc.user_id = :user_id and
          oc.cp_org_id = :agency_id ";
          $params[":agency_id"] = $agency_id;
        }else{
          $where = " org_users.id = :user_id limit 1";
        }

        $qry = "select org_users.id
               ,first_name
               ,last_name
               ,phone
               ,default_agency_id
               ,title
               ,extension
               ,alt_phone
               ,email
               ,oc.cp_contact_type as contact_type 
               ,oc.cp_level_1 as level_1
               ,oc.cp_community_portal_user_type as community_portal_user_type
               ,oc.contact_license_type
               ,oc.status
               ,oc.cp_notification
               from org_users 
               left join org_contacts oc on oc.user_id = org_users.id
               where $where ";
    
      $dbh = $this->_db->initDB();
      $sth = $dbh->prepare($qry);
      $params[":user_id"] = $user_id;
      $sth->execute($params);
      $result = $sth->fetch(PDO::FETCH_ASSOC);
      return $result;
    }

}
