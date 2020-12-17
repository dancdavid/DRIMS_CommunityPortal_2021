<?php
$_core = new core();
if (!UserAccess::ManageLevel1()) {
    $_core->redir('directory');
}
?>
<style>
    .dataTables_wrapper .dt-buttons {
        float: right;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="org_reports">Community Portal Summary</a></li>
            <li role="presentation"><a href="org_reports_team">Team Utilization</a></li>
            <li role="presentation" class="active"><a href="#">Individual User Utilization</a></li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-hover" id="listGlobalOrgUsers">
                    <thead>
                    <tr>
                        <th><?= $_SESSION['Level1_Label'] ?></th>
                        <th>Organization</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact Type</th>
                        <th>User Status</th>
                        <th>Notifications On</th>
                        <th>Last Login Date</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/sc-2.0.0/datatables.min.js"></script>
<script>
    $(function () {
        $("#listGlobalOrgUsers").DataTable({
            scrollY:        '70vh',
            scrollCollapse: true,
            paging:         false,
            "bProcessing": false,
            "sDom": '<"top"Bif>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/reportajax.php?action=OrgUserReport",
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search:"
            },
            "bDeferRender": true,
            "iDeferLoading": 200,
            "aaSorting": [[2, 'asc']],
            "buttons": [
                {
                    'extend': 'excelHtml5',
                    'text': 'Excel',
                    'title': 'Individual User Utilization - <?= date("M d, Y") ?> powered by DRIMS'
                },
                {
                    'extend': 'csvHtml5',
                    'title': 'Individual User Utilization - <?= date("M d, Y") ?> powered by DRIMS'
                },
                {
                    'extend': 'pdfHtml5',
                    'text': 'PDF',
                    'orientation': 'landscape',
                    'title': 'Individual User Utilization - <?= date("M d, Y") ?> powered by DRIMS'
                },
                {
                    'extend': 'print',
                    'title': 'Individual User Utilization - <?= date("M d, Y") ?> powered by DRIMS'
                }
            ]
            // ,
            // "aoColumnDefs": [
            //     { "aTargets":[3], "bSortable":false }
            // ]
        });
    });
</script>
