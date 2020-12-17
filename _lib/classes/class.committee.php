<?php

class committee {

    protected $committee_id;
    private $db;
    private $dbh;

    public function __construct($committee_id) {
        $this->db = new db();
        $this->dbh = $this->db->initDB();
        $this->committee_id = $committee_id;
    }

    public function get_committee_data() {
        $qry = "select * from cp_committee_list where committee_id = :committee_id";
        $sth = $this->dbh->prepare($qry);
        $sth->execute(array(":committee_id" => $this->committee_id));
        return $sth->fetch(PDO::FETCH_ASSOC);
    }
    
    public function get_committee_contacts() {
        $qry = "select * from full_committee_contacts where committee_id = :committee_id";
        $sth = $this->dbh->prepare($qry);
        $sth->execute(array(":committee_id" => $this->committee_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

}
