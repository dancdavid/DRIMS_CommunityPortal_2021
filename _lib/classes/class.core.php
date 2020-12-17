<?php

/**
 * @author Dan David
 */
class core {

    protected $html = '';

    //RETURN GET OR POST DATA
    public function gpGet($varName) {
        $gpResult = null;
        if (isset($_POST[$varName])) {
            $gpResult = $_POST[$varName];
        }
        if (isset($_GET[$varName])) {
            $gpResult = $_GET[$varName];
        }

        return $gpResult;
    }

    public function redir($t_header) {
        header("Location: " . ROOT_URL . "$t_header");
        exit;
    }

    public function limitWords($string, $word_limit) {
        $words = explode(" ", $string);
        return implode(" ", array_splice($words, 0, $word_limit));
    }


    //UPDATE ENCODE DECODDE TO MATCH CMS
    public function encode($var) {
        $r = substr(md5(mt_rand()), 0, 10) . str_replace("=", "", base64_encode($var)) . substr(md5(mt_rand()), 0, 12);
        return $r;
//        $r = substr(md5(mt_rand()), 0, 10) . str_replace("=", "", base64_encode($var));
//        return $r;
    }

    public function decode($var) {
        $decode = substr_replace($var, "", 0, 10);
        $decoded = substr_replace($decode, '', -12);
        $r = base64_decode($decoded);
        return $r;
//        $decode = substr_replace($var, "", 0, 10);
//        $r = base64_decode($decode);
//        return $r;
    }

    public function includeJS($lib) {
        $html = "<script type=\"text/javascript\" src=\"../js/$lib\"></script>";
        return $html;
    }

    public function loadingImage() {
        $html = "<img src='images/loading.gif' alt=''><br>\n"
                . "Loading ...\n";
        return $html;
    }

    public function rowColor($cnt, $color1 = null, $color2 = null) {
        $color1 = (isset($color1)) ? $color1 = $color1 : $color1 = "#ffffff";
        $color2 = (isset($color2)) ? $color2 = $color2 : $color2 = "#c0c0c0";
        $row_color = ($cnt % 2) ? $color1 : $color2;
        return $row_color;
    }

    public function randomPassword($var = 8) {
        $alphabet = "abcdefghijklmnopqrstuwxyz0123456789$#!?ABCDEFGHIJKLMNOPQRSTUWXYZ";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache

        for ($i = 0; $i < $var; $i++) {
            $n = mt_rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass); //turn the array into a string
    }

    public function generateSalt($encryption = 'md5') {
        $salt = uniqid(mt_rand(), true);

        switch ($encryption) {
            case 'blowfish':
                $enc = "$2a$";
                break;
            case 'sha_256':
                $enc = "$5$";
                break;
            case 'sha_512':
                $enc = "$6$";
                break;
            default:
                $enc = "$1$";
                break;
        }

        return $enc . $salt;
    }

    public function debug() {

        if (DEBUG) {
            echo "<div class='container'>";

            echo "<a href='../_lib/changesession.php?user=orgadmin' class='btn btn-primary'>Change Org Admin</a>";
            echo "<a href='../_lib/changesession.php?user=orguser' class='btn btn-danger'>Change Org User</a>";
            echo "<a href='../_lib/changesession.php?user=level2' class='btn btn-success'>Change Level 2 User</a>";
            echo "<a href='../_lib/changesession.php?user=level1' class='btn btn-warning'>Change Level 1 User</a>";

            echo "<p style='padding-top:10px;'>";
            echo "<pre>SESSION<br>";
            echo session_id() . '<br>';
            print_r($_SESSION);
            echo "</pre>";
            
            echo "<pre>COOKIE<br>";
            print_r($_COOKIE);
            echo "</pre>";

            echo "<pre>POST<br>";
            print_r($_POST);
            echo "</pre>";

            echo "<pre>GET<br>";
            print_r($_GET);
            echo "</pre>";   
            echo "</p>";

            echo "<pre>DECODED<br>";
//            echo "SID: " . $this->decode($_GET['sid']) . '<br>';
//            echo "IID: " . $this->decode($_GET['iid']) . '<br>';
//            echo "SIID: " . $this->decode($_GET['siid']) . '<br>';
            echo "</div>";


        }
    }

//DROP DOWN OPTIONS FOR DAY MONTH YEAR HOUR MINUTES
    public function dropDay() {
        $html = "";
        for ($i = 0; $i <= 31; $i++) {

            $i = (strlen($i) == 1) ? $i = "0" . $i : $i = $i;

            if ($i == 0) {
                $html .= "<option value='' selected>DD</option>";
                $html .= "<optgroup label='-----'></optgroup>";
            } else {
                $html .= "<option value='$i'>$i</option>";
            }
        }
        return $html;
    }

    public function dropMonth() {
        $html = "";
        for ($i = 0; $i <= 12; $i++) {

            $i = (strlen($i) == 1) ? $i = "0" . $i : $i = $i;

            if ($i == 0) {
                $html .= "<option value='' selected>MM</option>";
                $html .= "<optgroup label='-----'></optgroup>";
            } else {
                $html .= "<option value='$i'>$i</option>";
            }
        }
        return $html;
    }

    public function dropYear($start = '', $end = '') {
        $html = "";
        $year = (isset($start)) ? (date('Y') - $start) : date('Y');

        $calc = (isset($end)) ? ($year - $end) : $year;

        for ($j = $year; $j >= ($calc); $j--) {

            if ($j == $year) {
                $html .= "<option value='' selected>YYYY</option>";
                $html .= "<optgroup label='-----'></optgroup>";
            } else {
                $html .= "<option value='$j'>$j</option>";
            }
        }
        return $html;
    }

    public function dropHour($var) {
        $html = "";

        for ($j = 1; $j <= 12; $j++) {

            if (strlen($j) == 1) {
                $j = "0" . $j;
            }

            if ($j == $var) {
                $html .= "<option value='$j' selected>$j</option>";
            } else {
                $html .= "<option value='$j'>$j</option>";
            }
        }
        return $html;
    }

    public function dropMinute($var) {
        $html = "";

        $i = '00';
        while ($i <= 45) {
            if ($i == $var) {
                $html .= "<option value='$i' selected>$i</option>";
            } else {
                $html .= "<option value='$i'>$i</option>";
            }
            $i = ($i + 15);
        }

        return $html;
    }

    public function SetParentAgencyId()
    {
        if ($_SESSION['parent_agency'] == '-1')
            return $_SESSION['agency_id'];
        else
            return $_SESSION['parent_agency'];
    }

    public function IncludeJqueryConfirm()
    {
        echo '<link rel="stylesheet" href="../css/jquery-confirm.min.css">';
        echo '<script type="text/javascript" src="../js/jquery-confirm.min.js"></script>';
    }
    

}
