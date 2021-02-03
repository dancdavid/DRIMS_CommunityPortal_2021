<?php

/**
 * Description of db
 * handles most of all database functions
 *
 * @author Dan David
 */
class db extends core
{

    public $last_inserted_id;
    public $salt;

    public function initDB()
    {
        try {
            $dbh = new PDO(C_DSN, C_USER, C_PWD);
            return $dbh;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function insertUpdateSQL($data, $table)
    {

        //BUILDING INSERT & UPDATE QUERY
        $update_data = "";
        foreach ($data as $field => $value) {
            $fields[] = $field;

            $values[] = ":" . $field;
            $update_data .= "$field = :$field,";
        }

        $field_list = join(', ', $fields);
        $value_list = join(', ', $values);


        $qry = "INSERT INTO " . $table . " (" . $field_list . ") VALUES (" . $value_list . ")
                ON DUPLICATE KEY UPDATE " . rtrim($update_data, ",");

        try {

            $dbh = $this->initDB();
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sth = $dbh->prepare($qry);

            //BIND THE VALUES FOR QUERY
//            foreach ($data as $fn => $val) {
//                $sth->bindParam(':' . $fn, $val);
//            }

            foreach ($data as $fn => $val) {
                $bind_data[":$fn"] = $val;
            }

            $sth->execute($bind_data);

            $this->last_inserted_id = $dbh->lastInsertId();

            return true;
        } catch (PDOException $e) {
            
            if (DEBUG) {
                echo $e->getMessage();
            } else {
                return false;
            }
        }
    }

    function dump_query($sql)
    {

        // Useful during development for debugging  purposes.  Simple dumps a
        // query to the screen in a table.

        $dbh = $this->initDB();
        $sth = $dbh->prepare($sql);
        $sth->execute();

        echo "<div style=\"border: 1px solid blue; font-family: sans-serif; margin: 8px;\">\n";
        echo "<table cellpadding=\"3\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";

        $i = 0;
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            if ($i == 0) {
                echo "<tr><td colspan=\"" . sizeof($row) . "\"><span style=\"font-face: monospace; font-size: 9pt;\">$sql</span></td></tr>\n";
                echo "<tr>\n";
                foreach ($row as $col => $value) {
                    echo "<td bgcolor=\"#E6E5FF\"><span style=\"font-face: sans-serif; font-size: 9pt; font-weight: bold;\">$col</span></td>\n";
                }
                echo "</tr>\n";
            }
            $i++;
            if ($i % 2 == 0)
                $bg = '#E3E3E3';
            else
                $bg = '#F3F3F3';
            echo "<tr>\n";
            foreach ($row as $value) {
                echo "<td bgcolor=\"$bg\"><span style=\"font-face: sans-serif; font-size: 9pt;\">$value</span></td>\n";
            }
            echo "</tr>\n";
        }
        echo "</table></div>\n";
    }

    public function buildDropMenu($field_name, $data = null)
    {

        //Builds drop menu from database table DROP_DOWN_MENU
        //Field Name and List
        //LIST OF ITEMS MUST BE SEMI-COLON ; SEPERATED in list field

        $html = "";

        $dbh = $this->initDB();
        $qry = "select list from drop_down_menu where field_name = '$field_name'";
        $sth = $dbh->query($qry);
        $result = $sth->fetchColumn();

        $data_fields = explode(";", $result);

        if (isset($data)) {
            $html .= "<option selected value='$data'>$data</option>";
            $html .= "<optgroup label='------'></optgroup>";
        } else {
            $html .= "<option value='' selected></option>";
        }

        foreach ($data_fields as $val) {
            $html .= "<option value='" . strtoupper($val) . "'>" . strtoupper($val) . "</option>";
        }

        return $html;
    }

    public function buildDropYN($data = null)
    {
        $html = '';

        if (isset($data)) {
            $html .= "<option selected value='$data'>$data</option>";
            $html .= "<optgroup label='------'></optgroup>";
            $html .= "<option value=''></option>";
        } else {
            $html .= "<option value='' selected></option>";
        }

        $html .= "<option value='YES'>YES</option>";
        $html .= "<option value='NO'>NO</option>";

        return $html;
    }

    public function validateLogin($email, $pwd, $table, $landingPage = '', $saltedPassword = '', $loginType = '')
    {

        try {

        
            if($saltedPassword){
                $password = $saltedPassword;
            }else{
                $password = $this->getSaltedPassword($email, $pwd, $table);
            }

            $dbh = $this->initDB();
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //TODO SET QRY TO VALIDATE LOGIN
            $qry = "SELECT * FROM {$table} WHERE email = :email and password = :password";
            $sth = $dbh->prepare($qry);
            $sth->bindParam(":email", $email);
            $sth->bindParam(":password", $password);
            $sth->execute();
            $r = $sth->fetch(PDO::FETCH_OBJ);
            if ($sth->rowCount() <= 0) {
                $e = urlencode("Invalid Username or Password!");
                $link = "$landingPage/?e=$e";
            } else {
                    # get user and its related agency data
                    $qry = "SELECT u.id,u.first_name,u.last_name,u.email,u.default_org_id,u.default_portal_type, u.homescreen_org_id , u.default_agency_id , c.cp_org_id, c.cp_user_level, c.cp_access_level, c.cp_instance_id, c.cp_community_portal_user_type,
                    c.cp_contact_type, c.cp_level_1, c.contact_license_type, c.cp_notification , c.status, c.cp_org_id , c.cp_access as community_portal, c.cms_access as case_management
                    FROM {$table} as u 
                    LEFT JOIN org_contacts as c ON u.id =  c.user_id  
                    WHERE u.id = {$r->id} ";
                    $sth = $dbh->prepare($qry);
                    $sth->execute();
                    $rows = $sth->rowCount();
                    $f = new stdClass;
                    $hasCommunityPortalAccess = 0;
                    while($t = $sth->fetch(PDO::FETCH_OBJ)){
                        # default_agency_id = cp_org_id and cp_org_id IS NOT NULL
                        if($t->default_agency_id == $t->cp_org_id && $t->cp_org_id){
                            # get the agency data which is set as default
                            $f = $t;
                        }
                        if($t->community_portal){
                            # check if community_portal access exist
                            $hasCommunityPortalAccess = $t->community_portal;
                        }
                    }
                if(!$hasCommunityPortalAccess){
                    # If the user do not have access to CP 
                    $e = 'accessdenied';
                    $link = "$landingPage/?e=$e";
                }
                else if($rows && empty((array)$f)){
                        # if data exist in org_contacts table and default agency is not set then redirect to editprofile section 
                        # to set a default agency first
                        $link = 'directory/editmyprofile?err=Please set a default agency first to continue';
                        $_SESSION['v'] = CRYPT_KEY . session_id() . C_24_KEY;
                        $_SESSION['userID'] = $r->id;
                        $_SESSION['orgID'] = $r->default_org_id;
                        $_SESSION['user_id'] = $r->id;
                        $_SESSION['agency_id'] = $r->default_agency_id;
                        $_SESSION['user_name'] = $r->first_name . ' ' . $r->last_name;
                        $_SESSION['user_email'] = $r->email;
                        $_SESSION['landing_page'] = $landingPage;
                        $_SESSION['userLevel'] = '';
                        $_SESSION['cp_access'] = '';
                        $_SESSION['cms_access'] = '';
                        $_SESSION['user_type'] = '';
                        $_SESSION['level_1'] = '';
                        $_SESSION['cp_user_level'] = 0;
                        //$_SESSION['parent_agency'] = '';
                        
                }
                else if ($f->status !== 'ACTIVE')
                {
                    $e = 'contactsupport';
                    $link = "$landingPage/?e=$e";
                }
                else {
                 
                    $isCommunityPortal = $f->community_portal;
                    $agencyId = $f->default_agency_id;
                    $agency = new agency();
                  
                    if(!$isCommunityPortal){
                        # If the user do not have access to CP 
                        $e = 'accessdenied';
                        $link = "$landingPage/?e=$e";
                    }
                    else if(!$this->checkInstanceURL($f->id) && !$loginType){
                        # If the login url is incorrect and not autologin type
                        $e = 'incorrecturl';
                        $link = "$landingPage/?e=$e";
                    }
                    else{
                        # Save data for autologin
                        if($f->case_management){
                            # If CMS(case management portal) access
                            $_SESSION['_oe'] = $this->encode($email);
                            $_SESSION['_op'] = $this->encode($pwd);
                        }

                        $_SESSION['v'] = CRYPT_KEY . session_id() . C_24_KEY;
                        $_SESSION['userID'] = $f->id;
                        $_SESSION['userLevel'] = $f->cp_access_level;
                        $_SESSION['orgID'] = $f->default_org_id;
                        $_SESSION['cp_access'] = $f->community_portal;
                        $_SESSION['cms_access'] = $f->case_management;
                        $_SESSION['user_id'] = $f->id;
                        $_SESSION['agency_id'] = $f->default_agency_id;
                        $_SESSION['user_type'] = $f->cp_community_portal_user_type;
                        $_SESSION['user_name'] = $f->first_name . ' ' . $f->last_name;
                        $_SESSION['user_email'] = $f->email;
                        $_SESSION['level_1'] = $f->cp_level_1;
                        $_SESSION['cp_user_level'] = $f->cp_user_level;
                        $_SESSION['landing_page'] = $landingPage;
                        //$_SESSION['parent_agency'] = '';
                        if(!$r->default_agency_id){
                            // if default agency id is not defined then set it first , redirect to editprofile page
                            $link = 'directory/editmyprofile';
                        }else{
                            $link = 'directory';
                        }

                        $default_portal_type = $f->default_portal_type;
                        if($default_portal_type && $default_portal_type == 'CMS' && !$loginType){
                            // if initial login then set homescreen
                            global $_core;
                            $encoded_org_id = $_core->encode($f->default_org_id);
                            $encoded_user_id = $_core->encode($f->id);
                            $root_cms_url = ROOT_CMS_URL."_lib/oaction.php?action=autologin&uid=$encoded_user_id&oid=$encoded_org_id&portal_type=CMS";
                            header("Location: " . $root_cms_url);
                            exit;
                        }
                    }
                    
                }
//                foreach ($data as $f) {
//
//                    if ($f->user_status !== 'ACTIVE') {
//                        $e = urlencode("Your account is not active Contact Administrator.");
//                        $link = "signin&e=$e";
//                    } else {
//                        $_SESSION['v'] = CRYPT_KEY . session_id() . C_24_KEY;
//
//                        //TODO SET SESSION VARS AND SET REDIR LINK using $link
//                        $_SESSION['user_id'] = $f->contact_id;
//                        $_SESSION['agency_id'] = $f->agency_id;
//                        $_SESSION['user_type'] = $f->user_type;
//                        $_SESSION['user_name'] = $f->contact_name;
//                        $_SESSION['user_email'] = $f->contact_email;
//
//                        if ($pwd === 'recovery' || $pwd === 'r31coV3rY') {
//                            $link = 'directory/updatepwd';
//                        } else {
//                            $link = 'directory';
//                        }
//                    }
//                }
            }

            if($loginType != 'autologin'){
                // if login type is not autologin then only redirect
                $this->redir($link);
            }
        } catch (PDOException $e) {
            if (DEBUG) {
                die($e->getMessage());
            } else {
                die("OOPS: Could not process request. Please contact administrator");
            }


        }
    }

    public function switchOrganization($org_id, $user_id, $portal_type){
        $qry = '';

        if($portal_type == "CMS"){
            // goto CMS
            $_SESSION['orgID'] = $org_id;
            $qry = 'CMS LOGIN SUCCESS';
        }

        if($portal_type == "CP"){
            // goto CP
            $dbh = $this->initDB();
            $qry = "SELECT c.id , c.cp_user_level, c.cp_access_level , c.cp_community_portal_user_type, c.cms_access , c.cp_access
                    FROM org_users as u 
                    LEFT JOIN org_contacts as c ON u.id =  c.user_id  
                    WHERE u.id = {$user_id} and cp_org_id = {$org_id} and cp_org_id IS NOT NULL";
            $sth = $dbh->query($qry);
            $f = $sth->fetch(PDO::FETCH_OBJ);
            // update session
            //$_SESSION['userLevel'] = $f->cp_access_level;
            $_SESSION['cp_access'] = $f->cp_access;
            $_SESSION['cms_access'] = $f->cms_access;
            $_SESSION['user_type'] = $f->cp_community_portal_user_type;
            $_SESSION['cp_user_level'] = $f->cp_user_level;
            $_SESSION['agency_id'] = $org_id;
            $qry = 'CP LOGIN SUCCESS';
        }

        return $qry;
    }

    // Check Instance URL
    private function checkInstanceURL($userId){
        $dbh = $this->initDB();
        $custom_login_url = $this->getFirstURLSegment();
        $isSigninKeyword = (strpos($custom_login_url, 'signin') !== false);
        if(!$custom_login_url || $isSigninKeyword){
            // if no custom URL then return true , means only base URL
            return true;
        }

        $sth = $dbh->prepare(" select count(*) as cnt from org_contacts 
        join org_information on org_information.cp_parent_child = org_contacts.cp_org_id 
        join cp_org_instance on cp_org_instance.cp_org_id = org_information.cp_parent_child 
        where org_contacts.user_id = :user_id and cp_org_instance.custom_url = :custom_login_url  ");
        $sth->execute(array(":user_id" => $userId, ":custom_login_url" => $custom_login_url));
        $count = $sth->fetchColumn();
        return $count;
    }

    public function getAgencyAdminUser($agencyId){
        $qry = "SELECT cpdc.user_id FROM org_users as u LEFT JOIN org_contacts as cpdc ON cpdc.user_id = u.id WHERE cpdc.cp_community_portal_user_type='ADMIN' AND cpdc.cp_org_id = $agencyId LIMIT 1";
        $dbh = $this->initDB();
        $sth = $dbh->prepare($qry);
        $sth->execute();
        
        return $sth->fetchColumn();
    }

    private function getFirstURLSegment(){
        $uri_path = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
        $uri_segments = array_values(array_filter(explode('/', $uri_path)));
        return (count($uri_segments) ? $uri_segments[0] : '');
    }

    private function getSaltedPassword($email, $password, $table)
    {

        $dbh = $this->initDB();
        //TODO SET QRY TO VALIDATE LOGIN
        $sth = $dbh->prepare("SELECT salt FROM {$table} WHERE email = :email");
        $sth->execute(array(":email" => $email));

        $salt = $sth->fetchColumn();

        return crypt($password, $salt);
    }

    public function executeFile($file)
    {

        try {
            if (!file_exists($file)) {
                throw new Exception('File: ' . $file . ' does not exist');
            }

            $str = file_get_contents($file);
            if (!$str) {
                throw new Exception('Unable to read the contents of ' . $file);
            }

            $sql = explode(';', $str);
            $_dbh = $this->initDB();
            $_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            foreach ($sql as $query) {
                if (!empty($query)) {
                    $_dbh->exec($query);

                    //successful panels
                    $this->html .= '<div class="panel panel-success">';
                    $this->html .= '<div class="panel-heading">Successful</div>';
                    $this->html .= '<div class="panel-body">';
                    $this->html .= $query;
                    $this->html .= '</div>';
                    $this->html .= '</div>';
                }
            }

            echo $this->html;
        } catch (Exception $ex) {
            $this->html .= '<div class="panel panel-danger">';
            $this->html .= '<div class="panel-heading">ERROR!</div>';
            $this->html .= '<div class="panel-body">';
            $this->html .= $ex->getMessage();
            $this->html .= '</div>';
            $this->html .= '</div>';

            echo $this->html;
        }
    }

    public function checkEmailExists($email, $table)
    {

        //UPDATED FOR CP
        $dbh = $this->initDB();
//        $qry = "select count(contact_email) as CNT from $table where contact_email = '$email'";
        $qry = "select count(email) as CNT from $table where email = '$email'";

        $r = $dbh->query($qry)->fetchColumn();

        if ($r >= 1)
            return true;
        else
            return false;
    }

    // get agency fields from org information table
    public function getAgencyFields(){

        $ageny_table_fields = " id , name as agency_name, org_phone as agency_telephone, org_fax as agency_fax, address as agency_address,
        city as agency_city, state as agency_state, zipcode as agency_zipcode, description as description, type as user_type,
        url as agency_url, status , cp_parent_child as agency_id, cp_parent_agency as parent_agency, cp_partner_type as partner_type, level_1 ";
        
        return $ageny_table_fields;
    }

    public function checkExistingUser($email, $agency_id,  $table)
    {

        //UPDATED FOR CP
        $dbh = $this->initDB();
        if($agency_id){
            $qry = "select count(email) as CNT from org_users 
            join org_contacts on org_contacts.user_id = org_users.id 
            where email = '$email' and org_contacts.cp_org_id = $agency_id ";
        }else{
            $qry = "select count(email) as CNT from org_users 
            where email = '$email'  ";
        }
        

        $r = $dbh->query($qry)->fetchColumn();

        if ($r >= 1)
            return true;
        else
            return false;
    }

    public function buildTimezoneDropdown(){
        
        $timezoneHTML = '
            <option disabled selected style="display:none;">Time Zone...</option>

            <optgroup label="US (Common)">	
                <option value="America/Puerto_Rico">Puerto Rico (Atlantic)</option>
                <option value="America/New_York">New York (Eastern)</option>
                <option value="America/Chicago">Chicago (Central)</option>
                <option value="America/Denver">Denver (Mountain)</option>
                <option value="America/Phoenix">Phoenix (MST)</option>
                <option value="America/Los_Angeles">Los Angeles (Pacific)</option>
                <option value="America/Anchorage">Anchorage (Alaska)</option>
                <option value="Pacific/Honolulu">Honolulu (Hawaii)</option>
            </optgroup>

            <optgroup label="America">
                <option value="America/Adak">Adak</option>
                <option value="America/Anchorage">Anchorage</option>
                <option value="America/Anguilla">Anguilla</option>
                <option value="America/Antigua">Antigua</option>
                <option value="America/Araguaina">Araguaina</option>
                <option value="America/Argentina/Buenos_Aires">Argentina - Buenos Aires</option>
                <option value="America/Argentina/Catamarca">Argentina - Catamarca</option>
                <option value="America/Argentina/ComodRivadavia">Argentina - ComodRivadavia</option>
                <option value="America/Argentina/Cordoba">Argentina - Cordoba</option>
                <option value="America/Argentina/Jujuy">Argentina - Jujuy</option>
                <option value="America/Argentina/La_Rioja">Argentina - La Rioja</option>
                <option value="America/Argentina/Mendoza">Argentina - Mendoza</option>
                <option value="America/Argentina/Rio_Gallegos">Argentina - Rio Gallegos</option>
                <option value="America/Argentina/Salta">Argentina - Salta</option>
                <option value="America/Argentina/San_Juan">Argentina - San Juan</option>
                <option value="America/Argentina/San_Luis">Argentina - San Luis</option>
                <option value="America/Argentina/Tucuman">Argentina - Tucuman</option>
                <option value="America/Argentina/Ushuaia">Argentina - Ushuaia</option>
                <option value="America/Aruba">Aruba</option>
                <option value="America/Asuncion">Asuncion</option>
                <option value="America/Atikokan">Atikokan</option>
                <option value="America/Atka">Atka</option>
                <option value="America/Bahia">Bahia</option>
                <option value="America/Barbados">Barbados</option>
                <option value="America/Belem">Belem</option>
                <option value="America/Belize">Belize</option>
                <option value="America/Blanc-Sablon">Blanc-Sablon</option>
                <option value="America/Boa_Vista">Boa Vista</option>
                <option value="America/Bogota">Bogota</option>
                <option value="America/Boise">Boise</option>
                <option value="America/Buenos_Aires">Buenos Aires</option>
                <option value="America/Cambridge_Bay">Cambridge Bay</option>
                <option value="America/Campo_Grande">Campo Grande</option>
                <option value="America/Cancun">Cancun</option>
                <option value="America/Caracas">Caracas</option>
                <option value="America/Catamarca">Catamarca</option>
                <option value="America/Cayenne">Cayenne</option>
                <option value="America/Cayman">Cayman</option>
                <option value="America/Chicago">Chicago</option>
                <option value="America/Chihuahua">Chihuahua</option>
                <option value="America/Coral_Harbour">Coral Harbour</option>
                <option value="America/Cordoba">Cordoba</option>
                <option value="America/Costa_Rica">Costa Rica</option>
                <option value="America/Cuiaba">Cuiaba</option>
                <option value="America/Curacao">Curacao</option>
                <option value="America/Danmarkshavn">Danmarkshavn</option>
                <option value="America/Dawson">Dawson</option>
                <option value="America/Dawson_Creek">Dawson Creek</option>
                <option value="America/Denver">Denver</option>
                <option value="America/Detroit">Detroit</option>
                <option value="America/Dominica">Dominica</option>
                <option value="America/Edmonton">Edmonton</option>
                <option value="America/Eirunepe">Eirunepe</option>
                <option value="America/El_Salvador">El Salvador</option>
                <option value="America/Ensenada">Ensenada</option>
                <option value="America/Fortaleza">Fortaleza</option>
                <option value="America/Fort_Wayne">Fort Wayne</option>
                <option value="America/Glace_Bay">Glace Bay</option>
                <option value="America/Godthab">Godthab</option>
                <option value="America/Goose_Bay">Goose Bay</option>
                <option value="America/Grand_Turk">Grand Turk</option>
                <option value="America/Grenada">Grenada</option>
                <option value="America/Guadeloupe">Guadeloupe</option>
                <option value="America/Guatemala">Guatemala</option>
                <option value="America/Guayaquil">Guayaquil</option>
                <option value="America/Guyana">Guyana</option>
                <option value="America/Halifax">Halifax</option>
                <option value="America/Havana">Havana</option>
                <option value="America/Hermosillo">Hermosillo</option>
                <option value="America/Indiana/Indianapolis">Indiana - Indianapolis</option>
                <option value="America/Indiana/Knox">Indiana - Knox</option>
                <option value="America/Indiana/Marengo">Indiana - Marengo</option>
                <option value="America/Indiana/Petersburg">Indiana - Petersburg</option>
                <option value="America/Indiana/Tell_City">Indiana - Tell City</option>
                <option value="America/Indiana/Vevay">Indiana - Vevay</option>
                <option value="America/Indiana/Vincennes">Indiana - Vincennes</option>
                <option value="America/Indiana/Winamac">Indiana - Winamac</option>
                <option value="America/Indianapolis">Indianapolis</option>
                <option value="America/Inuvik">Inuvik</option>
                <option value="America/Iqaluit">Iqaluit</option>
                <option value="America/Jamaica">Jamaica</option>
                <option value="America/Jujuy">Jujuy</option>
                <option value="America/Juneau">Juneau</option>
                <option value="America/Kentucky/Louisville">Kentucky - Louisville</option>
                <option value="America/Kentucky/Monticello">Kentucky - Monticello</option>
                <option value="America/Knox_IN">Knox IN</option>
                <option value="America/La_Paz">La Paz</option>
                <option value="America/Lima">Lima</option>
                <option value="America/Los_Angeles">Los Angeles</option>
                <option value="America/Louisville">Louisville</option>
                <option value="America/Maceio">Maceio</option>
                <option value="America/Managua">Managua</option>
                <option value="America/Manaus">Manaus</option>
                <option value="America/Marigot">Marigot</option>
                <option value="America/Martinique">Martinique</option>
                <option value="America/Matamoros">Matamoros</option>
                <option value="America/Mazatlan">Mazatlan</option>
                <option value="America/Mendoza">Mendoza</option>
                <option value="America/Menominee">Menominee</option>
                <option value="America/Merida">Merida</option>
                <option value="America/Mexico_City">Mexico City</option>
                <option value="America/Miquelon">Miquelon</option>
                <option value="America/Moncton">Moncton</option>
                <option value="America/Monterrey">Monterrey</option>
                <option value="America/Montevideo">Montevideo</option>
                <option value="America/Montreal">Montreal</option>
                <option value="America/Montserrat">Montserrat</option>
                <option value="America/Nassau">Nassau</option>
                <option value="America/New_York">New York</option>
                <option value="America/Nipigon">Nipigon</option>
                <option value="America/Nome">Nome</option>
                <option value="America/Noronha">Noronha</option>
                <option value="America/North_Dakota/Center">North Dakota - Center</option>
                <option value="America/North_Dakota/New_Salem">North Dakota - New Salem</option>
                <option value="America/Ojinaga">Ojinaga</option>
                <option value="America/Panama">Panama</option>
                <option value="America/Pangnirtung">Pangnirtung</option>
                <option value="America/Paramaribo">Paramaribo</option>
                <option value="America/Phoenix">Phoenix</option>
                <option value="America/Port-au-Prince">Port-au-Prince</option>
                <option value="America/Porto_Acre">Porto Acre</option>
                <option value="America/Port_of_Spain">Port of Spain</option>
                <option value="America/Porto_Velho">Porto Velho</option>
                <option value="America/Puerto_Rico">Puerto Rico</option>
                <option value="America/Rainy_River">Rainy River</option>
                <option value="America/Rankin_Inlet">Rankin Inlet</option>
                <option value="America/Recife">Recife</option>
                <option value="America/Regina">Regina</option>
                <option value="America/Resolute">Resolute</option>
                <option value="America/Rio_Branco">Rio Branco</option>
                <option value="America/Rosario">Rosario</option>
                <option value="America/Santa_Isabel">Santa Isabel</option>
                <option value="America/Santarem">Santarem</option>
                <option value="America/Santiago">Santiago</option>
                <option value="America/Santo_Domingo">Santo Domingo</option>
                <option value="America/Sao_Paulo">Sao Paulo</option>
                <option value="America/Scoresbysund">Scoresbysund</option>
                <option value="America/Shiprock">Shiprock</option>
                <option value="America/St_Barthelemy">St Barthelemy</option>
                <option value="America/St_Johns">St Johns</option>
                <option value="America/St_Kitts">St Kitts</option>
                <option value="America/St_Lucia">St Lucia</option>
                <option value="America/St_Thomas">St Thomas</option>
                <option value="America/St_Vincent">St Vincent</option>
                <option value="America/Swift_Current">Swift Current</option>
                <option value="America/Tegucigalpa">Tegucigalpa</option>
                <option value="America/Thule">Thule</option>
                <option value="America/Thunder_Bay">Thunder Bay</option>
                <option value="America/Tijuana">Tijuana</option>
                <option value="America/Toronto">Toronto</option>
                <option value="America/Tortola">Tortola</option>
                <option value="America/Vancouver">Vancouver</option>
                <option value="America/Virgin">Virgin</option>
                <option value="America/Whitehorse">Whitehorse</option>
                <option value="America/Winnipeg">Winnipeg</option>
                <option value="America/Yakutat">Yakutat</option>
                <option value="America/Yellowknife">Yellowknife</option>
            </optgroup>

            <optgroup label="Europe">
                <option value="Europe/Amsterdam">Amsterdam</option>
                <option value="Europe/Andorra">Andorra</option>
                <option value="Europe/Athens">Athens</option>
                <option value="Europe/Belfast">Belfast</option>
                <option value="Europe/Belgrade">Belgrade</option>
                <option value="Europe/Berlin">Berlin</option>
                <option value="Europe/Bratislava">Bratislava</option>
                <option value="Europe/Brussels">Brussels</option>
                <option value="Europe/Bucharest">Bucharest</option>
                <option value="Europe/Budapest">Budapest</option>
                <option value="Europe/Chisinau">Chisinau</option>
                <option value="Europe/Copenhagen">Copenhagen</option>
                <option value="Europe/Dublin">Dublin</option>
                <option value="Europe/Gibraltar">Gibraltar</option>
                <option value="Europe/Guernsey">Guernsey</option>
                <option value="Europe/Helsinki">Helsinki</option>
                <option value="Europe/Isle_of_Man">Isle of Man</option>
                <option value="Europe/Istanbul">Istanbul</option>
                <option value="Europe/Jersey">Jersey</option>
                <option value="Europe/Kaliningrad">Kaliningrad</option>
                <option value="Europe/Kiev">Kiev</option>
                <option value="Europe/Lisbon">Lisbon</option>
                <option value="Europe/Ljubljana">Ljubljana</option>
                <option value="Europe/London">London</option>
                <option value="Europe/Luxembourg">Luxembourg</option>
                <option value="Europe/Madrid">Madrid</option>
                <option value="Europe/Malta">Malta</option>
                <option value="Europe/Mariehamn">Mariehamn</option>
                <option value="Europe/Minsk">Minsk</option>
                <option value="Europe/Monaco">Monaco</option>
                <option value="Europe/Moscow">Moscow</option>
                <option value="Europe/Nicosia">Nicosia</option>
                <option value="Europe/Oslo">Oslo</option>
                <option value="Europe/Paris">Paris</option>
                <option value="Europe/Podgorica">Podgorica</option>
                <option value="Europe/Prague">Prague</option>
                <option value="Europe/Riga">Riga</option>
                <option value="Europe/Rome">Rome</option>
                <option value="Europe/Samara">Samara</option>
                <option value="Europe/San_Marino">San Marino</option>
                <option value="Europe/Sarajevo">Sarajevo</option>
                <option value="Europe/Simferopol">Simferopol</option>
                <option value="Europe/Skopje">Skopje</option>
                <option value="Europe/Sofia">Sofia</option>
                <option value="Europe/Stockholm">Stockholm</option>
                <option value="Europe/Tallinn">Tallinn</option>
                <option value="Europe/Tirane">Tirane</option>
                <option value="Europe/Tiraspol">Tiraspol</option>
                <option value="Europe/Uzhgorod">Uzhgorod</option>
                <option value="Europe/Vaduz">Vaduz</option>
                <option value="Europe/Vatican">Vatican</option>
                <option value="Europe/Vienna">Vienna</option>
                <option value="Europe/Vilnius">Vilnius</option>
                <option value="Europe/Volgograd">Volgograd</option>
                <option value="Europe/Warsaw">Warsaw</option>
                <option value="Europe/Zagreb">Zagreb</option>
                <option value="Europe/Zaporozhye">Zaporozhye</option>
                <option value="Europe/Zurich">Zurich</option>
            </optgroup>
            
            <optgroup label="Asia">
                <option value="Asia/Aden">Aden</option>
                <option value="Asia/Almaty">Almaty</option>
                <option value="Asia/Amman">Amman</option>
                <option value="Asia/Anadyr">Anadyr</option>
                <option value="Asia/Aqtau">Aqtau</option>
                <option value="Asia/Aqtobe">Aqtobe</option>
                <option value="Asia/Ashgabat">Ashgabat</option>
                <option value="Asia/Ashkhabad">Ashkhabad</option>
                <option value="Asia/Baghdad">Baghdad</option>
                <option value="Asia/Bahrain">Bahrain</option>
                <option value="Asia/Baku">Baku</option>
                <option value="Asia/Bangkok">Bangkok</option>
                <option value="Asia/Beirut">Beirut</option>
                <option value="Asia/Bishkek">Bishkek</option>
                <option value="Asia/Brunei">Brunei</option>
                <option value="Asia/Calcutta">Calcutta</option>
                <option value="Asia/Choibalsan">Choibalsan</option>
                <option value="Asia/Chongqing">Chongqing</option>
                <option value="Asia/Chungking">Chungking</option>
                <option value="Asia/Colombo">Colombo</option>
                <option value="Asia/Dacca">Dacca</option>
                <option value="Asia/Damascus">Damascus</option>
                <option value="Asia/Dhaka">Dhaka</option>
                <option value="Asia/Dili">Dili</option>
                <option value="Asia/Dubai">Dubai</option>
                <option value="Asia/Dushanbe">Dushanbe</option>
                <option value="Asia/Gaza">Gaza</option>
                <option value="Asia/Harbin">Harbin</option>
                <option value="Asia/Ho_Chi_Minh">Ho Chi Minh</option>
                <option value="Asia/Hong_Kong">Hong Kong</option>
                <option value="Asia/Hovd">Hovd</option>
                <option value="Asia/Irkutsk">Irkutsk</option>
                <option value="Asia/Istanbul">Istanbul</option>
                <option value="Asia/Jakarta">Jakarta</option>
                <option value="Asia/Jayapura">Jayapura</option>
                <option value="Asia/Jerusalem">Jerusalem</option>
                <option value="Asia/Kabul">Kabul</option>
                <option value="Asia/Kamchatka">Kamchatka</option>
                <option value="Asia/Karachi">Karachi</option>
                <option value="Asia/Kashgar">Kashgar</option>
                <option value="Asia/Kathmandu">Kathmandu</option>
                <option value="Asia/Katmandu">Katmandu</option>
                <option value="Asia/Kolkata">Kolkata</option>
                <option value="Asia/Krasnoyarsk">Krasnoyarsk</option>
                <option value="Asia/Kuala_Lumpur">Kuala Lumpur</option>
                <option value="Asia/Kuching">Kuching</option>
                <option value="Asia/Kuwait">Kuwait</option>
                <option value="Asia/Macao">Macao</option>
                <option value="Asia/Macau">Macau</option>
                <option value="Asia/Magadan">Magadan</option>
                <option value="Asia/Makassar">Makassar</option>
                <option value="Asia/Manila">Manila</option>
                <option value="Asia/Muscat">Muscat</option>
                <option value="Asia/Nicosia">Nicosia</option>
                <option value="Asia/Novokuznetsk">Novokuznetsk</option>
                <option value="Asia/Novosibirsk">Novosibirsk</option>
                <option value="Asia/Omsk">Omsk</option>
                <option value="Asia/Oral">Oral</option>
                <option value="Asia/Phnom_Penh">Phnom Penh</option>
                <option value="Asia/Pontianak">Pontianak</option>
                <option value="Asia/Pyongyang">Pyongyang</option>
                <option value="Asia/Qatar">Qatar</option>
                <option value="Asia/Qyzylorda">Qyzylorda</option>
                <option value="Asia/Rangoon">Rangoon</option>
                <option value="Asia/Riyadh">Riyadh</option>
                <option value="Asia/Saigon">Saigon</option>
                <option value="Asia/Sakhalin">Sakhalin</option>
                <option value="Asia/Samarkand">Samarkand</option>
                <option value="Asia/Seoul">Seoul</option>
                <option value="Asia/Shanghai">Shanghai</option>
                <option value="Asia/Singapore">Singapore</option>
                <option value="Asia/Taipei">Taipei</option>
                <option value="Asia/Tashkent">Tashkent</option>
                <option value="Asia/Tbilisi">Tbilisi</option>
                <option value="Asia/Tehran">Tehran</option>
                <option value="Asia/Tel_Aviv">Tel Aviv</option>
                <option value="Asia/Thimbu">Thimbu</option>
                <option value="Asia/Thimphu">Thimphu</option>
                <option value="Asia/Tokyo">Tokyo</option>
                <option value="Asia/Ujung_Pandang">Ujung Pandang</option>
                <option value="Asia/Ulaanbaatar">Ulaanbaatar</option>
                <option value="Asia/Ulan_Bator">Ulan Bator</option>
                <option value="Asia/Urumqi">Urumqi</option>
                <option value="Asia/Vientiane">Vientiane</option>
                <option value="Asia/Vladivostok">Vladivostok</option>
                <option value="Asia/Yakutsk">Yakutsk</option>
                <option value="Asia/Yekaterinburg">Yekaterinburg</option>
                <option value="Asia/Yerevan">Yerevan</option>
            </optgroup>

            <optgroup label="Africa">
                <option value="Africa/Abidjan">Abidjan</option>
                <option value="Africa/Accra">Accra</option>
                <option value="Africa/Addis_Ababa">Addis Ababa</option>
                <option value="Africa/Algiers">Algiers</option>
                <option value="Africa/Asmara">Asmara</option>
                <option value="Africa/Asmera">Asmera</option>
                <option value="Africa/Bamako">Bamako</option>
                <option value="Africa/Bangui">Bangui</option>
                <option value="Africa/Banjul">Banjul</option>
                <option value="Africa/Bissau">Bissau</option>
                <option value="Africa/Blantyre">Blantyre</option>
                <option value="Africa/Brazzaville">Brazzaville</option>
                <option value="Africa/Bujumbura">Bujumbura</option>
                <option value="Africa/Cairo">Cairo</option>
                <option value="Africa/Casablanca">Casablanca</option>
                <option value="Africa/Ceuta">Ceuta</option>
                <option value="Africa/Conakry">Conakry</option>
                <option value="Africa/Dakar">Dakar</option>
                <option value="Africa/Dar_es_Salaam">Dar es Salaam</option>
                <option value="Africa/Djibouti">Djibouti</option>
                <option value="Africa/Douala">Douala</option>
                <option value="Africa/El_Aaiun">El Aaiun</option>
                <option value="Africa/Freetown">Freetown</option>
                <option value="Africa/Gaborone">Gaborone</option>
                <option value="Africa/Harare">Harare</option>
                <option value="Africa/Johannesburg">Johannesburg</option>
                <option value="Africa/Kampala">Kampala</option>
                <option value="Africa/Khartoum">Khartoum</option>
                <option value="Africa/Kigali">Kigali</option>
                <option value="Africa/Kinshasa">Kinshasa</option>
                <option value="Africa/Lagos">Lagos</option>
                <option value="Africa/Libreville">Libreville</option>
                <option value="Africa/Lome">Lome</option>
                <option value="Africa/Luanda">Luanda</option>
                <option value="Africa/Lubumbashi">Lubumbashi</option>
                <option value="Africa/Lusaka">Lusaka</option>
                <option value="Africa/Malabo">Malabo</option>
                <option value="Africa/Maputo">Maputo</option>
                <option value="Africa/Maseru">Maseru</option>
                <option value="Africa/Mbabane">Mbabane</option>
                <option value="Africa/Mogadishu">Mogadishu</option>
                <option value="Africa/Monrovia">Monrovia</option>
                <option value="Africa/Nairobi">Nairobi</option>
                <option value="Africa/Ndjamena">Ndjamena</option>
                <option value="Africa/Niamey">Niamey</option>
                <option value="Africa/Nouakchott">Nouakchott</option>
                <option value="Africa/Ouagadougou">Ouagadougou</option>
                <option value="Africa/Porto-Novo">Porto-Novo</option>
                <option value="Africa/Sao_Tome">Sao Tome</option>
                <option value="Africa/Timbuktu">Timbuktu</option>
                <option value="Africa/Tripoli">Tripoli</option>
                <option value="Africa/Tunis">Tunis</option>
                <option value="Africa/Windhoek">Windhoek</option>
            </optgroup>
            
            <optgroup label="Australia">
                <option value="Australia/ACT">ACT</option>
                <option value="Australia/Adelaide">Adelaide</option>
                <option value="Australia/Brisbane">Brisbane</option>
                <option value="Australia/Broken_Hill">Broken Hill</option>
                <option value="Australia/Canberra">Canberra</option>
                <option value="Australia/Currie">Currie</option>
                <option value="Australia/Darwin">Darwin</option>
                <option value="Australia/Eucla">Eucla</option>
                <option value="Australia/Hobart">Hobart</option>
                <option value="Australia/LHI">LHI</option>
                <option value="Australia/Lindeman">Lindeman</option>
                <option value="Australia/Lord_Howe">Lord Howe</option>
                <option value="Australia/Melbourne">Melbourne</option>
                <option value="Australia/North">North</option>
                <option value="Australia/NSW">NSW</option>
                <option value="Australia/Perth">Perth</option>
                <option value="Australia/Queensland">Queensland</option>
                <option value="Australia/South">South</option>
                <option value="Australia/Sydney">Sydney</option>
                <option value="Australia/Tasmania">Tasmania</option>
                <option value="Australia/Victoria">Victoria</option>
                <option value="Australia/West">West</option>
                <option value="Australia/Yancowinna">Yancowinna</option>
            </optgroup>

            <optgroup label="Indian">
                <option value="Indian/Antananarivo">Antananarivo</option>
                <option value="Indian/Chagos">Chagos</option>
                <option value="Indian/Christmas">Christmas</option>
                <option value="Indian/Cocos">Cocos</option>
                <option value="Indian/Comoro">Comoro</option>
                <option value="Indian/Kerguelen">Kerguelen</option>
                <option value="Indian/Mahe">Mahe</option>
                <option value="Indian/Maldives">Maldives</option>
                <option value="Indian/Mauritius">Mauritius</option>
                <option value="Indian/Mayotte">Mayotte</option>
                <option value="Indian/Reunion">Reunion</option>
            </optgroup>
            
            <optgroup label="Atlantic">
                <option value="Atlantic/Azores">Azores</option>
                <option value="Atlantic/Bermuda">Bermuda</option>
                <option value="Atlantic/Canary">Canary</option>
                <option value="Atlantic/Cape_Verde">Cape Verde</option>
                <option value="Atlantic/Faeroe">Faeroe</option>
                <option value="Atlantic/Faroe">Faroe</option>
                <option value="Atlantic/Jan_Mayen">Jan Mayen</option>
                <option value="Atlantic/Madeira">Madeira</option>
                <option value="Atlantic/Reykjavik">Reykjavik</option>
                <option value="Atlantic/South_Georgia">South Georgia</option>
                <option value="Atlantic/Stanley">Stanley</option>
                <option value="Atlantic/St_Helena">St Helena</option>
            </optgroup>

            <optgroup label="Pacific">
                <option value="Pacific/Apia">Apia</option>
                <option value="Pacific/Auckland">Auckland</option>
                <option value="Pacific/Chatham">Chatham</option>
                <option value="Pacific/Easter">Easter</option>
                <option value="Pacific/Efate">Efate</option>
                <option value="Pacific/Enderbury">Enderbury</option>
                <option value="Pacific/Fakaofo">Fakaofo</option>
                <option value="Pacific/Fiji">Fiji</option>
                <option value="Pacific/Funafuti">Funafuti</option>
                <option value="Pacific/Galapagos">Galapagos</option>
                <option value="Pacific/Gambier">Gambier</option>
                <option value="Pacific/Guadalcanal">Guadalcanal</option>
                <option value="Pacific/Guam">Guam</option>
                <option value="Pacific/Honolulu">Honolulu</option>
                <option value="Pacific/Johnston">Johnston</option>
                <option value="Pacific/Kiritimati">Kiritimati</option>
                <option value="Pacific/Kosrae">Kosrae</option>
                <option value="Pacific/Kwajalein">Kwajalein</option>
                <option value="Pacific/Majuro">Majuro</option>
                <option value="Pacific/Marquesas">Marquesas</option>
                <option value="Pacific/Midway">Midway</option>
                <option value="Pacific/Nauru">Nauru</option>
                <option value="Pacific/Niue">Niue</option>
                <option value="Pacific/Norfolk">Norfolk</option>
                <option value="Pacific/Noumea">Noumea</option>
                <option value="Pacific/Pago_Pago">Pago Pago</option>
                <option value="Pacific/Palau">Palau</option>
                <option value="Pacific/Pitcairn">Pitcairn</option>
                <option value="Pacific/Ponape">Ponape</option>
                <option value="Pacific/Port_Moresby">Port Moresby</option>
                <option value="Pacific/Rarotonga">Rarotonga</option>
                <option value="Pacific/Saipan">Saipan</option>
                <option value="Pacific/Samoa">Samoa</option>
                <option value="Pacific/Tahiti">Tahiti</option>
                <option value="Pacific/Tarawa">Tarawa</option>
                <option value="Pacific/Tongatapu">Tongatapu</option>
                <option value="Pacific/Truk">Truk</option>
                <option value="Pacific/Wake">Wake</option>
                <option value="Pacific/Wallis">Wallis</option>
                <option value="Pacific/Yap">Yap</option>
            </optgroup>
            
            <optgroup label="Antarctica">
                <option value="Antarctica/Casey">Casey</option>
                <option value="Antarctica/Davis">Davis</option>
                <option value="Antarctica/DumontDUrville">DumontDUrville</option>
                <option value="Antarctica/Macquarie">Macquarie</option>
                <option value="Antarctica/Mawson">Mawson</option>
                <option value="Antarctica/McMurdo">McMurdo</option>
                <option value="Antarctica/Palmer">Palmer</option>
                <option value="Antarctica/Rothera">Rothera</option>
                <option value="Antarctica/South_Pole">South Pole</option>
                <option value="Antarctica/Syowa">Syowa</option>
                <option value="Antarctica/Vostok">Vostok</option>
            </optgroup>

        ';

        return $timezoneHTML;
    }

    public function buildDropState($selected = '')
    {

        $html = '';

        $dbh = $this->initDB();
        $sth = $dbh->query('select distinct(full_state), state from zip_codes where full_state is not null order by full_state');

//        $html .= (!empty($selected)) ? '<option value="' . $selected . '"selected>' . $selected . '</option>' : '';

        if (empty($selected)) $html .= '<option value="" selected></option>';

        $selAttr = '';
        $i = 0;

        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {

            if ($i == 0 && !empty($selected)) {
                if (strcasecmp($selected, $f->state) == 0 || strcasecmp($selected, $f->full_state) == 0) {
                    $selAttr = 'selected';
                    $i++;
                }
            }

            $html .= '<option value="' . $f->full_state . '" ' . $selAttr . '>' . $f->full_state . '</option>';

            if ($i == 1) $selAttr = '';
        }

        return $html;
    }

//    public function CreateSearchTempTable()
//    {
//        $_dbh = $this->initDB();
//
//        $delQry = "delete from cp_print_friendly_search where parent_agency = :parent";
//        $dbhD = $_dbh->prepare($delQry);
//        $sthd = $dbhD->execute([':parent' => $_SESSION['parent_agency']]);
//
//        $qry = "select * from cp_search_orgs_and_locations
//                where parent_agency = :parent
//                and agency_id <> :parent
//                and `status` = 'ACTIVE'";
//        $sth = $_dbh->prepare($qry);
//        $sth->execute([':parent' => $_SESSION['parent_agency']]);
//
//        $insertQry = '';
//        while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
//            //Contacts
//            $_agency = new agency();
//            $agencyContact = $_agency->get_agency_contact($f->agency_id);
//            $contacts = '';
//            foreach ($agencyContact as $col) {
//                $contacts .= $col['first_name'] . ' ' . $col['last_name'] .',';
//            }
//
//            //Level1
//            $_level = new Level1();
//            $lvl = explode(';', $f->level_1);
//            $level_1 = '';
//            foreach ($lvl as $lvlVal) {
//                $level_1 .= $_level->GetLevel1Name($lvlVal).',';
//            }
//
//            //Partner Type
//            $partnerType = '';
//            if (!empty($f->partner_type)) {
//                $_partnerType = new PartnerType();
//                $pt = explode(";", $f->partner_type);
//                foreach ($pt as $val) {
//                    $partnerType .= $_partnerType->GetPartnerTypeName($val).',';
//                }
//            }
//
//            //Services
//            $qry = "select * from cp_search_items
//            where agency_id = :agencyId
//            and `status` = 'ACTIVE'
//            and `item_status` = 'ACTIVE'
//            and `sub_item_status` = 'ACTIVE'
//            and `sub_item_2_status` = 'ACTIVE'
//            ";
//            $sthi = $_dbh->prepare($qry);
//            $sthi->execute([':agencyId' => $f->id]);
//
//            $resourceType = '';
//            $itemName = '';
//            while ($fi = $sthi->fetch(PDO::FETCH_OBJ)) {
//                $resourceType .= $fi->category.',';
//                $itemName .= $fi->item.',';
//            }
//
////            $searchField = str_replace(" ", ",", $f->agency_name) . ',' .
////                $f->org_type . ',' .
////                $level_1 .
////                $f->agency_city .
////                $f->agency_state .
////                $f->agency_zipcode .
////                $contacts .
////                $partnerType .
////                $resourceType .
////                $itemName;
//
//
//            $insertQry .= 'insert into cp_print_friendly_search
//                        (id
//                        ,agency_id
//                        ,parent_agency
//                        ,agency_name
//                        ,level_1
//                        ,contacts
//                        ,agency_address
//                        ,agency_city
//                        ,agency_state
//                        ,agency_zipcode
//                        ,agency_telephone
//                        ,status
//                        ,partner_type
//                        ,org_type
//                        ,resource_type
//                        ,item_name)
//                        values
//                        (
//                        "'.$f->id.'"
//                        ,"'.$f->agency_id.'"
//                        ,"'.$f->parent_agency.'"
//                        ,"'.$f->agency_name.'"
//                        ,"'.rtrim($level_1,',').'"
//                        ,"'.rtrim($contacts,',').'"
//                        ,"'.$f->agency_address.'"
//                        ,"'.$f->agency_city.'"
//                        ,"'.$f->agency_state.'"
//                        ,"'.$f->agency_zipcode.'"
//                        ,"'.$f->agency_telephone.'"
//                        ,"'.$f->status.'"
//                        ,"'.rtrim($partnerType,',').'"
//                        ,"'.$f->org_type.'"
//                        ,"'.rtrim($resourceType,',').'"
//                        ,"'.rtrim($itemName,',').'"
//                        );
//                        ';
//
//        }
//        $_dbh->query($insertQry);
//    }


}

//end class
