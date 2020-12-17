<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Search Results for '<?= $_POST['search'] ?>'
                </h3>
            </div>
            <div class="panel-body">
                <?php
                if(!empty($_POST['search']))
                {
                    $search = explode(" ", $_POST['search']);
                    $_db = new db();

                    $qry = "select distinct(agency_id)
                            , parent_agency_id
                            , agency_name
                            , agency_address
                            , agency_city
                            , agency_state
                            , agency_zipcode
                            , agency_telephone
                            , first_name
                            , last_name
                            from cp_search_view where";

                    $i=0;
                    $qry .= ' ( ';
                    foreach ($search as $val)
                    {
                        if ($i > 0) $qry .= ' or';
                        $qry .= " find_wild_in_set('{$val}%', searchfield)";
                        $i++;
                    }
                    $qry .= ' )';
                    $qry .= " and parent_agency = :parent";
                    $qry .= " and agency_id <> :parent";


                    $_dbh = $_db->initDB();
                    $sth = $_dbh->prepare($qry);
                    $sth->execute([':parent' => $_SESSION['parent_agency']]);

                    if ($sth->rowCount() > 0)
                    {

                        $chkId = '';
                        echo "<table class='table' width='100%'>";
                        while ($f = $sth->fetch(PDO::FETCH_OBJ))
                        {
                            if ($chkId != $f->agency_id)
                            {
                                $_agency = new agency($f->agency_id);
                                echo "<tr>";
                                echo "<td>";
                                echo "<b><a href='agency_summary?id={$_db->encode($f->agency_id)}'>" . $f->agency_name . '</a></b><br>';
                                echo '<address>';
                                echo $f->agency_address . '<br>';
                                echo $f->agency_city . ', ' . $f->agency_state . ' ' . $f->agency_zipcode . '<br>';
                                echo '<b>Contact Person: </b>' . $f->first_name . ' ' . $f->last_name . '<br>';
                                echo '</address>';
                                echo "</td>";
                                echo "<td>";
                                echo $_agency->ListAgencyLocations();
                                echo "</td>";
                                echo "</tr>";
                            }
                            $chkId = $f->agency_id;
                        }
                        echo "</table>";
                    } else {
                        echo "No Results Found.";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>


