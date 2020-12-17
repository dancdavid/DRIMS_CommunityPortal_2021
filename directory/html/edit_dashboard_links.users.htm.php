<?php
$_core = new core();
if (!UserAccess::ManageLevel1()) {
    $_core->redir('directory');
}
?>

<div class="col-md-10 col-md-offset-1">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">
                Edit Dashboard Links
            </h3>
        </div>
        <div class="panel-body">
            <table id="dashboardLinks" class="table table-striped" width="100%">
                <thead>
                <tr>
                    <th>TItle</th>
                    <th><?= $_SESSION['Level1_Label'] ?></th>
                    <th>Status</th>
                    <th>Url</th>
                    <th>Description</th>
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>


<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<script>
    $(function () {

        $("#dashboardLinks").DataTable({
            "bProcessing": false,
            "sDom": '<"top"f>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/ajax.php?action=GetAllDashboardLinks",
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search Links:"
            },
            "bDeferRender": true,
            "iDeferLoading": 200,
            "iDisplayLength": -1,
            "aaSorting": [[0, 'asc']],
            "aoColumnDefs" : [{ "sWidth": "5%", "aTargets": [ 3 ] }]

        });
    });
</script>
