<?php
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

    private $_db;
    private $_dbh;

    public function __construct()
    {
        $this->_db = new db();
        $this->_dbh = $this->_db->initDB();
    }

    public function GetAllContactList()
    {
        $qry = "select id, contact_type, status from cp_contact_types where parent_agency_id = :id and `status` <> 'DELETE' order by id desc";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':id' => $_SESSION['parent_agency']]);

        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
            $aStatus = ($f->status == 'ACTIVE') ? 'selected' : '';
            $iStatus = ($f->status == 'IN-ACTIVE') ? 'selected' : '';

            $data['aaData'][] = array(
                "<form class='form-horizontal' method='post' action='../_lib/ctaction.php?action=" . $this->_db->encode("EditContactType") . "'>"
                . "<div class='col-md-8'>"
                . "<input type='hidden' name='ctid' value='".$this->_db->encode($f->id)."'>"
                . "<input type='text' name='contact_type' class='form-control' value='{$f->contact_type}' required>"
                . "</div>"
                . "<div class='col-md-2'>"
                . "<select name='status' class='form-control'>"
                . "<option value='ACTIVE' {$aStatus}>ACTIVE</option>"
                . "<option value='IN-ACTIVE' {$iStatus}>IN-ACTIVE</option>"
                . "</select>"
                . "</div>"
                . "<div class='col-md-1'><button type='submit' class='btn btn-success'>EDIT</button></div>"
                . "</form>",
                $f->contact_type
            );
        }

        echo json_encode($data);
    }

}

?>