<?php

class Dashboard extends db {

    public function BuildLinksList($filter='')
    {
        $qry = "select * from cp_dashboard_links where ";


        if (!empty($filter) && $filter != 'null') {
            $qry .= " (";
            $filterArr = explode(",",$filter);
            $i=0;
            foreach($filterArr as $find)
            {
                if ($i > 0) $qry .= " or";
                $qry .= " find_in_set('{$find}', replace(level_1, ';', ','))";
                $i++;
            }
            $qry .= " ) and";
        }

        $qry .= " `status` = 'ACTIVE' and parent_agency_id = :parentId";


        $qry .= " order by title";

        $_dbh = $this->initDB();
        $sth = $_dbh->prepare($qry);
        $sth->execute([':parentId' => $_SESSION['parent_agency']]);

        $htm = '';
        $htm .= '<ul>';
        while ($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $htm .= '<li>';
            $htm .= "<a href='{$f->url}' data-toggle='tooltip' data-placement='top' title='{$f->description}' target='_blank'>{$f->title}</a>";
            $htm .= '</li>';
        }
        $htm .= '</ul>';

        return $htm;
    }

    public function BuildDocsList($filter='')
    {
        $qry = "select * from cp_file_upload where ";


        if (!empty($filter) && $filter != 'null') {
            $qry .= " (";
            $filterArr = explode(",",$filter);
            $i=0;
            foreach($filterArr as $find)
            {
                if ($i > 0) $qry .= " or";
                $qry .= " find_in_set('{$find}', replace(level_1, ';', ','))";
                $i++;
            }
            $qry .= " ) and";
        }

        $qry .= " `status` = 'ACTIVE' and parent_agency = :parent";

        $qry .= " order by title";

        $_dbh = $this->initDB();
        $sth = $_dbh->prepare($qry);
        $sth->execute([':parent' => $_SESSION['parent_agency']]);

        $htm = '';
        $htm .= '<ul>';
        while ($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $htm .= "<li>";
            $htm .= "<a href='../uploads/dashboard/{$_SESSION['parent_agency']}/{$f->file_name}' data-toggle='tooltip' data-placement='top' title='{$f->description}' download>";
            $htm .= $f->title;
            $htm .= "</a>";
            $htm .= "</li>";
        }
        $htm .= '</ul>';

        return $htm;
    }

    public function GetLinkData($id,$table='cp_dashboard_links')
    {
        $qry = "select * from $table where id = :id";
        $_dbh = $this->initDB();
        $sth = $_dbh->prepare($qry);
        $sth->execute([':id' => $id]);

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function GetDocData($id,$table='cp_file_upload')
    {
        $qry = "select * from $table where id = :id";
        $_dbh = $this->initDB();
        $sth = $_dbh->prepare($qry);
        $sth->execute([':id' => $id]);

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function GetEventsData($idEnc, $table = 'cp_events_calendar')
    {
        $id = $this->decode($idEnc);
        $qry = "select * from {$table} where id = :id";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':id' => $id]);

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

}
