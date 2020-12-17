<?php

class Teams extends db {

    private $teamId;

    public function __construct($teamId)
    {
        $this->teamId = $teamId;
    }

    public function GetTeamData()
    {
        $dbh = $this->initDB();
        $sth = $dbh->prepare("select * from cp_teams where id = :id");
        $sth->execute([':id' => $this->teamId]);
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function BuildSelectedMembers()
    {
        $qry = "select * from cp_complete_team_members_list where team_id = :id order by first_name";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':id' => $this->teamId]);

        $htm = '';
        if ($sth->rowCount() > 0)
        {
            $htm .= '<table class="table table-bordered table-striped">';
            $htm .= '<tr><td colspan="6"><h4>TEAM MEMBERS</h4></td></tr>';
            $htm .= '<tr>';
            $htm .= '<th>Name</th>';
            $htm .= '<th>Role</th>';
            $htm .= '<th>Email</th>';
            $htm .= '<th>Org Name</th>';
            $htm .= '<th>Status</th>';
            $htm .= '</tr>';

            while ($f = $sth->fetch(PDO::FETCH_OBJ))
            {
                $name = $f->first_name . ' ' . $f->last_name;
                $htm .= "<form action='../_lib/tmaction.php?action={$this->encode('DelMember')}' method='post'>";
                $htm .= "<input type='hidden' name='tmid' value='{$this->encode($f->team_member_id)}'>";
                $htm .= "<input type='hidden' name='tid' value='{$this->encode($f->team_id)}'>";
                $htm .= '<tr>';
                $htm .= "<td>{$name}</td>";
                $htm .= "<td>{$f->role}</td>";
                $htm .= "<td><a target='_blank' href='mailto:{$f->email}'>{$f->email}</a></td>";
                $htm .= "<td>{$f->agency_name}</td>";
                $htm .= "<td>{$f->status}</td>";
                $htm .= "<td align='center'><button class='btn btn-xs btn-danger del' type='submit'>Remove</button></td>";
                $htm .= '</tr>';
                $htm .= '</form>';
            }

            $htm .= '</table>';
        }

        return $htm;
    }

    public function GetTeamMemberCount()
    {
        $qry = "select count(*) as CNT 
                from cp_complete_team_members_list 
                where team_id = :teamId 
                and status = 'ACTIVE'";

        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':teamId' => $this->teamId]);

        return $sth->fetchColumn();
    }

    public function GetTeamName()
    {
        $qry = "select team_name from cp_teams where id = :id";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':id' => $this->teamId]);

        return $sth->fetchColumn();
    }

    public function TeamMemberAdmin()
    {
        $qry = "select role from cp_team_members where team_id = :teamId and user_id = :uid";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':teamId' => $this->teamId, ':uid' => $_SESSION['user_id']]);
        $role = $sth->fetchColumn();

        if ($role == 'ADMIN')
            return true;
        else
            return false;
    }

    public function BuildTeamMessageBoard()
    {

        $teamIdEnc = $this->gpGet('tid');
        $teamId = $this->decode($teamIdEnc);

        $qry = "select * from cp_team_message_board
        where team_id = :teamId and `status` = 'ACTIVE'
        order by id desc";

        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':teamId' => $teamId]);

        $_files = new TeamMessageFiles();
        $files = '';
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $post_date = date("M-d-Y", strtotime($f->timestamp));
            $files = $_files->GetFiles($f->id);

            echo "<b>{$f->title}</b> <br>";
            echo $f->message . "<br>";
            echo $files;
            echo "<small class='pull-right'>{$post_date} - <i>{$f->submitted_by}</i></small><hr></hr><br>";
        }

    }

    public function BuildTeamLinks()
    {
        $teamIdEnc = $this->gpGet('tid');
        $teamId = $this->decode($teamIdEnc);

        $qry = "select * from cp_team_dashboard_links
                where team_id = :teamId and `status` = 'ACTIVE'
                order by title";

        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':teamId' => $teamId]);

        $htm = '';
        $htm .= '<ul>';
        while ($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $htm .= '<li>';
            $htm .= "<a href='{$f->url}' data-toggle='tooltip' data-placement='top' title='{$f->description}' target='_blank'>{$f->title}</a>";
            $htm .= '</li>';
        }
        $htm .= '</ul>';

        echo $htm;
    }

    public function BuildTeamDocuments()
    {
        $teamIdEnc = $this->gpGet('tid');
        $teamId = $this->decode($teamIdEnc);

        $qry = "select * from cp_team_file_upload
                where team_id = :teamId and `status` = 'ACTIVE'
                order by title";

        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':teamId' => $teamId]);

        $htm = '';
        $htm .= '<ul>';
        while ($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $htm .= "<li>";
            $htm .= "<a href='../uploads/team/{$teamId}/{$f->file_name}' data-toggle='tooltip' data-placement='top' title='{$f->description}' download>";
            $htm .= $f->title;
            $htm .= "</a>";
            $htm .= "</li>";
        }
        $htm .= '</ul>';

        echo $htm;
    }

    public function GetOrgTeamReportCount($table)
    {
        $qry = "select id from $table where team_id = :teamId and `status` = 'ACTIVE'";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':teamId' => $this->teamId]);
        return $sth->rowCount();
    }

    public function GetOrgTeamReportLastUpdate($table)
    {
        $qry = "select update_date from $table 
                where team_id = :teamId and `status` = 'ACTIVE'
                order by id desc limit 1";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':teamId' => $this->teamId]);

        if ($sth->rowCount() > 0)
            return $sth->fetchColumn();
        else
            return null;
    }

    public function GetMemberEmailsForNotification()
    {
        $qry = "select ctm.team_id, ctm.user_id
                    , ou.email, ou.status, ou.first_name, ou.last_name
                    , ou.cp_notification
                    from cp_team_members ctm
                        join org_users ou on ctm.user_id = ou.id
                        where ctm.team_id = :teamId
                        and ou.status = 'ACTIVE'
                        and ou.cp_notification = 'YES'";

        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':teamId' => $this->teamId]);

        $data = [];
        while($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $data[] = [
                'email' => $f->email,
                'first_name' => $f->first_name
            ];
        }

        return $data;
    }

    public function ShowTeamMembers()
    {
        $qry = "select * from cp_complete_team_members_list where team_id = :id order by first_name";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':id' => $this->teamId]);

        $htm = '';
        if ($sth->rowCount() > 0)
        {
            $htm .= '<table class="table table-bordered table-striped">';
            $htm .= '<tr><td colspan="6"><h4>TEAM MEMBERS <button class="btn btn-danger btn-sm pull-right" id="close-members" style="margin-top:-5px;">CLOSE</button></h4></td></tr>';
            $htm .= '<tr>';
            $htm .= '<th>Name</th>';
            $htm .= '<th>Role</th>';
            $htm .= '<th>Email</th>';
            $htm .= '</tr>';

            while ($f = $sth->fetch(PDO::FETCH_OBJ))
            {
                $name = $f->first_name . ' ' . $f->last_name;
                $htm .= '<tr>';
                $htm .= "<td>{$name}</td>";
                $htm .= "<td>{$f->role}</td>";
                $htm .= "<td><a target='_blank' href='mailto:{$f->email}'>{$f->email}</a></td>";
                $htm .= '</tr>';
            }

            $htm .= '</table>';
        }

        return $htm;
    }

}
