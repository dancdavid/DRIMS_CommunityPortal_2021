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

    public function GetAllLvl1List()
    {
        $qry = "select id, level_1, status from cp_level_1 where parent_agency_id = :id and `status` <> 'DELETE' order by id desc";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':id' => $_SESSION['parent_agency']]);

        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
            $aStatus = ($f->status == 'ACTIVE') ? 'selected' : '';
            $iStatus = ($f->status == 'IN-ACTIVE') ? 'selected' : '';

            $data['aaData'][] = array(
                "<form class='form-horizontal' method='post' action='../_lib/lvlaction.php?action=" . $this->_db->encode("EditLevel1") . "'>"
                . "<div class='col-md-8'>"
                . "<input type='hidden' name='lvlid' value='".$this->_db->encode($f->id)."'>"
                . "<input type='text' name='level_1' class='form-control' value='{$f->level_1}' required>"
                . "</div>"
                . "<div class='col-md-2'>"
                . "<select name='status' class='form-control'>"
                . "<option value='ACTIVE' {$aStatus}>ACTIVE</option>"
                . "<option value='IN-ACTIVE' {$iStatus}>IN-ACTIVE</option>"
                . "</select>"
                . "</div>"
                . "<div class='col-md-1'><button type='submit' class='btn btn-success'>EDIT</button></div>"
                . "</form>",
                $f->level_1
            );
        }

        echo json_encode($data);
    }

}

?>