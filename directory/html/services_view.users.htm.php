<?php
$_core = new core();
if (!UserAccess::ManageLevel1()) {
    $_core->redir('directory');
}

$_db = new db();
?>


<div class="col-md-10 col-md-offset-1">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Services / Resources / Training
                <a href="add_services" class="btn btn-xs btn-danger pull-right">+ Add Service</a>
            </h3>
        </div>
        <div class="panel-body">

        <table class="table" id="listAvailableServices">
            <thead>
            <tr>
                <th>TITLE</th>
                <th>CATEGORY</th>
                <th>ITEM NAME</th>
                <th>SUB-ITEMS</th>
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
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});

        $("#listAvailableServices").DataTable({
            "bProcessing": false,
            "sDom": '<"top"f>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/ajax.php?action=GetServices",
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search Services:"
            },
            "bDeferRender": true,
            "iDeferLoading": 200,
            "iDisplayLength": -1,
            "aaSorting": [[0, 'asc']],
            "aoColumnDefs": [
                { "aTargets":[3], "bSortable":false }
            ]

        });
    });
</script>


