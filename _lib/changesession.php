<?php
session_start();

require_once(dirname(dirname(__FILE__)) . '/config/config.php');

include(ROOT . '/_lib/autoload.php');

spl_autoload_register('load_classes');


$_core = new core();

switch ($_GET['user'])
{
    case 'orgadmin':
        unset($_SESSION['cp_user_level']);
        unset($_SESSION['user_type']);
        unset($_SESSION['agency_id']);
        $_SESSION['user_type'] = 'ADMIN';
        $_SESSION['cp_user_level'] = 0;
        $_SESSION['agency_id'] = 6;
        break;

    case 'orguser':
        unset($_SESSION['cp_user_level']);
        unset($_SESSION['user_type']);
        unset($_SESSION['agency_id']);
        $_SESSION['cp_user_level'] = 0;
        $_SESSION['user_type'] = 'USER';
        $_SESSION['agency_id'] = 8;
        break;

    case 'level2':
        unset($_SESSION['cp_user_level']);
        unset($_SESSION['user_type']);
        unset($_SESSION['agency_id']);
        $_SESSION['cp_user_level'] = 2;
        $_SESSION['user_type'] = 'ADMIN';
        $_SESSION['agency_id'] = 3;
        break;

    case 'level1':
        unset($_SESSION['cp_user_level']);
        unset($_SESSION['user_type']);
        unset($_SESSION['agency_id']);
        $_SESSION['cp_user_level'] = 1;
        $_SESSION['user_type'] = 'ADMIN';
        $_SESSION['agency_id'] = 3;
        break;
}

$_core->redir('directory/');

