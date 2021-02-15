<?php

class agency
{

    private $db;
    private $dbh;
    private $agency_id;

    function __construct($agency_id = null)
    {
        $this->db = new db();
        $this->dbh = $this->db->initDB();
        $this->agency_id = $agency_id;
    }

    public function build_search_breadcrumb($s = null, $t = null, $l = null)
    {
        $qry = "select minor from cp_services_list where id = :id";
        $sth = $this->dbh->prepare($qry);
        $sth->execute(array(":id" => $s));
        $minor = $sth->fetchColumn();

        $htm = (is_null($s) && is_null($t) && is_null($l)) ? "Searching ALL Agencies with Services" : "Searching for {$minor} ";
        $htm .= ((isset($t) || isset($l)) && is_null($s)) ? "ALL Services " : "";
        $htm .= (isset($t)) ? "in Terrebonne " : "";
        $htm .= (isset($l)) ? "in Lafourche" : "";

        return $htm;

    }

    public function getUserAccess($user_id){
        
        $sth = $this->dbh->query("select org_contacts.user_id, org_contacts.cms_org_id, org_contacts.cp_org_id , 
        (CASE WHEN cp_parent_agency IS NOT NULL THEN 'CP' ELSE 'CMS' END) as portal_type,
        (CASE WHEN cp_parent_agency IS NOT NULL THEN 'Agency' ELSE 'Organization' END) as portal_org_type,
        (CASE WHEN cp_org_id IS NOT NULL THEN cp_org_id ELSE cms_org_id END) as org_id,
        org_contacts.status, org_contacts.cms_access_level , org_users.default_org_id, 
        org_users.default_portal_type, org_users.default_agency_id,org_users.homescreen_org_id, org_contacts.cp_access as community_portal, 
        org_contacts.cms_access as case_management , org_information.name as org_name, 
        org_information.community_portal as org_community_portal, 
        org_information.case_management as org_case_management from 
        org_contacts left join 
        org_users on org_contacts.user_id = org_users.id 
        left join org_information on org_information.id = org_contacts.cms_org_id or org_information.cp_parent_child = org_contacts.cp_org_id where org_users.id = {$user_id} order by org_contacts.id asc");
       
        return $sth ? $sth->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    
    public function BuildUserAgencyDropDown($default_agency_id){
        $user_id = $_SESSION['user_id'];
        $sth = $this->dbh->query("select user_id, cp_org_id as agency_id, o.name as agency_name from org_contacts join org_information o on org_contacts.cp_org_id = o.cp_parent_child where user_id = $user_id and cp_org_id IS NOT NULL");
        $htm = "<option value=''>Select Default Agency</option>";

        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
            $htm .= "<option value='".$f->agency_id."' ".($f->agency_id == $default_agency_id ? "selected" : "").">".$f->agency_name."</option>";
        }
        return $htm;
    }

    public function build_services_view()
    {
        $sth = $this->dbh->query("select distinct(major) as major_categories from directory_services_full where agency_id = '$this->agency_id'");

        $htm = "";

        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {


            $htm .= "<div class='panel panel-warning'>";

            //Header - Major Catgories
            $htm .= "<div class='panel-heading'>";
            $htm .= "<h5>" . strtoupper($f->major_categories) . "</h5>";
            $htm .= "</div>";

            //Body Minor Categories
            $htm .= "<div class='panel-body'>";
//            $htm .= "<div class='well'>";
            $htm .= $this->build_minor_services_view($f->major_categories);
//            $htm .= "</div>"; //end well
            $htm .= "</div>"; //end panel body

            $htm .= "</div>"; //end panel
        }


        return $htm;
    }

    private function build_minor_services_view($major_categories)
    {

        $sth = $this->dbh->query("select * from directory_services_full where agency_id = '$this->agency_id' and major = '$major_categories'");

        $htm = "";

//        $htm .= "<div class='row'>";
//        $htm .= "<div class='col-xs-8 col-sm-8'>";
//        $htm .= "<b><small>SERVICES</small></b>";
//        $htm .= "</div>";
//
//        $htm .= "<div class='col-xs-2 col-sm-2'>";
//        $htm .= "<b><small>TERREBONNE</small></b>";
//        $htm .= "</div>";
//
//        $htm .= "<div class='col-xs-2 col-sm-2'>";
//        $htm .= "<b><small>LAFOURCE</small></b>";
//        $htm .= "</div>";
//
//        $htm .= "</div>"; //end row
//
//        $htm .= "<div class='row'><div class='col-xs-12 col-sm-12'>&nbsp</div></div>";

        $htm .= "<table class='table table-striped table-bordered'>";

        $htm .= "<thead>";
        $htm .= "<tr>";
        $htm .= "<th width='80%'><small>SERVICES</small></th>";
        $htm .= "<th width='10%'><small>TERREBONNE</small></th>";
        $htm .= "<th width='10%'><small>LAFOURCHE</small></th>";
        $htm .= "</tr>";
        $htm .= "</thead>";

        $htm .= "<tbody>";
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $htm .= "<tr>";


            //services
            $ter_comment = '';
            if (!empty($f->comments)) {
                $ter_comment = "<a tabindex='0' role='button' data-trigger='focus' data-toggle='popover' data-container='body' title='Comments' data-content='$f->comments'><span class='glyphicon glyphicon-question-sign' aria-hidden='true'></span></a>";
            }
            $htm .= "<td>{$f->minor} {$ter_comment}</td>";


            //terrebonne
            $terrebone = ($f->terrebonne === 'YES') ? "<span class='label label-success'>YES</span>" : "<span class='label label-warning'>NO</span>";

            $htm .= "<td><center>{$terrebone}</center></td>";


            //lafourche
            $lafourche = ($f->lafourche === 'YES') ? "<span class='label label-success'>YES</span>" : "<span class='label label-warning'>NO</span>";
            $htm .= "<td><center>{$lafourche}</center></td>";


            $htm .= "</tr>";


//            $htm .= "<div class='row'>";
//
//            //services
//            $htm .= "<div class='col-xs-8 col-sm-8'>";
//            $htm .= "{$f->minor}";
//            $htm .= "</div>";
//
//            //terrebonne
//            $terrebone = ($f->terrebonne === 'YES') ? '<span class="label label-success">YES</span>' : '<span class="label label-warning">NO</span>';
//            $htm .= "<div class='col-xs-2 col-sm-2'>";
//            $htm .= "<center>{$terrebone}</center>";
//            $htm .= "</div>";
//
//            //lafourche
//            $lafourche = ($f->lafourche === 'YES') ? '<span class="label label-success">YES</span>' : '<span class="label label-warning">NO</span>';
//            $htm .= "<div class='col-xs-2 col-sm-2'>";
//            $htm .= "<center>{$lafourche}</center>";
//            $htm .= "</div>";
//            
//
//            $htm .= "</div>"; //end row
        }
        $htm .= "</tbody>";
        $htm .= "</table>";

        return $htm;
    }

