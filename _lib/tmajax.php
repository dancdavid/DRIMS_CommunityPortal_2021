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

    public function GetTeams()
    {
        $_level = new Level1();

        $qry = "select 
                id,
                team_name,
                description,
                level_1
                from cp_teams
                where parent_agency_id = :id";

        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':id' => $_SESSION['parent_agency']]);


        $data = [];
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $_team = new Teams($f->id);
            $level1 = $_level->GetLevel1Name($f->level_1);

            $data['aaData'][] = array(
                "<a href='edit_team?tid={$this->_db->encode($f->id)}'>" . $f->team_name . "</a>",
                $level1,
                $f->description,
                $_team->GetTeamMemberCount(),
                "<a href='my_teams?tid={$this->_db->encode($f->id)}'>View Team Board</a>"
            );
        }

        echo json_encode($data);
    }

    public function ListPotentialTeamMembers()
    {
        $qry = "select * from cp_list_all_users where parent_agency = :id and user_status = 'ACTIVE'";

        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':id' => $_SESSION['parent_agency']]);

        $teamId = $this->_db->decode($this->_db->gpGet('tid'));

        $data = [];
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $userChkSelected = ($this->CheckIfTeamMemberIsSelected($teamId,$f->user_id) == true) ? 'checked' : '';
            $userChkRole = ($this->CheckIfTeamMemberIsSelected($teamId,$f->user_id,'admin') == true) ? 'checked' : '';

            $data['aaData'][] = array(
                "<input type='checkbox' name='uid[{$f->user_id}]' value='{$this->_db->encode($f->user_id)}' {$userChkSelected}>",
                "<input type='checkbox' name='role[{$f->user_id}]' value='ADMIN' {$userChkRole}>",
                $f->first_name . ' ' . $f->last_name,
                $f->email,
                $f->agency_name
            );
        }

        echo json_encode($data);
    }

    private function CheckIfTeamMemberIsSelected($teamId,$userId,$chkRole = 'user')
    {
        $qry = "select * from cp_team_members where team_id = :teamId and user_id = :userId";
        $qry .= ($chkRole === 'admin') ? " and role = 'ADMIN'" : "";

        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':teamId' => $teamId, ':userId' => $userId]);

        if ( $sth->rowCount() > 0 )
            return true;
        else
            return false;
    }

    public function GetAllTeamLinks()
    {
        $qry = "select id,title,description,url,status from cp_team_dashboard_links where team_id = :id and `status` <> 'DELETE' order by id desc";
        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':id' => $this->_db->decode($this->_db->gpGet('tid'))]);

        $data = [];
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
            $data['aaData'][] = array(
                $f->title,
                $f->url,
                $f->description,
                "<a href='edit_link?id={$this->_db->encode($f->id)}&loc=team' class='btn btn-success'>EDIT</a>"
            );
        }

        echo json_encode($data);
    }

    public function GetAllTeamDocs()
    {
        $qry = "select id,title,description,status from cp_team_file_upload where team_id = :id and `status` <> 'DELETE' order by id desc";
        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':id' => $this->_db->decode($this->_db->gpGet('tid'))]);

        $data = [];
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
            $data['aaData'][] = array(
                $f->title,
                $f->description,
                $f->status,
                "<a href='edit_docs?id={$this->_db->encode($f->id)}&loc=team' class='btn btn-success'>EDIT</a>"
            );
        }

        echo json_encode($data);
    }

    public function GetTeamMessageBoard()
    {
        $teamIdEnc = $this->_db->gpGet('tid');
        $teamId = $this->_db->decode($teamIdEnc);

        $dbh = $this->_db->initDB();
        $qry = "select * from cp_team_message_board where team_id = :teamId";
        $sth = $dbh->prepare($qry);
        $sth->execute([':teamId' => $teamId]);

        $data = [];
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $class = "label label-success";
            if ($f->status === 'DELETED') {
                $class = "label label-warning";
            }

            $data['aaData'][] = array(
                strtoupper($f->title),
                "<span class='{$class}'>" . $f->status . "</span>",
                $f->submitted_by,
                "<a href='edit_team_messageboard?id={$this->_db->encode($f->id)}&tid={$teamIdEnc}' class='btn btn-xs btn-danger'>Edit</a>"
            );
        }
        echo json_encode($data);
    }

    public function LoadTeamCalInfo()
    {
        $teamIdEnc = $this->_db->gpGet('tid');
        $teamId = $this->_db->decode($teamIdEnc);

        $qry = "select * from cp_team_events_calendar where team_id = :teamId and `status` = 'ACTIVE'";

        $dbh = $this->_db->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':teamId' => $teamId]);

        $_files = new TeamEventFiles();

        $data = [];
        while($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $title = $f->event_title . ' on ' . date('M-d-Y', strtotime($f->event_date)) . ' @ ' . $f->event_time;
            $address = $f->event_address . ' ' . $f->event_city . ' ' . $f->event_state . ' ' . $f->event_zip;
            $eventWebsite = (!empty($f->url)) ? "<a href='{$f->url}' class='btn btn-success' target='_blank'>Event Website</a>" : "";
            $time = date("H:i", strtotime($f->event_time));
            $endTime = date("H:i", strtotime($f->event_end_time));
            $eventFiles = '<b>Files:</b><br>' . $_files->GetFiles($f->id, 'cp_team_events_calendar_file_upload', 'events');

            //EVENT TIME
            if (!empty($f->event_end_time))
            {
                $eventTime = $f->event_time . ' - ' . $f->event_end_time;
            } else if (!empty($f->event_time)) {
                $eventTime = $f->event_time;
            } else {
                $eventTime = '';
            }
            if($f->is_reoccuring=='1'){
                $title = $f->event_title .' @ ' . $f->event_time;
                if($f->event_reoccuring_type=='Weekly Day'){
                    $startDate = $f->event_date;
                    for($i=1; $i<=52; $i++){
                        $days = explode(',', $f->event_reoccuring_days);
                        $daysArray = [
                                        '0'=>'sunday this week',
                                        '1'=>'monday this week',
                                        '2'=>'tuesday this week',
                                        '3'=>'wednesday this week',
                                        '4'=>'thursday this week',
                                        '5'=>'friday this week',
                                        '6'=>'saturday this week',
                                    ];
                        foreach($days as $day){                    
                            if(array_key_exists($day,$daysArray)){
                                if(!is_null($f->event_end_date) && strtotime($f->event_end_date)>=strtotime($startDate)){
                                    if(strtotime($daysArray[$day], strtotime($startDate))>strtotime($f->event_date)){
                                        $data[] = [
                                            'id' => $f->id,
                                            'title' => $title,
                                            'description' => $f->event_description,
                                            'address' => $address,
                                            'eventWhen' => date("Y-m-d", strtotime($daysArray[$day], strtotime($startDate))) . ' @ ' . $eventTime,
                                            'eventUrl' => $eventWebsite,
                                            'eventFiles' => $eventFiles,
                                            'start' => date("Y-m-d", strtotime($daysArray[$day], strtotime($startDate))) . 'T' . $time,
                                            'end' => date("Y-m-d", strtotime($daysArray[$day], strtotime($startDate))) . 'T' . $endTime,
                                            'ics' => '<a href="../_lib/download_ics.php?id='.$this->_db->encode($f->id).'&loc=team" class="btn btn-default" target="_blank"><small>Download Event ICS File</small> <span class="glyphicon glyphicon-calendar" aria-hidden="true" ></span></a>'
                                        ];
                                    }
                                }
                            }
                        }
                        $startDate = date('Y-m-d', strtotime($startDate.' +7 day'));                        
                    }
                }
                elseif($f->event_reoccuring_type=='Daily'){
                    $startDate = $f->event_date;
                    for($i=1; $i<=365; $i++){
                        if(!is_null($f->event_end_date) && strtotime($f->event_end_date)>=strtotime($startDate)){
                            $data[] = [
                                'id' => $f->id,
                                'title' => $title,
                                'description' => $f->event_description,
                                'address' => $address,
                                'eventWhen' => date('M-d-Y', strtotime($startDate)) . ' @ ' . $eventTime,
                                'eventUrl' => $eventWebsite,
                                'eventFiles' => $eventFiles,
                                'start' => $startDate . 'T' . $time,
                                'end' => $startDate . 'T' . $endTime,
                                'ics' => '<a href="../_lib/download_ics.php?id='.$this->_db->encode($f->id).'&loc=team" class="btn btn-default" target="_blank"><small>Download Event ICS File</small> <span class="glyphicon glyphicon-calendar" aria-hidden="true" ></span></a>'
                            ];
                            $startDate = date('Y-m-d', strtotime($startDate.' +1 day'));
                        }
                    }
                }
                elseif($f->event_reoccuring_type=='Weekly'){
                    $startDate = $f->event_date;
                    for($i=1; $i<=52; $i++){
                        if(!is_null($f->event_end_date) && strtotime($f->event_end_date)>=strtotime($startDate)){
                            $data[] = [
                                'id' => $f->id,
                                'title' => $title,
                                'description' => $f->event_description,
                                'address' => $address,
                                'eventWhen' => date('M-d-Y', strtotime($startDate)) . ' @ ' . $eventTime,
                                'eventUrl' => $eventWebsite,
                                'eventFiles' => $eventFiles,
                                'start' => $startDate . 'T' . $time,
                                'end' => $startDate . 'T' . $endTime,
                                'ics' => '<a href="../_lib/download_ics.php?id='.$this->_db->encode($f->id).'&loc=team" class="btn btn-default" target="_blank"><small>Download Event ICS File</small> <span class="glyphicon glyphicon-calendar" aria-hidden="true" ></span></a>'
                            ];
                            $startDate = date('Y-m-d', strtotime($startDate.' +7 day'));
                        }
                    }
                }

                elseif($f->event_reoccuring_type=='Monthly'){
                    $startDate = $f->event_date;
                    $getDate = date('d',strtotime($startDate));
                    for($i=1; $i<=12; $i++){
                        if(!is_null($f->event_end_date) && strtotime($f->event_end_date)>=strtotime($startDate)){
                            $setDate = date('Y-m', strtotime($startDate)).'-'.$getDate;
                            if(date('m',strtotime($setDate))==date('m',strtotime($startDate))){
                                $data[] = [
                                    'id' => $f->id,
                                    'title' => $title,
                                    'description' => $f->event_description,
                                    'address' => $address,
                                    'eventWhen' => date('M-d-Y', strtotime($setDate)) . ' @ ' . $eventTime,
                                    'eventUrl' => $eventWebsite,
                                    'eventFiles' => $eventFiles,
                                    'start' => $setDate . 'T' . $time,
                                    'end' => $setDate . 'T' . $endTime,
                                    'ics' => '<a href="../_lib/download_ics.php?id='.$this->_db->encode($f->id).'&loc=team" class="btn btn-default" target="_blank"><small>Download Event ICS File</small> <span class="glyphicon glyphicon-calendar" aria-hidden="true" ></span></a>'
                                ];
                            }
                            $startDate = date('Y-m-d', strtotime($startDate.' +1 month'));
                        }
                    }
                }
            }
            else{
                $data[] = [
                    'id' => $f->id,
                    'title' => $title,
                    'description' => $f->event_description,
                    'address' => $address,
                    'eventWhen' => date('M-d-Y', strtotime($f->event_date)) . ' @ ' . $eventTime,
                    'eventUrl' => $eventWebsite,
                    'eventFiles' => $eventFiles,
                    'start' => $f->event_date . 'T' . $time,
                    'end' => $f->event_date . 'T' . $endTime,
                    'ics' => '<a href="../_lib/download_ics.php?id='.$this->_db->encode($f->id).'&loc=team" class="btn btn-default" target="_blank"><small>Download Event ICS File</small> <span class="glyphicon glyphicon-calendar" aria-hidden="true" ></span></a>'
                ];
            }
            
        }

        echo json_encode($data);
    }

    public function getTeamCalendarList()
    {
        $teamIdEnc = $this->_db->gpGet('tid');
        $teamId = $this->_db->decode($teamIdEnc);

        $dbh = $this->_db->initDB();
        $qry = "select * from cp_team_events_calendar where team_id = :teamId";
        $sth = $dbh->prepare($qry);
        $sth->execute([':teamId' => $teamId]);

        $data = array();
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $class = "label label-success";
            if ($f->status === 'DELETED') {
                $class = "label label-warning";
            }

            $data['aaData'][] = array(
                strtoupper($f->event_title),
                date('M-d-Y', strtotime($f->event_date)),
                "<span class='{$class}'>" . $f->status . "</span>",
                $f->submitted_by,
                "<a href='edit_team_event_data?id={$this->_db->encode($f->id)}&tid={$teamIdEnc}' class='btn btn-xs btn-danger'>Edit</a>"
            );
        }

        echo json_encode($data);
    }

    public function DeleteEventFile()
    {
        if (!empty($_POST['id']))
        {
            $id = $this->_db->decode($_POST['id']);
            $qry = "update cp_team_events_calendar_file_upload set `status` = 'DELETE' where id = '{$id}'";
            $sth = $this->_db->initDB();
            $sth->query($qry);
        }
    }

    public function DeleteMessageFile()
    {
        if (!empty($_POST['id']))
        {
            $id = $this->_db->decode($_POST['id']);
            $qry = "update cp_team_message_board_file_upload set `status` = 'DELETE' where id = '{$id}'";
            $sth = $this->_db->initDB();
            $sth->query($qry);
        }
    }

}

?>