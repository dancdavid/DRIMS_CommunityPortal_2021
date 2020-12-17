<?php

/**
 * Description of db
 * handles most of all oracle database functions
 *
 * @author Dan David
 */
//require config.php
class oraclePDO extends core{

    var $last_inserted_id;

    public function initDB() {
        try {
            $dbh = new PDO(ORA_DSN, ORA_USER, ORA_PWD);
            return $dbh;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function insertUpdateSQL($data, $table) {

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
            foreach ($data as $fn => $val) {
                if ($fn === 'password') {
                    $sth->bindParam(':' . $fn, sha1(md5($val)));
                } else if ($fn === "id") {
                    $sth->bindParam(':' . $fn, $this->decode($val));
                } else {
                    $sth->bindParam(':' . $fn, $val);
                }
            }

            $sth->execute();

            $this->last_inserted_id = $dbh->lastInsertId();

            return true;
        } catch (PDOException $e) {

            if (DEBUG) {
                return $e->getMessage();
            } else {
                return false;
            }
        }
    }

    public function buildDropMenu($field_name, $data = null) {

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
    
    
    public function buildDropYN($data=null) {
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

}

//end class
?>