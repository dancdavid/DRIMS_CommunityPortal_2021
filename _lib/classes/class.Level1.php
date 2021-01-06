<?php

class Level1 extends db {

    private $_dbh;

    public function __construct()
    {
        $this->_dbh = $this->initDB();
    }

    public function BuildLevelDropDown($selected = '')
    {
//        $agencyId = ($_SESSION['parent_agency'] != '-1') ? $_SESSION['parent_agency'] : $_SESSION['agency_id'];
        $qry = "select id,level_1 from cp_level_1 where parent_agency_id = :agencyId and `status` = 'ACTIVE' order by level_1 asc";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':agencyId' => $_SESSION['parent_agency']]);

        $htm = '';
        while ($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $selectedArr = explode(";", $selected);

            $select = (in_array($f->id, $selectedArr) || $selected == 'All') ? "selected" : "";
            $htm .= "<option value='{$f->id}' {$select}>{$f->level_1}</option>";
        }

        return $htm;
    }

    public function BuildLevelDropDownFilter($selected = '')
    {
        $qry = "select id,level_1 from cp_level_1 where parent_agency_id = :agencyId and `status` = 'ACTIVE' order by level_1 asc";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':agencyId' => $_SESSION['parent_agency']]);

        $htm = '';
        while ($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $selectedArr = explode(",", $selected);

            $select = (in_array($f->id, $selectedArr) ) ? "selected" : "";
            $htm .= "<option value='{$f->id}' {$select}>{$f->level_1}</option>";
        }

        return $htm;
    }

    public function GetLevel1Name($levelIds)
    {
        $level1Arr = explode(";", $levelIds);

        $data = '';
        foreach($level1Arr as $val)
        {
            $qry = "select level_1 from cp_level_1 where id = :levelId";
            $dbh = $this->initDB();
            $sth = $dbh->prepare($qry);
            $sth->execute([':levelId' => $val]);
            $data .=  $sth->fetchColumn() . ', ';
        }

        return rtrim($data, ', ');
    }

    public function GetLevel1Label()
    {   
        $parent_agency = isset($_SESSION['parent_agency']) ? $_SESSION['parent_agency'] : 0;
        $qry = "select label from cp_level_1_label where parent_agency_id = :parent";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':parent' => $parent_agency]);
        return $sth->fetchColumn();
    }

}