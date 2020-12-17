<?php

class volunteer {

    protected $_db;
    protected $dbh;
    private $volunteer_id;

    public function __construct($volunteer_id) {
        $this->volunteer_id = $volunteer_id;
        $this->_db = new db();
        $this->dbh = $this->_db->initDB();
    }

    public function get_volunteer_data() {
        $qry = "select * from cp_volunteers where id = :id";
        $sth = $this->dbh->prepare($qry);
        $sth->execute(array(":id" => $this->volunteer_id));
        return $sth->fetch(PDO::FETCH_ASSOC);
    }
    
    public function chk_category($category) {
        $f = $this->get_volunteer_data();
        
        $categories = explode(";",$f['categories']);
        
        if (in_array($category,$categories)) 
            echo "checked";
        else 
            echo '';
    }

}
