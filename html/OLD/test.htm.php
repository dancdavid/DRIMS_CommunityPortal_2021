<div class="container" style="padding-top:70px;">

    <?php
    $_core = new core();
    $_db = new db();

    $qry = "select * from agency_directory";
    $dbh = $_db->initDB();
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sth = $dbh->prepare($qry);
    $sth->execute();

    $x = array();
    while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
        $x['name'] = $f->name;
    }


    echo "<pre>";
    print_r($x);
    echo "</pre>";

    $k = implode(",", $x);

    echo $k;
    ?>

</div>

