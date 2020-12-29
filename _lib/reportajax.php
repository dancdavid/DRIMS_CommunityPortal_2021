<?php

/*
 * Written by Dan David
 * file to handle most AJAX calls for site
 */
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

    private $_core;
    private $_db;

    public function __construct()
    {
        $this->_core = new core();
        $this->_db = new db();
    }

    public function OrgUserReport()
    {
        $qry = "select * from cp_org_user_report 
                where parent_agency = :parent 
                and agency_id <> :parent";

        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':parent' => $_SESSION['parent_agency']]);

        while ($f = $sth->fetch(PDO::FETCH_OBJ))
        {
//            $level1 = str_replace(';',',',$f->user_level_1);
            $_level = new Level1();
            $level1 = str_replace(",","<br>",$_level->GetLevel1Name($f->user_level_1));

            $_contactType = new ContactType();
            $contactType = str_replace(",","<br>",$_contactType->GetContactTypeName($f->contact_type));

            $name = $f->first_name . ' ' . $f->last_name;

            $data['aaData'][] = array(
                $level1,
                "<a href='agency_summary?id={$this->_db->encode($f->agency_id)}'>{$f->agency_name}</a>",
                $name,
                "<a href='mailto:{$f->email}' target='_blank'>{$f->email}</a>",
                $contactType,
                $f->user_status,
                $f->cp_notification,
                $f->login_date
            );
        }

        echo json_encode($data);
    }

    public function OrgTeamReport()
    {
        $qry = "select * from cp_teams where parent_agency_id = :parent and status = 'ACTIVE' order by team_name";
        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':parent' => $_SESSION['parent_agency']]);

        while($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $_team = new Teams($f->id);

//            $level1 = str_replace(';',', ',$f->level_1);

            $_level = new Level1();
            $level1 = str_replace(",","<br>",$_level->GetLevel1Name($f->level_1));

            $data['aaData'][] = array(
                "<a href='edit_team?tid={$this->_db->encode($f->id)}'>{$f->team_name}</a>",
                $level1,
                $_team->GetOrgTeamReportCount('cp_team_members'),
                $_team->GetOrgTeamReportCount('cp_team_message_board'),
                $_team->GetOrgTeamReportLastUpdate('cp_team_message_board'),
                $_team->GetOrgTeamReportCount('cp_team_events_calendar'),
                $_team->GetOrgTeamReportLastUpdate('cp_team_events_calendar'),
                $_team->GetOrgTeamReportCount('cp_team_file_upload'),
                $_team->GetOrgTeamReportLastUpdate('cp_team_file_upload'),
                $_team->GetOrgTeamReportCount('cp_team_dashboard_links'),
                $_team->GetOrgTeamReportLastUpdate('cp_team_dashboard_links')
            );
        }

        echo json_encode($data);
    }

    public function OrgMainReport()
    {   
        $fields = $this->_db->getAgencyFields();
        $qry = "select $fields from org_information where cp_parent_agency = :parent and `status` = 'ACTIVE'";
        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':parent' => $_SESSION['parent_agency']]);

        while ($f = $sth->fetch(PDO::FETCH_OBJ))
        {
//            $level1 = str_replace(';',', ',$f->level_1);

            $_level = new Level1();
            $level1 = str_replace(",","<br>",$_level->GetLevel1Name($f->level_1));

            $_partnerType = new PartnerType();
            $partnerType = str_replace(",","<br>",$_partnerType->GetPartnerTypeName($f->partner_type));

            $data['aaData'][] = array(
                "<a href='agency_summary?id={$this->_db->encode($f->agency_id)}'>{$f->agency_name}</a>",
                $level1,
                $partnerType,
                $this->GetMainReportContact($f->agency_id),
                $this->GetMainReportCategory($f->agency_id, 'SERVICE'),
                $this->GetMainReportItem($f->agency_id, 'SERVICE'),
                $this->GetMainReportSubItem($f->agency_id, 'SERVICE'),
                $this->GetMainReportCategory($f->agency_id, 'RESOURCES'),
                $this->GetMainReportItem($f->agency_id, 'RESOURCES'),
                $this->GetMainReportSubItem($f->agency_id, 'RESOURCES'),
                $this->GetMainReportCategory($f->agency_id, 'TRAINING'),
                $this->GetMainReportItem($f->agency_id, 'TRAINING'),
                $this->GetMainReportSubItem($f->agency_id, 'TRAINING')

            );
        }

        echo json_encode($data);
    }

    private function GetMainReportContact($agencyId)
    {
        $qry = "select * from org_users where agency_id = :agencyId and `status` = 'ACTIVE'";
        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId]);
        return $sth->rowCount();
    }

    private function GetMainReportCategory($agencyId, $category)
    {
        $qry = "select distinct service_id
                from cp_agency_services_report 
                where agency_id = :agencyId 
                and category = :category
                and `status` = 'ACTIVE'";

        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId, ':category' => $category]);
        return $sth->rowCount();
    }

    private function GetMainReportItem($agencyId, $category)
    {
        $qry = "select distinct item_id
                from cp_agency_services_report 
                where agency_id = :agencyId 
                and category = :category
                and item_status = 'ACTIVE'";

        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId, ':category' => $category]);
        return $sth->rowCount();
    }

    private function GetMainReportSubItem($agencyId, $category)
    {
        $qry = "select sub_item_id
                from cp_agency_services_report 
                where agency_id = :agencyId 
                and category = :category
                and sub_item_status = 'ACTIVE'";

        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId, ':category' => $category]);
        return $sth->rowCount();
    }

}

?>