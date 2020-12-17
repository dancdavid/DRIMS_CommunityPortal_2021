<?php 
$_core = new core(); 
$_agn = new agency();
$agency_name = $_agn->get_agency_name($_core->decode($_core->gpGet(('id'))));
?>

<div class="col-sm-12 col-xs-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">ADD <?php echo strtoupper($agency_name); ?> SERVICES
                <?php
                if (isset($_GET['err'])) {
                    echo "<font class='text-danger'><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> {$_GET['err']}</font>";
                }
                ?>
                </h3>
        </div>
        
        <div class="panel-body">
            

        </div>

        
    </div>
</div>
