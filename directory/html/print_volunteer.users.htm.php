<?php
$_core = new core();
$_db = new db();
$dbh = $_db->initDB();

$qry = "select * from cp_volunteers order by status";

$sth = $dbh->query($qry);

while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
    ?>

    <div class="col-sm-12 col-xs-12" style="page-break-inside: avoid;">
        <img src='images/BARR_logo.png'>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">VOLUNTEER: <?php echo "$f->first_name $f->last_name"; ?></h3>
            </div>
            <div class="panel-body">

                <div class="col-xs-6 col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">INFO</h3>
                        </div>
                        <div class="panel-body">
                            <address>
                                <?php
                                echo "<b>$f->first_name $f->last_name</b><br>";
                                echo $f->address . "<br>";
                                echo $f->city . " " . $f->state . " " . $f->zipcode . "<br><br>";
                                
                                echo "<b>Phone:</b> $f->phone <br>";
                                echo "<b>Email:</b> $f->email <br><br>";
                                
                                echo "<b>Status:</b> $f->status <br>";
                                $start_date = (!empty($f->start_date)) ? date('M-d-Y',strtotime($f->start_date)) : '';
                                $end_date = (!empty($f->end_date)) ? date('M-d-Y',strtotime($f->end_date)) : '';
                                echo "<b>Start Date:</b> $start_date <br>";
                                echo "<b>End Date:</b> $end_date <br>";
                                ?>
                            </address>
                        </div>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">CATEGORIES</h3>
                        </div>
                        <div class="panel-body">
                            <?php echo str_replace(";","<br>",$f->categories); ?>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">NOTES</h3>
                        </div>
                        <div class="panel-body">
                            <?php echo nl2br($f->notes); ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php } ?>


<script>

    function myPrint() {
        window.print();
    }

    $(function () {
        myPrint();
    });
</script>