    public function build_services_panel_tmpl()
    {

        $sth = $this->dbh->query("select distinct(major) as major_categories from cp_services_list");

        $htm = "";

        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {


            $htm .= "<div class='panel panel-primary'>";

            //Header - Major Catgories
            $htm .= "<div class='panel-heading'>";
            $htm .= "<h3 class='panel-title'>" . strtoupper($f->major_categories) . "</h3>";
            $htm .= "</div>";

            //Body Minor Categories
            $htm .= "<div class='panel-body'>";
            $htm .= "<div class='well'>";
            $htm .= $this->build_services_minor_tmpl($f->major_categories);
            $htm .= "</div>"; //end well
            $htm .= "</div>"; //end panel body

            $htm .= "</div>"; //end panel
        }


        echo $htm;
    }

    private function build_services_minor_tmpl($major)
    {

        $sth = $this->dbh->prepare("select id as service_id,minor from cp_services_list where major = :major");
        $sth->execute(array(":major" => $major));

        $htm = "";

        $htm .= "<div class='row'>";
        $htm .= "<div class='col-xs-4 col-sm-4'>";
        $htm .= "<b>SERVICES</b>";
        $htm .= "</div>";

        $htm .= "<div class='col-xs-1 col-sm-1'>";
        $htm .= "<b>TERREBONNE</b>";
        $htm .= "</div>";

        $htm .= "<div class='col-xs-1 col-sm-1'>";
        $htm .= "<b>LAFOURCHE</b>";
        $htm .= "</div>";

        $htm .= "<div class='col-xs-6 col-sm-6'>";
        $htm .= "<b>COMMENTS</b>";
        $htm .= "</div>";
        $htm .= "</div>"; //end row

        $htm .= "<div class='row'><div class='col-xs-12 col-sm-12'><hr></hr></div></div>";

        $htm .= "<div class='form-group'>";
        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            $htm .= "<div class='row' style='margin: 0px 10px 10px 10px;'>";

            //services
            $htm .= "<div class='col-xs-4 col-sm-4'>";
            $htm .= $f->minor;
            $htm .= "</div>";

            //terrebonne
            $htm .= "<div class='col-xs-1 col-sm-1'>";
            $htm .= "<input type='hidden' name='terrebonne[{$f->service_id}]' value='NO'>";
            $htm .= "<center><input type='checkbox' name='terrebonne[{$f->service_id}]' value='YES' {$this->chk_minor_services($f->service_id, 'terrebonne')}></center>";
            $htm .= "</div>";

            //lafourche
            $htm .= "<div class='col-xs-1 col-sm-1'>";
            $htm .= "<input type='hidden' name='lafourche[{$f->service_id}]' value='NO'>";
            $htm .= "<center><input type='checkbox' name='lafourche[{$f->service_id}]' value='YES' {$this->chk_minor_services($f->service_id, 'lafourche')}></center>";
            $htm .= "</div>";

            //comments
            $htm .= "<div class='col-xs-6 col-sm-6'>";
            $htm .= "<input type='input' class='form-control' name='comments[{$f->service_id}]' value='{$this->get_services_comments($f->service_id)}'>";
            $htm .= "</div>";

            $htm .= "</div>"; //end row
        }
        $htm .= "</div>"; //end form group


