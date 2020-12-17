<?php

class ContactLicenseType extends db {

    private $_dbh;

    public function __construct()
    {
        $this->_dbh = $this->initDB();
    }

    public function BuildContactLicenseDropDown($selected = '')
    {
//        $agencyId = ($_SESSION['parent_agency'] != '-1') ? $_SESSION['parent_agency'] : $_SESSION['agency_id'];
        $qry = "select id, contact_license_type from cp_contact_license_type where parent_agency_id = :agencyId and `status` = 'ACTIVE' order by contact_license_type asc";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':agencyId' => $_SESSION['parent_agency']]);

        $htm = '';
        while ($f = $sth->fetch(PDO::FETCH_OBJ))
        {
            $selectedArr = explode(";", $selected);

            $select = (in_array($f->id, $selectedArr) || $selected == 'All') ? "selected" : "";
            $htm .= "<option value='{$f->id}' {$select}>{$f->contact_license_type}</option>";
        }

        return $htm;
    }

    public function GetContactLicenseName($clId)
    {
        $contactLic1Arr = explode(";", $clId);

        $data = '';
        foreach($contactLic1Arr as $val)
        {
            $qry = "select contact_license_type from cp_contact_license_type where id = :clId";
            $dbh = $this->initDB();
            $sth = $dbh->prepare($qry);
            $sth->execute([':clId' => $val]);
            $data .=  $sth->fetchColumn() . ',';
        }

        return rtrim($data, ',');
    }
}
