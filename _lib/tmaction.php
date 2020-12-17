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

class Action
{

    private $currentDateTime;
    private $_db;
    private $_dbh;

    public function __construct()
    {
        $this->currentDateTime = date("Y-m-d H:i:s");
        $this->_db = new db();
        $this->_dbh = $this->_db->initDB();
    }

    public function AddTeam()
    {
        $level1 = implode(";", $_POST['level_1']);

        $data = [
            "parent_agency_id" => $_SESSION['parent_agency'],
            "team_name" => $_POST['team_name'],
            "description" => $_POST['description'],
            "status" => 'ACTIVE',
            "level_1" => $level1,
            "created_by" => $_SESSION['user_id'],
            "created_date" => $this->currentDateTime
        ];

        $this->_db->insertUpdateSQL($data, 'cp_teams');

        $this->_db->redir('directory/teams_directory_admin');
    }

    public function EditTeam()
    {
        $teamIdEnc = $this->_db->gpGet('tid');
        $teamId = $this->_db->decode($teamIdEnc);

        $level1 = implode(";", $_POST['level_1']);

        $data = [
            "id" => $teamId,
            "team_name" => $_POST['team_name'],
            "description" => $_POST['description'],
            "status" => $_POST['status'],
            "level_1" => $level1,
            "update_by" => $_SESSION['user_id'],
            "update_date" => $this->currentDateTime
        ];

        $this->_db->insertUpdateSQL($data, 'cp_teams');

        $this->_db->redir('directory/edit_team?tid=' . $teamIdEnc);
    }

    public function AddTeamMembers()
    {
        if (!empty($_POST['uid'])) {
            $teamIdEnc = $this->_db->gpGet('tid');
            $teamId = $this->_db->decode($teamIdEnc);

            //PURGE OLD DATA
            $delQry = "delete from cp_team_members where team_id = '{$teamId}'";
            $this->_dbh->query($delQry);

            $qry = "insert into cp_team_members (team_id, user_id, `status`, update_by, update_date) values";

            foreach ($_POST['uid'] as $k => $v) {
                $qry .= " ({$teamId}, {$k}, 'ACTIVE', {$_SESSION['user_id']}, '{$this->currentDateTime}'),";
            }

            $qryAdd = rtrim($qry, ',') . ';';

            $sth = $this->_dbh->prepare($qryAdd);
            $sth->execute();
        }

        if (!empty($_POST['role'])) {
            foreach ($_POST['role'] as $id => $val) {
                $qryRole = "update cp_team_members set role = 'ADMIN' where team_id = '{$teamId}' and user_id = '{$id}'";
                $sth = $this->_dbh->prepare($qryRole);
                $sth->execute();
            }
        }

        $this->_db->redir('directory/edit_team?tid=' . $teamIdEnc);

    }

    public function DelMember()
    {
        $teamMemberIdEnc = $this->_db->gpGet('tmid');
        $teamMemberId = $this->_db->decode($teamMemberIdEnc);

        $teamIdEnc = $this->_db->gpGet('tid');

        if (!empty($teamMemberId)) {
            $qry = "delete from cp_team_members where id = :teamMemberId";
            $sth = $this->_dbh->prepare($qry);
            $sth->execute([':teamMemberId' => $teamMemberId]);
        }

        $this->_db->redir('directory/edit_team?tid=' . $teamIdEnc);
    }

    public function PostTeamMessage()
    {
        $teamIdEnc = $this->_db->gpGet('tid');
        $teamId = $this->_db->decode($teamIdEnc);

        $data = [
            "team_id" => $teamId,
            "title" => $_POST['title'],
            "message" => $_POST['message'],
            "timestamp" => $this->currentDateTime,
            'status' => 'ACTIVE',
            "submitted_by" => $_SESSION['user_name'],
            "update_date" => $this->currentDateTime,
            "update_by" => $_SESSION['user_id']
        ];

        $this->_db->insertUpdateSQL($data, 'cp_team_message_board');
        $messageId = $this->_db->last_inserted_id;

        if (!empty($_FILES['upload_attachment']['tmp_name'][0])) {
            //UPDATED to handle multiple attachments
            $this->UploadFiles($_FILES, $messageId, 'message');
        }

        //NOTIFY
        if (isset($_POST['notify_members']) && $_POST['notify_members'] === 'YES') {
            $_team = new Teams($teamId);

            $emails = $_team->GetMemberEmailsForNotification();
            $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);

            $_user = new users();
            $userData = $_user->getUserData($_SESSION['user_id']);

            $from = $_SESSION['user_name'] . "<br>";
            $from .= $_SESSION['user_email'];
            $from .= (!empty($userData['phone'])) ? "<br>" . $userData['phone'] : '';

            $_notification = new notification();
            foreach ($emails as $k => $val) {
                $_notification->from = $from;
                $_notification->fromEmail = $_SESSION['user_email'];
                $_notification->fromName = $_SESSION['user_name'];
                $_notification->sendToEmail = $val['email'];
                $_notification->sendToName = $val['first_name'];
                $_notification->teamArea = "Message";
                $_notification->docTitle = $title;
                $_notification->teamName = $_team->GetTeamName();
                $_notification->sentByName = $_SESSION['user_name'];
                $_notification->sendEmail('team_notification');
            }
        }

