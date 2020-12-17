<?php
$_core = new core();
$agency_id = $_core->decode($_core->gpGet('id'));
$_agency = new agency($agency_id);
?>

<div class="col-sm-12 col-xs-12">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title"><font class="text-primary"><?php echo strtoupper($_agency->get_agency_name($agency_id)); ?></font> <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> EDIT SERVICES <a href="agency_summary?id=<?php echo $_core->gpGet('id'); ?>" class="pull-right text-primary"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Back to Agency Info</a></h3>
        </div>
        <div class="panel-body">

            <form class="form-horizontal" enctype="multipart/form-data" id="addAgencyInfo" role="form" method="post" action="../_lib/action.php?action=<?php echo $_core->encode('editAgencyServices'); ?>">
                <input type="hidden" name="agency_id" value="<?php echo $_core->gpGet('id'); ?>">
                <?php $_agency->build_services_panel_tmpl(); ?>
                <nav class="navbar navbar-default navbar-fixed-bottom">
                    <div class="container" style="padding-top:1px;margin-right:15px;">
                        <button id="save_services" class="pull-right btn btn-lg btn-danger">SAVE SERVICES <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button>
                    </div>
                </nav>
            </form>

        </div>
    </div>

</div>

<div class="overlay">
    <div id="loading-img"></div>
</div>

<script>
    $(function () {

        $("#save_services").click(function () {
            $(".overlay").show();
        });
    });
</script>



