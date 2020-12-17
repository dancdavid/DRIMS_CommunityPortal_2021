<?php
define("ROOT_PATH", dirname(dirname(dirname(__FILE__))));

require ROOT_PATH . '/config/config.php';
require_once (ROOT_PATH . '/_lib/autoload.php');

spl_autoload_register('load_classes');

$_db = new db();
$dbh = $_db->initDB();
$qry = "select 
       first_name,
       last_name,
       email,
       phone,
       address,
       city,
       state,
       zipcode,
       status,
       start_date,
       end_date,
       categories,
       notes
       from cp_volunteers order by first_name
       ";

$sth = $dbh->query($qry);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="BARR_Volunteer.csv"');
header("Pragma: no-cache");
header("Expires: 0");


$fp = fopen('php://output', 'w');

// first set
$first_row = $sth->fetch(PDO::FETCH_ASSOC);
$headers = array_keys($first_row);
fputcsv($fp, $headers); // put the headers
fputcsv($fp, array_values($first_row)); // put the first row

while ($row = $sth->fetch(PDO::FETCH_NUM))  {
fputcsv($fp,$row); // push the rest
}
fclose($fp);  






