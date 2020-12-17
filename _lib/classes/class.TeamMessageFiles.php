<?php

class TeamMessageFiles extends db
{
    protected $_dbh;

    public function __construct()
    {
        $this->_dbh = $this->initDB();
    }

    public function GetFiles($messageId)
    {
        $qry = "select * from cp_team_message_board_file_upload where message_id = :messageId and `status` = 'ACTIVE'";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':messageId' => $messageId]);

        $htm = '';
        if ($sth->rowCount() > 0)
        {
            $htm .= '<b>Files</b><br>';
            while ($f = $sth->fetch(PDO::FETCH_OBJ))
            {
                $uploadDir = ROOT_URL . "uploads/team/" . $_SESSION['parent_agency'] . "/message/" . $f->file_name;
                $htm .= "<a href='{$uploadDir}' target='blank'>{$f->file_name}</a><br>";
            }
        }

        return $htm;
    }

    public function DeleteFilesTable($messageId)
    {
        $qry = "select * from cp_team_message_board_file_upload where message_id = :messageId and `status` = 'ACTIVE'";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':messageId' => $messageId]);

        $htm = '';
        if ($sth->rowCount() > 0)
        {

            $htm .= '<table class="table table-bordered" id="files-table" style="margin-left:15px;width:30%;">';
            $htm .= "<tr><th colspan='2''>Files</th></tr>";
            while ($f = $sth->fetch(PDO::FETCH_OBJ))
            {
                $uploadDir = ROOT_URL . "uploads/team/" . $_SESSION['parent_agency'] . "/message/" . $f->file_name;

                $htm .= "<tr>";
                $htm .= "<td><a href='{$uploadDir}' target='blank'>{$f->file_name}</a></td>";
                $htm .= "<td style='padding:5px 10px; text-align:center;'><button type='submit' class='btn btn-xs btn-danger delete' data-id='{$this->encode($f->id)}'>Delete</td>";
                $htm .= "</tr>";
            }
            $htm .= '</table>';

        }

        return $htm;
    }
}

