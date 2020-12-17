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

    public function AddLevel()
    {
        $parentAgencyId = $this->_db->SetParentAgencyId();
        $data = [
            'agency_id' => $_SESSION['agency_id'],
            'parent_agency_id' => $parentAgencyId,
            'level_1' => $_POST['level_1'],
            'status' => 'ACTIVE',
            'update_by' => $_SESSION['user_id'],
            'update_date' => $this->currentDateTime
        ];

        $this->_db->insertUpdateSQL($data,'cp_level_1');

        $this->_db->redir('directory/edit_lvl1_list');
    }

    public function EditLevel1()
    {
        $qry = "update cp_level_1 set level_1 = :lvl1, `status` = :status where id = :id";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':lvl1' => $_POST['level_1'], ':status' => $_POST['status'], ':id' => $this->_db->decode($_POST['lvlid'])]);

        $this->_db->redir('directory/edit_lvl1_list&e=Saved');
    }

    public function RenameLevel1Label()
    {
        $data = [
            "parent_agency_id" => $_SESSION['parent_agency'],
            "label" => $_POST['label'],
            "update_by" => $_SESSION['user_id'],
            "update_date" => $this->currentDateTime
        ];

        $r = $this->_db->insertUpdateSQL($data, 'cp_level_1_label');

        if ($r)
        {
            unset($_SESSION['Level1_label']);
            $_SESSION['Level1_label'] = $_POST['label'];
        }

        $this->_db->redir('directory/edit_lvl1_list');
    }

    private function debugData($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit;
    }

}

//end action

