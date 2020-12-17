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

class Action {

    private $currentDateTime;
    private $_db;
    private $_dbh;

    public function __construct() {
        $this->currentDateTime = date("Y-m-d H:i:s");
        $this->_db = new db();
        $this->_dbh = $this->_db->initDB();
    }

    public function AddContactType()
    {
        $parentAgencyId = $this->_db->SetParentAgencyId();
        $data = [
            'agency_id' => $_SESSION['agency_id'],
            'parent_agency_id' => $parentAgencyId,
            'contact_type' => $_POST['contact_type'],
            'status' => 'ACTIVE',
            'update_by' => $_SESSION['user_id'],
            'update_date' => $this->currentDateTime
        ];

        $this->_db->insertUpdateSQL($data,'cp_contact_types');

        $this->_db->redir('directory/edit_contact_types');
    }

    public function EditContactType()
    {
        $qry = "update cp_contact_types set contact_type = :contactType, `status` = :status where id = :id";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':contactType' => $_POST['contact_type'], ':status' => $_POST['status'], ':id' => $this->_db->decode($_POST['ctid'])]);

        $this->_db->redir('directory/edit_contact_types&e=Saved');
    }

    private function debugData($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit;
    }

}

//end action

