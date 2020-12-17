<div class="col-sm-12 col-xs-12" style="padding-bottom: 30px; padding-top:130px;">
    <h4 class="text-danger" style="padding-bottom: 10px;">BARR AGENCY LIST 

        <?php
        $_core = new core();
        $_agency = new agency();
        
        echo "<font class='text-primary'><span class='glyphicon glyphicon-arrow-right'></span> {$_agency->build_search_breadcrumb($_core->gpGet('sid'), $_core->gpGet('t'), $_core->gpGet('l'))}</font> <a href='agency_list' class='btn btn-xs btn-danger'>Clear Search <span class='glyphicon glyphicon-remove-sign' aria-hidden='true'></span></a>";

        ?>

        <button data-toggle="modal" data-target="#searchServicesModal" class="btn btn-xs btn-warning pull-right">SEARCH SERVICES <span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>

    </h4>

    <div style="top:-30px">
        <table id="agencyList" class="table table-bordered table-striped" style="font-size:12px">
            <thead>
                <tr>
                    <th>AGENCY NAME</th>
                    <th>ADDRESS</th>
                    <th>CITY</th>
                    <th>TELEPHONE</th>
                    <th>SERVICES</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="servicesModal" tabindex="-1" role="dialog" aria-labelledby="myServiceModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="searchServicesModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form class='form-horizontal' action='_lib/action.php?action=<?php echo $_core->encode('searchServicesPublic'); ?>' method='post'>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Search Agency Services</h4>
                </div>
                <div class="modal-body">
                    <!--<div class="row">-->
                        <div class="col-xs-3 col-sm-3">
                            <div class='checkbox'>
                                <label> <input type='checkbox' name='terrebonne' value='yes'>Terrebonne</label>
                            </div>
                        </div>

                        <div class="col-xs-3 col-sm-3">
                            <div class='checkbox'>
                                <label> <input type='checkbox' name='lafourche' value='yes'>Lafourche</label>
                            </div>
                        </div>

                        <div class='form-group'>
                            <!--<label>Services</label>-->
                            <div class="col-xs-6 col-sm-6">
                                <select name='service_id' class='form-control'>
                                    <option selected value="">SELECT SERVICE</option>
                                    <?php echo build_services_dropdown(); ?>
                                </select>
                            </div>
                        </div>
                    <!--</div>-->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
        </form>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<!--<script type="text/javascript" src="../js/dataTables.bootstrap.min.js"></script>-->
<script>
    var ter = '<?php echo $_core->gpGet('t'); ?>';
    var laf = '<?php echo $_core->gpGet('l'); ?>';
    var sid = '<?php echo $_core->gpGet('sid'); ?>';
    
    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
        location.reload();
    });
</script>
<script type="text/javascript" src="js/publicAgencyListServices.js"></script>

<?php

function build_services_dropdown() {
    $_db = new db();
    $dbh = $_db->initDB();
    $qry = "select distinct(major) as major from cp_services_list";
    $sth = $dbh->query($qry);

    $htm = '';
    while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
        $htm .= "<optgroup label='$f->major'>";
        $htm .= get_services_minor($f->major);
    }

    return $htm;
}

function get_services_minor($major) {
    $_db = new db();
    $dbh = $_db->initDB();
    $qry = "select id, minor from cp_services_list where major = '{$major}'";
    $sth = $dbh->query($qry);

    $htm = '';
    while ($f = $sth->fetch(PDO::FETCH_OBJ)) {
        $htm .= "<option name='service_id' value='{$f->id}'>{$f->minor}</option>";
    }

    return $htm;
    
}
?>


