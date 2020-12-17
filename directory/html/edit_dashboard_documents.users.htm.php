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
                Edit Dashboard Docs
            </h3>
        </div>
        <div class="panel-body">
            <table id="dashboardDocs" class="table table-striped" width="100%">
                <thead>
                <tr>
                    <th>Title</th>
                    <th><?= $_SESSION['Level1_Label'] ?></th>
                    <th>Description</th>
                    <th>Status</th>
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
        $("#dashboardDocs").DataTable({
            "bProcessing": false,
            "sDom": '<"top"f>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/ajax.php?action=GetAllDashboardDocs",
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search Docs:"
            },
            "bDeferRender": true,
            "iDeferLoading": 200,
            "iDisplayLength": -1,
            "aaSorting": [[0, 'asc']]
        });
    });
</script>
