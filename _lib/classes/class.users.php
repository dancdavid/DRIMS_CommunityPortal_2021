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

//        $qry = "select * from org_users where id = :user_id";
        $qry = "select 
                 id,
                 first_name,
                 last_name,
                 email,
                 phone,
                 alt_phone,
                 level_1,
                 status,
                 cp_notification
                 from org_users where id = :user_id";

        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute(array( ":user_id" => $user_id ));
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
