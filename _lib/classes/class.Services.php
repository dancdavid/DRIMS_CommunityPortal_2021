<?php

class Services extends db
{

    public $serviceId;
    public $itemId;
    public $subItemId;

    public function EditServices($serviceId)
    {
        $qry = "select * from cp_services where id = :id";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':id' => $serviceId]);

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function EditItems($itemId)
    {
        $qry = "select * from cp_services_item where id = :id";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':id' => $itemId]);

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function EditSubItems($subItemId)
    {
        $qry = "select * from cp_services_sub_item where id = :id";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':id' => $subItemId]);

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function BuildCopyDropDown($serviceId)
    {
        $qry = "select id, type from cp_services where  parent_agency_id= :parentId and `status` = 'ACTIVE'";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':parentId' => $_SESSION['parent_agency']]);

//        $htm = '<div class="well">';
        $htm = "<form action='../_lib/servicesaction.php?action={$this->encode('CopyItems')}' class='form-horizontal' method='post'>";
        $htm .= "<input type='hidden' name='sid' value='{$this->encode($serviceId)}'>";
        $htm .= "<div class='form-group'>";
        $htm .= "<div class='col-md-12'>";
        $htm .= "<label>Copy Items and Sub-Items from another</label>";
        $htm .= "<select class='form-control' name='csid' required>";
        $htm .= "<option value=''>Select Item</option>";

        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
            if ($f->id === $serviceId) {
                $htm .= '';
            } else {
                $htm .= "<option value='{$this->encode($f->id)}'>{$f->type}</option>";
            }

        }

        $htm .= "</select>";
        $htm .= "</div>";

        $htm .= '<div class="text-center" style="padding:20px;"><button class="btn btn-success">COPY</button></div>';
        $htm .= "</div>";
        $htm .= "</div>";
//        $htm .= "</div>";
        $htm .= "</form>";

        return $htm;

    }

    public function CheckIFItemsExists($serviceId)
    {
        $qry = "select id from cp_services_item where service_id = :serviceId and `status` = 'ACTIVE'";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':serviceId' => $serviceId]);

        if ($sth->rowCount() > 0)
            return true;
        else
            return false;
    }

    public function CheckIFServicesExists()
    {
        $qry = "select id from cp_services where parent_agency_id = :parentId and `status` = 'ACTIVE'";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':parentId' => $_SESSION['parent_agency']]);

        if ($sth->rowCount() > 1)
            return true;
        else
            return false;
    }

    public function BuildListOfItems($serviceIdEnc)
    {

        $serviceId = $this->decode($serviceIdEnc);

        $qry = "select * from cp_services_item where service_id = :serviceId";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':serviceId' => $serviceId]);

        $htm = '';
        if ($sth->rowCount() > 0) {
            $htm .= '<ul style="padding-top:15px;">';
            while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
                $htm .= "<li><a href='add_services2?sid={$serviceIdEnc}&iid={$this->encode($f->id)}'>{$f->item}</a></li>";
            }
            $htm .= '</ul>';
        }

        return $htm;

    }

    public function BuildListOfSubItems($itemIdEnc)
    {

        $itemId = $this->decode($itemIdEnc);

        $qry = "select * from cp_services_sub_item where item_id = :itemId";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':itemId' => $itemId]);

        $htm = '';
        if ($sth->rowCount() > 0) {
            $htm .= '<ul style="padding-top:15px;">';
            while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
                $htm .= "<li><a href='add_services2?sid={$this->encode($f->service_id)}&iid={$this->encode($f->item_id)}&siid={$this->encode($f->id)}'>{$f->sub_item}</a></li>";
            }
            $htm .= '</ul>';
        }

        return $htm;

    }

    public function GetCustomItemName($agencyId, $itemId)
    {
        $qry = 'select custom_item_name from cp_custom_item_name 
                where agency_id = :agencyId 
                and parent_agency_id = :parentId 
                and item_id = :itemId';

        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId,
            ':parentId' => $_SESSION['parent_agency'],
            ':itemId' => $itemId]);

        return $sth->fetchColumn();
    }

    public function ChkIfServiceSelected($agencyId, $serviceId)
    {
        $qry = "select agency_id, service_id
                from cp_agency_services
                where agency_id = :agencyId
                and service_id = :serviceId
                union all
                select agency_id, service_id
                from cp_custom_item_name
                where agency_id = :agencyId
                and service_id = :serviceId";

        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId, ':serviceId' => $serviceId]);

        $cnt = $sth->rowCount();

        if ($cnt > 0)
        {
            return true;
        } else {
            return false;
        }
    }

    public function ChkIfLocationServiceSelected($agencyId, $locationId, $serviceId)
    {
        $qry = "select agency_id, location_id, service_id
                from cp_agency_location_services
                where agency_id = :agencyId
                and location_id = :locationId
                and service_id = :serviceId";

        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId, ':locationId' => $locationId, ':serviceId' => $serviceId]);

        $cnt = $sth->rowCount();

        if ($cnt > 0)
        {
            return true;
        } else {
            return false;
        }
    }


}
