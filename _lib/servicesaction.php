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
    echo "Invalid Action {$action}" . $_core->encode($action);
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

    public function AddService()
    {
        if ($this->CheckIfServiceTtileExist($_POST['category'],$_POST['type']))
        {
            $this->_db->redir('directory/add_services2?e=duplicate');
        } else {
            $data = [
                "parent_agency_id" => $_SESSION['parent_agency'],
                "category" => $_POST['category'],
                "type" => $_POST['type'],
                "description" => htmlspecialchars($_POST['description']),
                "update_by" => $_SESSION['user_id'],
                "update_date" => $this->currentDateTime,
            ];

            $serviceIdEnc = $this->_db->gpGet('sid');

            if (!empty($serviceIdEnc))
            {

                $serviceId = $this->_db->decode($serviceIdEnc);
                $data = array_merge(['id' => $serviceId, 'status' => $_POST['status']], $data);
                $this->_db->insertUpdateSQL($data, 'cp_services');

            } else {

                $data = array_merge(['status' => 'ACTIVE'], $data);
                $this->_db->insertUpdateSQL($data, 'cp_services');
                $serviceId = $this->_db->last_inserted_id;

            }

            $iid =  (!empty($this->_db->gpGet('iid'))) ? '&iid=' . $this->_db->gpGet('iid') : '';

            $this->_db->redir('directory/add_services2?sid=' . $this->_db->encode($serviceId) . $iid . '&e=Saved');
        }
        
    }

    private function CheckIfServiceTtileExist($category,$type)
    {
        $qry = "select title from cp_services where category = :category and type = :type and parent_agency_id = :parent";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':category' => $category, ':type' => $type, ':parent' => $_SESSION['parent_agency']]);

        if ($sth->rowCount() > 0)
            return true;
        else
            return false;
    }

    public function AddItem()
    {
        $data = [
            "parent_agency_id" => $_SESSION['parent_agency'],
            "service_id" => $this->_db->decode($this->_db->gpGet('sid')),
            "item" => $_POST['item'],
            "update_by" => $_SESSION['user_id'],
            "update_date" => $this->currentDateTime
        ];

        $itemIdEnc = $this->_db->gpGet('iid');

        if (!empty($itemIdEnc))
        {

            $itemId = $this->_db->decode($itemIdEnc);
            $data = array_merge(['id' => $itemId, 'status' => $_POST['status']], $data);
            $this->_db->insertUpdateSQL($data, 'cp_services_item');

        } else {

            $data = array_merge(['status' => 'ACTIVE'], $data);
            $this->_db->insertUpdateSQL($data, 'cp_services_item');
            $itemId = $this->_db->last_inserted_id;

        }

        $this->_db->redir('directory/add_services2?sid=' . $this->_db->gpGet('sid') . '&iid=' . $this->_db->encode($itemId));

    }

    public function AddSubItem()
    {

        $serviceId = $this->_db->decode($this->_db->gpGet('sid'));
        $itemId = $this->_db->decode($this->_db->gpGet('iid'));

        $data = [
            "parent_agency_id" => $_SESSION['parent_agency'],
            "service_id" => $serviceId,
            "item_id" => $itemId,
            "sub_item" => $_POST['sub_item'],
            "update_by" => $_SESSION['user_id'],
            "update_date" => $this->currentDateTime
        ];

//        $this->debugData($data);


        $subItemIdEnc = $this->_db->gpGet('siid');

        if (!empty($subItemIdEnc))
        {

            $subItemId = $this->_db->decode($subItemIdEnc);
            $data = array_merge(['id' => $subItemId, 'status' => $_POST['status']], $data);
            $this->_db->insertUpdateSQL($data, 'cp_services_sub_item');

        } else {

            $data = array_merge(['status' => 'ACTIVE'], $data);
            $this->_db->insertUpdateSQL($data, 'cp_services_sub_item');
            $subItemId = $this->_db->last_inserted_id;

        }

        $this->_db->redir('directory/add_services2?sid=' . $this->_db->gpGet('sid') . '&iid=' . $this->_db->encode($itemId) . '&siid=' . $this->_db->encode($subItemId));

    }

    public function AddSubItem2()
    {
        $serviceId = $this->_db->decode($this->_db->gpGet('sid'));
        $subItemId = $this->_db->decode($this->_db->gpGet('siid'));

        $data = [
            "parent_agency_id" => $_SESSION['parent_agency'],
            "service_id" => $serviceId,
            "sub_item_id" => $subItemId,
            "sub_item_2" => $_POST['sub_item_2'],
            "update_by" => $_SESSION['user_id'],
            "update_date" => $this->currentDateTime
        ];

//        $this->debugData($data);

        $subItem2IdEnc = $this->_db->gpGet('siid2');

        if (!empty($subItem2IdEnc))
        {
            $subItemId2 = $this->_db->decode($subItem2IdEnc);
            $data = array_merge(['id' => $subItemId2, 'status' => $_POST['status']], $data);
            $this->_db->insertUpdateSQL($data, 'cp_services_sub_item_2');

        } else {

            $data = array_merge(['status' => 'ACTIVE'], $data);
            $this->_db->insertUpdateSQL($data, 'cp_services_sub_item_2');

        }

        $this->_db->redir('directory/add_services2?sid=' . $this->_db->gpGet('sid') . '&iid=' . $this->_db->gpGet('iid') . '&siid=' . $this->_db->gpGet('siid'));
    }

    public function CopyItems()
    {
        $serviceId = $this->_db->decode($_POST['sid']);
        $copyServiceId = $this->_db->decode($_POST['csid']);


        //COPY ITEMS
        $qry = "select id, item, status from cp_services_item where service_id = :copyServiceId";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':copyServiceId' => $copyServiceId]);

        $i=0;
        while ($fi = $sth->fetch(PDO::FETCH_OBJ))
        {

            $copyItemQry = "insert into cp_services_item (parent_agency_id, service_id, item, `status`, update_by, update_date) 
                            values 
                            ('{$_SESSION['parent_agency']}', '{$serviceId}', '{$fi->item}', '{$fi->status}', '{$_SESSION['user_id']}', '{$this->currentDateTime}')";

//            echo '<br><br>COPYING FROM SERVICE ID: ' . $copyServiceId . '<br>';
//            echo 'COPYING TO SERVICE ID: ' . $serviceId . '<br>';
//            echo 'COPYING ITEM ID: ' . $fi->id . '<br>';
//
//            echo $copyItemQry . '<br>';


            $dbh1 = $this->_db->initDB();
            $sth1 = $dbh1->prepare($copyItemQry);
            $sth1->execute();
            $newItemId = $dbh1->lastInsertId();

            if ($i == 0) $firstCopiedItemId = $newItemId;

            //COPY SUB-ITEMS
            $qry1 = "select id as sub_item_id, sub_item, status from cp_services_sub_item where service_id = :serviceId and item_id = :itemId";
            $sth2 = $this->_dbh->prepare($qry1);
            $sth2->execute([':serviceId' => $copyServiceId, ':itemId' => $fi->id]);

            if ($sth2->rowCount() > 0)
            {
                while ($f = $sth2->fetch(PDO::FETCH_OBJ))
                {
                    $copySubItemQry = "insert into cp_services_sub_item (parent_agency_id, service_id, item_id, sub_item, `status`, update_by, update_date) values ";
                    $copySubItemQry .= "( '{$_SESSION['parent_agency']}', '{$serviceId}', '{$newItemId}', '{$f->sub_item}', '{$f->status}', '{$_SESSION['user_id']}', '{$this->currentDateTime}' );";
                    $this->_dbh->query($copySubItemQry);
                    $subItemId = $this->_dbh->lastInsertId();

                    if ($i == 0) $firstCopiedSubItemId = $subItemId;

                    //COPY SUB-ITEMS 2
                    $qrySub2 = "select sub_item_2, status from cp_services_sub_item_2 where service_id = :serviceId and sub_item_id = :subItemId";
                    $sthSub2 = $this->_dbh->prepare($qrySub2);
                    $sthSub2->execute([':serviceId' => $copyServiceId, ':subItemId' => $f->sub_item_id]);

                    $copySubItem2Qry = "insert into cp_services_sub_item_2 (parent_agency_id, sub_item_id, service_id, sub_item_2, `status`, update_by, update_date) values ";
                    while ($fx = $sthSub2->fetch(PDO::FETCH_OBJ))
                    {
                        $copySubItem2Qry .= "( '{$_SESSION['parent_agency']}', '{$subItemId}', '{$serviceId}', '{$fx->sub_item_2}', '{$fx->status}', '{$_SESSION['user_id']}', '{$this->currentDateTime}' ),";
                    }

                    $copySubItem2Qry = rtrim($copySubItem2Qry, ',');

                    $this->_dbh->query($copySubItem2Qry);
                }
            }

            $i++;
        }

        $this->_db->redir('directory/add_services2?sid=' . $this->_db->gpGet('sid') . '&iid=' . $this->_db->encode($firstCopiedItemId) . '&siid=' . $this->_db->encode($firstCopiedSubItemId) );

    }


    private function debugData($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit;
    }

}

//end action