        $this->_db->redir('directory/my_teams?tid=' . $teamIdEnc);
    }

    public function AddTeamLink()
    {
        if (!preg_match('#^https?://#', $_POST['url'])) {
            $url = 'http://' . $_POST['url'];
        } else {
            $url = $_POST['url'];
        }

        $teamIdEnc = $this->_db->gpGet('tid');
        $teamId = $this->_db->decode($teamIdEnc);

        $data = [
            'team_id' => $teamId,
            'title' => $_POST['title'],
            'url' => $url,
            'description' => $_POST['description'],
            'status' => 'ACTIVE',
            'update_by' => $_SESSION['user_id'],
            'update_date' => $this->currentDateTime,
        ];

        $this->_db->insertUpdateSQL($data, 'cp_team_dashboard_links');

        //NOTIFY
        if (isset($_POST['notify_members']) && $_POST['notify_members'] === 'YES') {
            $_team = new Teams($teamId);

            $emails = $_team->GetMemberEmailsForNotification();
            $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);

            $_user = new users();
            $userData = $_user->getUserData($_SESSION['user_id']);

            $from = $_SESSION['user_name'] . "<br>";
            $from .= $_SESSION['user_email'];
            $from .= (!empty($userData['phone'])) ? "<br>" . $userData['phone'] : '';

            $_notification = new notification();
            foreach ($emails as $k => $val) {
                $_notification->from = $from;
                $_notification->fromEmail = $_SESSION['user_email'];
                $_notification->fromName = $_SESSION['user_name'];
                $_notification->sendToEmail = $val['email'];
                $_notification->sendToName = $val['first_name'];
                $_notification->teamArea = "Link";
                $_notification->docTitle = $title;
                $_notification->teamName = $_team->GetTeamName();
                $_notification->sentByName = $_SESSION['user_name'];
                $_notification->sendEmail('team_notification');
            }
        }

        $this->_db->redir('directory/my_teams?tid=' . $teamIdEnc);
    }

    public function AddTeamDocument()
    {
        if (!empty($_FILES['upload_docs']['tmp_name'])) {

            $teamIdEnc = $this->_db->gpGet('tid');
            $teamId = $this->_db->decode($teamIdEnc);

            $upload_dir = UPLOAD_DIR . "team/" . $teamId . "/";

            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $clean_name = array(" ", "(", ")", "[", "]", "-");
            $file_name = str_replace($clean_name, "_", basename($_FILES['upload_docs']['name']));
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['upload_docs']['tmp_name'], $file_path)) {

                $type = $_FILES['upload_docs']['type'];

                $data = array(
                    "team_id" => $teamId,
                    "type" => $type,
                    "file_name" => $file_name,
                    "title" => $_POST['title'],
                    "description" => $_POST['description'],
                    "upload_date" => $this->currentDateTime,
                    "upload_by" => $_SESSION['user_id'],
                    "status" => 'ACTIVE'
                );

                $this->_db->insertUpdateSQL($data, 'cp_team_file_upload');

                //NOTIFY
                if (isset($_POST['notify_members']) && $_POST['notify_members'] === 'YES') {
                    $_team = new Teams($teamId);

                    $emails = $_team->GetMemberEmailsForNotification();
                    $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);

                    $_user = new users();
                    $userData = $_user->getUserData($_SESSION['user_id']);

                    $from = $_SESSION['user_name'] . "<br>";
                    $from .= $_SESSION['user_email'];
                    $from .= (!empty($userData['phone'])) ? "<br>" . $userData['phone'] : '';

                    $_notification = new notification();
                    foreach ($emails as $k => $val) {
                        $_notification->from = $from;
                        $_notification->fromEmail = $_SESSION['user_email'];
                        $_notification->fromName = $_SESSION['user_name'];
                        $_notification->sendToEmail = $val['email'];
                        $_notification->sendToName = $val['first_name'];
                        $_notification->teamArea = "Document";
                        $_notification->docTitle = $title;
                        $_notification->teamName = $_team->GetTeamName();
                        $_notification->sentByName = $_SESSION['user_name'];
                        $_notification->sendEmail('team_notification');
                    }
                }

                $this->_db->redir('directory/my_teams?tid=' . $teamIdEnc);

            }
        }
    }

    public function editMessage()
    {

        $messageId = $this->_db->decode($this->_db->gpGet('id'));

        $data = array(
            "id" => $messageId,
            "title" => $_POST['title'],
            "message" => $_POST['message'],
            "status" => $_POST['status'],
            "update_by" => $_SESSION['user_id'],
            "update_date" => $this->currentDateTime
        );


        $this->_db->insertUpdateSQL($data, 'cp_team_message_board');

        if (!empty($_FILES['upload_attachment']['tmp_name'][0])) {
            //UPDATED to handle multiple attachments
            $this->UploadFiles($_FILES, $messageId, 'message');
        }

        $this->_db->redir('directory/team_messageboard_list?tid=' . $this->_db->gpGet('tid') . '&e=Saved');
    }

    public function addTeamEvent()
    {

        $teamId = $this->_db->decode($this->_db->gpGet('tid'));

        $eventDate = DateTime::createFromFormat('m-d-Y', $_POST['event_date']);
        $eventDateClean = $eventDate->format('Y-m-d');

        $data = array(
            "event_title" => $_POST['event_title'],
            "event_description" => $_POST['event_description'],
            "event_address" => $_POST['event_address'],
            "event_city" => $_POST['event_city'],
            "event_state" => $_POST['event_state'],
            "event_zip" => $_POST['event_zip'],
            "timezone" => $_POST['timezone'],
            "event_date" => $eventDateClean,
            "event_time" => $_POST['t1'] . ":" . $_POST['t2'] . " " . $_POST['t3'],
            "event_end_time" => $_POST['t1_end'] . ":" . $_POST['t2_end'] . " " . $_POST['t3_end'],
            "url" => $_POST['url'],
            "status" => "ACTIVE",
            "submitted_by" => $_SESSION['user_name'],
            "timestamp" => $this->currentDateTime,
            "agency_id" => $_SESSION['agency_id'],
            "team_id" => $teamId
        );

        //REOCCURING SECTION
        if(isset($_POST['is_reoccuring']) && $_POST['is_reoccuring']=='1'){
            $data['is_reoccuring'] = '1';
            $data['event_reoccuring_type'] = $_POST['event_reoccuring_type'];
            
            $endDate = explode('-',$_POST['event_end_date']);
            $data['event_end_date'] = $endDate[2].'-'.$endDate[0].'-'.$endDate[1];

            //FOR WEEKLY DAY
            if($_POST['event_reoccuring_type']=='Weekly Day' ){
                $data['event_reoccuring_days'] = implode(',',$_POST['event_reoccuring_days']);
            }

        }

        $this->_db->insertUpdateSQL($data, 'cp_team_events_calendar');
        $eventId = $this->_db->last_inserted_id;

        if (!empty($_FILES['upload_attachment']['tmp_name'][0])) {
            //UPDATED to handle multiple attachments
            $this->UploadFiles($_FILES, $eventId, 'events');
        }

        //NOTIFY
        if (isset($_POST['notify_members']) && $_POST['notify_members'] === 'YES') {
            $_team = new Teams($teamId);

            $emails = $_team->GetMemberEmailsForNotification();
            $title = filter_input(INPUT_POST, "event_title", FILTER_SANITIZE_STRING);

            $_user = new users();
            $userData = $_user->getUserData($_SESSION['user_id']);

            $from = $_SESSION['user_name'] . "<br>";
            $from .= $_SESSION['user_email'];
            $from .= (!empty($userData['phone'])) ? "<br>" . $userData['phone'] : '';

            $_notification = new notification();
            foreach ($emails as $k => $val) {
                $_notification->from = $from;
                $_notification->fromEmail = $_SESSION['user_email'];
                $_notification->fromName = $_SESSION['user_name'];
                $_notification->sendToEmail = $val['email'];
                $_notification->sendToName = $val['first_name'];
                $_notification->teamArea = "Event";
                $_notification->docTitle = $title;
                $_notification->teamName = $_team->GetTeamName();
                $_notification->sentByName = $_SESSION['user_name'];
                $_notification->sendEmail('team_notification');
            }
        }

        $this->_db->redir('directory/my_teams?tid=' . $this->_db->gpGet('tid'));
    }

    public function editTeamEvent()
    {

        global $_core, $_db;

        $eventId = $_core->decode($_core->gpGet('id'));

        $eventDate = DateTime::createFromFormat('m-d-Y', $_POST['event_date']);
        $eventDateClean = $eventDate->format('Y-m-d');

        $data = array(
            "id" => $eventId,
            "event_title" => $_POST['event_title'],
            "event_description" => $_POST['event_description'],
            "event_address" => $_POST['event_address'],
            "event_city" => $_POST['event_city'],
            "event_state" => $_POST['event_state'],
            "event_zip" => $_POST['event_zip'],
            "timezone" => $_POST['timezone'],
            "event_date" => $eventDateClean,
            "event_time" => $_POST['t1'] . ":" . $_POST['t2'] . " " . $_POST['t3'],
            "event_end_time" => $_POST['t1_end'] . ":" . $_POST['t2_end'] . " " . $_POST['t3_end'],
            "url" => $_POST['url'],
            "status" => $_POST['status'],
            "update_by" => $_SESSION['user_id'],
            "update_date" => $this->currentDateTime,
            "timestamp" => $this->currentDateTime,
        );

        //REOCCURING SECTION
        if(isset($_POST['is_reoccuring']) && $_POST['is_reoccuring']=='1'){
            $data['is_reoccuring'] = '1';
            $data['event_reoccuring_type'] = $_POST['event_reoccuring_type'];
            

            $endDate = explode('-',$_POST['event_end_date']);
            $data['event_end_date'] = $endDate[2].'-'.$endDate[0].'-'.$endDate[1];
            //FOR WEEKLY DAY
            if($_POST['event_reoccuring_type']=='Weekly Day' ){
                $data['event_reoccuring_days'] = implode(',',$_POST['event_reoccuring_days']);
            }
            else{
                $data['event_reoccuring_days'] = NULL;
            }

        }
        else{
            $data['is_reoccuring'] = '0';
            $data['event_reoccuring_type'] = NULL;          
            $data['event_reoccuring_days'] = NULL;
        }

//        $this->debugData($data);

        $this->_db->insertUpdateSQL($data, 'cp_team_events_calendar');

        if (!empty($_FILES['upload_attachment']['tmp_name'][0])) {
            //UPDATED to handle multiple attachments
            $this->UploadFiles($_FILES, $eventId, 'events');
        }

        $this->_db->redir('directory/edit_team_event?tid=' . $this->_db->gpGet('tid'));
    }

    private function debugData($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit;
    }

    private function UploadFiles($filesArr = [], $id, $section)
    {

        $upload_dir = UPLOAD_DIR . "team/" . $_SESSION['parent_agency'] . "/" . $section . "/";

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $cnt = (count($filesArr['upload_attachment']['name']) - 1);
        for ($i = 0; $i <= $cnt; $i++) {

            $clean_name = array(" ", "(", ")", "[", "]", "-");
            $file_name = str_replace($clean_name, "_", basename($filesArr['upload_attachment']['name'][$i]));
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($filesArr['upload_attachment']['tmp_name'][$i], $file_path)) {
                $type = $filesArr['upload_attachment']['tmp_name'][$i];
                $fileData = [
                    'file_name' => $file_name,
                    'type' => $type,
                    'upload_date' => $this->currentDateTime,
                    'upload_by' => $_SESSION['user_id'],
                    'status' => 'ACTIVE'
                ];

                $fileDataInput = ($section === 'events') ? array_merge(['event_id' => $id], $fileData) : array_merge(['message_id' => $id], $fileData);

                $dbTable = ($section === 'events') ? 'cp_team_events_calendar_file_upload' : 'cp_team_message_board_file_upload';

                $this->_db->insertUpdateSQL($fileDataInput, $dbTable);
            }
        }
    }

    public function DeleteEventFile()
    {
        echo 'Delete File';
    }

}

//end action