        return $htm;
    }

    private function chk_minor_services($service_id, $section)
    {

        $dbh = $this->db->initDB();
        $qry = "select {$section} from cp_services_directory where service_id = '{$service_id}' and agency_id = '{$this->agency_id}'";
        $sth = $dbh->query($qry);

        if ($sth->fetchColumn() === 'YES') {
            return 'checked';
        } else {
            return null;
        }
    }

    private function get_services_comments($service_id)
    {
        $dbh = $this->db->initDB();
        $qry = "select comments from cp_services_directory where service_id = '{$service_id}' and agency_id = '{$this->agency_id}'";
        $sth = $dbh->query($qry);
        return $sth->fetchColumn();
    }

    public function get_agency($agency_id, $org_id = '')
    {
        $whereQuery = ($agency_id ? "cp_parent_child = $agency_id" : "id = $org_id");
        $dbh = $this->db->initDB();
        $qry = "select name as agency_name, org_phone as agency_telephone, org_fax as agency_fax, address as agency_address,
        city as agency_city, state as agency_state, zipcode as agency_zipcode, description as description, type as user_type,
        url as agency_url, status , cp_parent_child as agency_id, cp_parent_agency as parent_agency, cp_partner_type as partner_type, org_information.level_1
         from org_information where ".$whereQuery;
        $sth = $dbh->prepare($qry);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrgId($agency_id)
    {

        $dbh = $this->db->initDB();
        $qry = "select id from org_information where cp_parent_child = :agency_id LIMIT 1";
        $sth = $dbh->prepare($qry);
        $sth->execute([':agency_id' => $agency_id]);
        $result = $sth->fetchColumn(); 
        if($result){
            return $result;
        }else{
            $dbh = $this->db->initDB();
            $qry = "select default_org_id from org_users where agency_id = :agency_id and default_org_id IS NOT NULL LIMIT 1";
            $sth = $dbh->prepare($qry);
            $sth->execute([':agency_id' => $agency_id]);
            $result = $sth->fetchColumn();
            return $result; 
        }
    }

    public function get_agency_contact($agency_id, $limit = '')
    {
        $dbh = $this->db->initDB();
//        $qry = "select contact_id,contact_name,contact_telephone,contact_cellphone,contact_fax,contact_email,contact_type from cp_directory_contact where agency_id = :agency_id and user_status = 'ACTIVE' order by contact_name asc";
        $qry = "select org_users.id
                ,first_name
                ,last_name
                ,phone
                ,title
                ,extension
                ,alt_phone
                ,email
                ,oc.cp_contact_type as contact_type 
                ,oc.contact_license_type
                ,oc.status
                from org_users 
                join org_contacts oc on oc.user_id = org_users.id
                where oc.cp_org_id = :agency_id
               ";

        if (UserAccess::ManageLevel1() || UserAccess::ManageMyOrg($agency_id)) {
            $qry .= '';
        } else {
            $qry .= " and oc.status = 'ACTIVE'";
        }

        if (!empty($limit)) {
            $qry .= " limit " . $limit;
        } else {
            $qry .= " order by first_name asc";
        }

        $sth = $dbh->prepare($qry);
        $sth->execute(array(":agency_id" => $agency_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_agency_services($agency_id)
    {
        $dbh = $this->db->initDB();
        $qry = "select service_type,service_description from cp_directory_service where agency_id = :agency_id";
        $sth = $dbh->prepare($qry);
        $sth->execute(array(":agency_id" => $agency_id));
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_agency_name($agency_id)
    {
        $dbh = $this->db->initDB();
        $qry = "select name as agency_name from org_information where cp_parent_child = :agency_id";
        $sth = $dbh->prepare($qry);
        $sth->execute(array(":agency_id" => $agency_id));
        return $sth->fetchColumn();
    }

    public function edit_agency_contact($userId, $agency_id = '')
    {
        $dbh = $this->db->initDB();
//        $qry = "select * from cp_directory_contact where contact_id = :contact_id";
        if($agency_id){
            $where = "  where oc.user_id = :id and
             oc.cp_org_id = :agency_id ";
        }else{
            $where = " where oc.user_id = :id limit 1";
        }
        $qry = "select org_users.id
            ,first_name
            ,last_name
            ,phone
            ,title
            ,extension
            ,alt_phone
            ,email
            ,oc.cp_contact_type as contact_type 
            ,oc.cp_level_1 as level_1
            ,oc.cp_community_portal_user_type as community_portal_user_type
            ,oc.contact_license_type
            ,oc.status
            from org_users 
            join org_contacts oc on oc.user_id = org_users.id
            $where ";


        $sth = $dbh->prepare($qry);
//        $sth->execute(array(":contact_id" => $contact_id));
        $data[":id"] = $userId;
        if($agency_id){
            $data[":agency_id"] = $agency_id;
        }
        
        $sth->execute($data);
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function get_agency_logo($agency_id)
    {
        $qry = "select filename from cp_agency_logo where agency_id = :agency_id";
        $sth = $this->dbh->prepare($qry);
        $sth->execute(array(":agency_id" => $agency_id));
        $filename = $sth->fetchColumn();
        $upload_dir = "../uploads/agency_logos/" . $agency_id . "/" . $filename;
        return $upload_dir;
    }

    public function agency_logo_exists($agency_id)
    {
        $qry = "select count(*) from cp_agency_logo where agency_id = '$agency_id'";
        $sth = $this->dbh->query($qry);
        return $sth->fetchColumn();
    }

    public function SetParentAgencySession()
    {
        $qry = "select cp_parent_child as agency_id, cp_parent_agency as parent_agency from org_information where cp_parent_child = :agencyId";
        $sth = $this->dbh->prepare($qry);
        $sth->execute([':agencyId' => $_SESSION['agency_id']]);

        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
            if ($f->parent_agency == '-1') {
                $_SESSION['parent_agency'] = $f->agency_id;
            } else {
                $_SESSION['parent_agency'] = $f->parent_agency;
            }
        }
    }

    public function GetAgencyLevel1($agencyId)
    {
        $qry = "select level_1 from org_information where cp_parent_child = :agencyId";
        $sth = $this->dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId]);
        return $sth->fetchColumn();
    }

    public function GetAgencyStatus($agencyId)
    {
        $qry = "select status from org_information where cp_parent_child = :agencyId";
        $sth = $this->dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId]);
        return $sth->fetchColumn();
    }

    public function GetAgencyLevel($agencyId)
    {
        $qry = "select agency_level from org_information where cp_parent_child = :agencyId";
        $sth = $this->dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId]);
        return $sth->fetchColumn();
    }

    public function GetAgencyLocations($agencyId)
    {
        $qry = "select * from cp_directory_agency_locations where agency_id = :agencyId and `status` = 'ACTIVE'";
        $sth = $this->dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId]);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function GetAgencyInActiveLocations($agencyId)
    {
        $qry = "select * from cp_directory_agency_locations where agency_id = :agencyId and `status` = 'IN-ACTIVE'";
        $sth = $this->dbh->prepare($qry);
        $sth->execute([':agencyId' => $agencyId]);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function GetLocationData($locationId)
    {
        $qry = "select * from cp_directory_agency_locations where id = :locationId";
        $sth = $this->dbh->prepare($qry);
        $sth->execute([':locationId' => $locationId]);
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function ListAgencyLocations()
    {
        $qry = "select * from cp_directory_agency_locations where agency_id = :agencyId";
        $sth = $this->dbh->prepare($qry);
        $sth->execute([':agencyId' => $this->agency_id]);

        $htm = '';
        $htm .= '<b>Other Locations:</b>';

        if ($sth->rowCount() > 0) {

//            $htm .= '<br><br>';

            while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
                $htm .= '<address>';
                $htm .= $f->address . '<br>';
                $htm .= $f->city . ', ' . $f->state . ' ' . $f->zip_code . '<br>';
                $htm .= '</address>';
            }
        }

        return $htm;

    }

    public function CheckIfServicesExist()
    {
        $sth = $this->dbh->prepare("select id from cp_agency_services where agency_id = '{$this->agency_id}'");
        $sth->execute();

        if ($sth->rowCount() > 0)
            return true;
        else
            return false;
    }

    public function SetContactUs()
    {   
        $parent_agency = isset($_SESSION['parent_agency']) ? $_SESSION['parent_agency'] : 0;
        $qry = 'select * from cp_contact_us where parent_agency_id = :id';
        $sth = $this->dbh->prepare($qry);
        $sth->execute([':id' => $parent_agency]);
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    public function GetAgencyIdByLocationId($locationId)
    {
        $qry = "select agency_id from cp_directory_agency_locations where id = :id";
        $sth = $this->dbh->prepare($qry);
        $sth->execute([':id' => $locationId]);
        return $sth->fetchColumn();
    }

}
