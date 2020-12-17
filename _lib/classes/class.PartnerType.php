<?php

class PartnerType extends db {

    private $_dbh;

    public function __construct()
    {
        $this->_dbh = $this->initDB();
    }

    public function BuildPartnerDropDown($selected = '')
    {
//        $agencyId = ($_SESSION['parent_agency'] != '-1') ? $_SESSION['parent_agency'] : $_SESSION['agency_id'];
        $qry = "select id, partner_type from cp_partner_types where parent_agency_id = :agencyId and `status` = 'ACTIVE' order by partner_type asc";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':agencyId' => $_SESSION['parent_agency']]);

        $htm = '';
        while ($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $selectedArr = explode(";", $selected);

            $select = (in_array($f->id, $selectedArr) || $selected == 'All') ? "selected" : "";
            $htm .= "<option value='{$f->id}' {$select}>{$f->partner_type}</option>";
        }

        return $htm;
    }

    public function BuildOrgPartnerTypeDropDownFilter($selected = '')
    {
        $qry = "select partner_type from cp_partner_types where parent_agency_id = :parent and `status` = 'ACTIVE' order by partner_type asc";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':parent' => $_SESSION['parent_agency']]);

        $htm = '';
        while ($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $selectedArr = explode(",", $selected);

            $select = (in_array($f->partner_type, $selectedArr) ) ? "selected" : "";
            $htm .= "<option value='{$f->partner_type}' {$select}>{$f->partner_type}</option>";
        }

        return $htm;
    }

    public function GetPartnerTypeName($ptId)
    {
        $partnerType1Arr = explode(";", $ptId);

        $data = '';
        foreach($partnerType1Arr as $val)
        {
            $qry = "select partner_type from cp_partner_types where id = :clId";
            $dbh = $this->initDB();
            $sth = $dbh->prepare($qry);
            $sth->execute([':clId' => $val]);
            $data .=  $sth->fetchColumn() . ',';
        }

        return rtrim($data, ',');
    }
}