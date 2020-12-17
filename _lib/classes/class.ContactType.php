<?php

class ContactType extends db {

    private $_dbh;

    public function __construct()
    {
        $this->_dbh = $this->initDB();
    }

    public function BuildContactDropDown($selected = '')
    {
//        $agencyId = ($_SESSION['parent_agency'] != '-1') ? $_SESSION['parent_agency'] : $_SESSION['agency_id'];
        $qry = "select id, contact_type from cp_contact_types where parent_agency_id = :agencyId and `status` = 'ACTIVE' order by contact_type asc";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':agencyId' => $_SESSION['parent_agency']]);

        $htm = '';
        while ($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $selectedArr = explode(";", $selected);

            $select = (in_array($f->id, $selectedArr) || $selected == 'All') ? "selected" : "";
            $htm .= "<option value='{$f->id}' {$select}>{$f->contact_type}</option>";
        }

        return $htm;
    }

    public function BuildContactTypeDropDownFilter($selected = '')
    {
        $qry = "select contact_type from cp_contact_types where parent_agency_id = :parent and `status` = 'ACTIVE' order by contact_type asc";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':parent' => $_SESSION['parent_agency']]);

        $htm = '';
        while ($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $selectedArr = explode(",", $selected);

            $select = (in_array($f->contact_type, $selectedArr) ) ? "selected" : "";
            $htm .= "<option value='{$f->contact_type}' {$select}>{$f->contact_type}</option>";
        }

        return $htm;
    }

    public function GetContactTypeName($ctId)
    {
        $contactType1Arr = explode(";", $ctId);

        $data = '';
        foreach($contactType1Arr as $val)
        {
            $qry = "select contact_type from cp_contact_types where id = :clId";
            $dbh = $this->initDB();
            $sth = $dbh->prepare($qry);
            $sth->execute([':clId' => $val]);
            $data .=  $sth->fetchColumn() . ',';
        }

        return rtrim($data, ',');
    }

}
