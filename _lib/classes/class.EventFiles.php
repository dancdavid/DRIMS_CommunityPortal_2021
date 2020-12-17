<?php

class EventFiles extends db
{
    protected $_dbh;

    public function __construct()
    {
        $this->_dbh = $this->initDB();
    }

    public function GetFiles($id, $table, $section)
    {
        $qry = "select * from {$table} where";
        $qry .= ($section === 'events') ? " event_id = :id" : " message_id = :id";
        $qry .= " and `status` = 'ACTIVE'";

        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':id' => $id]);

        $htm = '';
        if ($sth->rowCount() > 0)
        {
            while ($f = $sth->fetch(PDO::FETCH_OBJ))
            {
                $uploadDir = ROOT_URL . "uploads/dashboard/" . $_SESSION['parent_agency'] . "/".$section."/" . $f->file_name;
                $htm .= "<a href='{$uploadDir}' target='blank'>{$f->file_name}</a><br>";
            }
        }

        return $htm;
    }

    public function DeleteFilesTable($eventId,$table)
    {
        $qry = "select * from {$table} where event_id = :eventId and `status` = 'ACTIVE'";
        $sth = $this->_dbh->prepare($qry);
        $sth->execute([':eventId' => $eventId]);

        $htm = '';
        if ($sth->rowCount() > 0)
        {

            $htm .= '<table class="table table-bordered" id="files-table" style="margin-left:15px;width:30%;">';
            $htm .= "<tr><th colspan='2''>Files</th></tr>";
            while ($f = $sth->fetch(PDO::FETCH_OBJ))
            {
                $uploadDir = ROOT_URL . "uploads/team/" . $_SESSION['parent_agency'] . "/events/" . $f->file_name;

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