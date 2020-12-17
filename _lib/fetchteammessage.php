<?php
function CheckBlankDate($DateString)
{ if (($DateString == "Nov-30--0001") || ($DateString == "00-00-0000") || ($DateString == "Dec-31-1969") ){
    $DateString = "";
}
    Return $DateString;
}

session_start();

require_once(dirname(dirname(__FILE__)) . '/config/config.php');

include(ROOT . '/_lib/autoload.php');

spl_autoload_register('load_classes');

$_core = new core();
$_db = new db();

$item_per_page = 5;

//sanitize post value
$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);

//throw HTTP error if page number is not valid
if (!is_numeric($page_number)) {
    header('HTTP/1.1 500 Invalid page number!');
    exit();
}

//get current starting point of records
$position = (($page_number - 1) * $item_per_page);

$teamIdEnc = $_core->gpGet('tid');
$teamId = $_core->decode($teamIdEnc);

$dbh = $_db->initDB();
$qry = "select * from cp_team_message_board
        where team_id = :teamId 
        order by id desc 
        limit {$position}, {$item_per_page}";

$sth = $dbh->prepare($qry);
$sth->execute([':teamId' => '3']);

//output results from database
while ($f = $sth->fetch(PDO::FETCH_OBJ))
{
    $posted_by = $f->submitted_by;
    $posted_date1 = date('M-d-Y', strtotime($f->timestamp));
    $posted_date =  CheckBlankDate($posted_date1);

    //echo $posted_by;  die();
    $post_date = date("M-d-Y", strtotime($f->timestamp));
    echo "<b>{$f->title}</b> <br>";
    echo $f->message . "<br>";
    echo "<small class='pull-right'>{$post_date} - <i>{$f->submitted_by}</i></small><hr></hr><br>";
}
?>
