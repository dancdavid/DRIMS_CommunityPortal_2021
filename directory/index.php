<?php
session_start();
/*
 * Written by Dan David
 * USERS INDEX
 */

require_once(dirname(dirname(__FILE__)) . '/config/config.php');
require_once(ROOT . '/_lib/router.php');
require_once(ROOT . '/_lib/autoload.php');

spl_autoload_register('load_classes');

$_core = new core();


//VERIFY USER SESSION

if (isset($_COOKIE['vcp'])) {
    $cookie = explode('.', $_COOKIE['vcp']);

    //UPDATED agency_level to cp_user_level
    if ($_COOKIE['v'] === CRYPT_KEY . $cookie[0] . C_24_KEY) {
        $_SESSION['v'] = CRYPT_KEY . session_id() . C_24_KEY;
        $_SESSION['userID'] = $_core->decode($cookie[1]);
        $_SESSION['userLevel'] = $_core->decode($cookie[2]);
        $_SESSION['orgID'] = $_core->decode($cookie[3]);
        $_SESSION['cp_access'] = $_core->decode($cookie[4]);
        $_SESSION['cms_access'] = $_core->decode($cookie[5]);
        $_SESSION['user_id'] = $_core->decode($cookie[6]);
        $_SESSION['agency_id'] = $_core->decode($cookie[7]);
        $_SESSION['user_type'] = $_core->decode($cookie[8]);
        $_SESSION['user_name'] = $_core->decode($cookie[9]);
        $_SESSION['user_email'] = $_core->decode($cookie[10]);
        //$_SESSION['level_1'] = $_core->decode($cookie[11]);
        $_SESSION['cp_user_level'] = $_core->decode($cookie[12]);
    }
}

if ($_SESSION['v'] !== CRYPT_KEY . session_id() . C_24_KEY) {
    $e = urlencode("Session expired please Sign In.");
    $_core->redir('signin&e=' . $e);
    exit;
}

if (!isset($_SESSION['level_1_filter'])) {
    $_SESSION['level_1_filter'] = $_SESSION['level_1'];
}

$_level = new Level1();
if (!empty($_level->GetLevel1Label())) {
    $_SESSION['Level1_Label'] = $_level->GetLevel1Label();
} else {
    $_SESSION['Level1_Label'] = 'Level 1';
}

$_agency = new agency();
if (!isset($_SESSION['parent_agency']) || $_SESSION['parent_agency'] == '-1') {
    $_agency->SetParentAgencySession();
}

$url = (isset($_GET['url'])) ? $_GET['url'] : null;

include(ROOT . '/directory/html/header.users.htm.php');

routerUsers();

include(ROOT . '/directory/html/footer.users.htm.php');

//show variables if debug is on
$_core->debug();