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

//        $qry = "select * from org_users where id = :user_id"
          $qry = "select org_users.id
                 ,first_name
                 ,last_name
                 ,phone
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
                 join org_contacts oc on oc.user_id = org_users.id
                 where oc.user_id = :user_id and
                  oc.cp_org_id = :agency_id";

        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute(array( ":user_id" => $user_id , ":agency_id" => $_SESSION['agency_id']));
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        
        return $result;
    }

    public function GetMyProfile()
    {
        $qry = "select * from org_users where id = :id";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':id' => $_SESSION['user_id']]);
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

}
