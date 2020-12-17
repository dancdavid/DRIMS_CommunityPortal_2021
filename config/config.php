<?php
/*
 * Written by Dan David
 */

define("ROOT", dirname(dirname(__FILE__)));

define("DEVELOP",TRUE);

if ($_SERVER['SERVER_NAME'] === 'drims.tst')
{
    define('ROOT_DOMAIN', '.drims.tst');
} else {
    define('ROOT_DOMAIN', '.drims.org');
}

if ($_SERVER['SERVER_NAME'] === 'cpdev.drims.org') {
    define("ROOT_URL", "https://" . $_SERVER['SERVER_NAME'] . "/");
    define("ROOT_CMS_URL", "https://dev.drims.org/");
    define("MAIN_DIR", "https://" . $_SERVER['SERVER_NAME'] . "/");
    define('C_DSN', 'mysql:unix_socket=/tmp/mysql5.sock;dbname=db735907024');
    define('C_USER', 'dbo735907024');
    define('C_PWD', 'DRI20ms18*');

} else if ($_SERVER['SERVER_NAME'] === 'cpbeta.drims.org') {
    define("ROOT_URL", "https://" . $_SERVER['SERVER_NAME'] . "/");
    define("ROOT_CMS_URL", "https://beta.drims.org/");
    define("MAIN_DIR", "https://" . $_SERVER['SERVER_NAME'] . "/");
    define('C_DSN', 'mysql:unix_socket=/tmp/mysql5.sock;dbname=db813402292');  //MySql 5.5
    define('C_USER', 'dbo813402292');
    define('C_PWD', 'DRI20ms19*');
    define('C_DB', 'db813402292');

} else if ($_SERVER['SERVER_NAME'] === 'cp.drims.org') {
    define("ROOT_URL", "https://" . $_SERVER['SERVER_NAME'] . "/");
    define("ROOT_CMS_URL", "https://live.drims.org/");
    define("MAIN_DIR", "https://" . $_SERVER['SERVER_NAME'] . "/");
    define('C_DSN', 'mysql:unix_socket=/tmp/mysql5.sock;dbname=db820178317'); //PROD MySql 5.5
    define('C_USER', 'dbo820178317');
    define('C_PWD', 'prdDRI20ms19*');
    define('C_DB', 'db820178317');
//    define('C_DSN', 'mysql:unix_socket=/tmp/mysql5.sock;dbname=db735907024');
//    define('C_USER', 'dbo735907024');
//    define('C_PWD', 'DRI20ms18*');
} else {
    define("ROOT_URL", "http://" . $_SERVER['SERVER_NAME'] . "/");
    define("ROOT_CMS_URL", "http://drims.tst/");
    define("MAIN_DIR", "http://" . $_SERVER['SERVER_NAME'] . "/");
//    define('C_DSN', 'mysql:host=localhost;dbname=db819784955');
//    define('C_USER', 'dbo819784955');
//    define('C_PWD', 'U517lD@0B&Iz');
    define('C_DSN', 'mysql:host=localhost;dbname=private'); //local
//    define('C_DSN', 'mysql:unix_socket=/tmp/mysql5.sock;dbname=db735907024');
    define('C_USER', 'root');
    define('C_PWD', '');
}




define('COOKIE_HOST', 'localhost');
define('UPLOAD_DIR', ROOT . '/uploads/');
define('DEBUG', false);


define('CKEY', substr(md5(date('Y-m-d')), 0, 10));
//define('CRYPT_KEY', md5('trac4LApr0ject'));
define('CRYPT_KEY', md5('DRIMSv2019v2'));
define('C_24_KEY', md5(date('Y-m-d')));

//mysql
////PROD CREDS
//define('C_DSN', 'mysql:unix_socket=/tmp/mysql5.sock;dbname=db667279724');

////DEV CREDS
//define('C_DSN', 'mysql:host=localhost;dbname=db667279724');
//define('C_USER', 'dbo667279724');
//
//define('C_PWD', 'Recover123*');

//BETA CREDS
//define('C_DSN', 'mysql:unix_socket=/tmp/mysql5.sock;dbname=db819784955');  //SERVER USE
//define('C_DSN', 'mysql:host=localhost;dbname=db819784955'); //LOCAL USE
//define('C_USER', 'dbo819784955');
//define('C_PWD', 'U517lD@0B&Iz');

//define('C_DATABASE', 'latest_framework');
////oracle
//define('ORA_DSN', 'oci:dbname=');
//define('ORA_USER', '');
//define('ORA_PWD', '');

//PROD
//define("ROOT_URL", "http://" . $_SERVER['SERVER_NAME'] . "/");
//
//DEV


//LOCAL
//define("ROOT_URL", "http://" . $_SERVER['SERVER_NAME'] . "/barr4bayou/");

