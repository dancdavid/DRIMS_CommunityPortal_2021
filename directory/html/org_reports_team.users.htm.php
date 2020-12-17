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
            <li role="presentation" class="active"><a href="#">Team Utilization</a></li>
            <li role="presentation"><a href="org_reports_user">Individual User Utilization</a></li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="table table-hover" id="listOrgTeams">
                    <thead>
                    <tr>
                        <th rowspan="2">Team Name</th>
                        <th rowspan="2"><?= $_SESSION['Level1_Label'] ?></th>
                        <th rowspan="2">Members</th>
                        <th colspan="2">Posted Messages</th>
                        <th colspan="2">Posted Events</th>
                        <th colspan="2">Posted Documents</th>
                        <th colspan="2">Posted Links</th>
                    </tr>
                    <tr>
                        <th>Messages</th>
                        <th>Last Update</th>
                        <th>Events</th>
                        <th>Last Update</th>
                        <th>Documents</th>
                        <th>Last Update</th>
                        <th>Links</th>
                        <th>Last Update</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/sc-2.0.0/datatables.min.js"></script>
<script>
    $(function () {
        $("#listOrgTeams").DataTable({
            scrollY:        '70vh',
            scrollCollapse: true,
            paging:         false,
            "bProcessing": false,
            "sDom": '<"top"Bif>rt<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/reportajax.php?action=OrgTeamReport",
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search:"
            },
            "bDeferRender": true,
            "iDeferLoading": 200,
            "aaSorting": [[0, 'asc']],
            "buttons": [
                {
                    'extend': 'excelHtml5',
                    'text': 'Excel',
                    'title': 'Team Utilization Report - <?= date("M d, Y") ?> powered by DRIMS'
                },
                {
                    'extend': 'csvHtml5',
                    'title': 'Team Utilization Report - <?= date("M d, Y") ?> powered by DRIMS'
                },
                {
                    'extend': 'pdfHtml5',
                    'text': 'PDF',
                    'orientation': 'landscape',
                    'title': 'Team Utilization Report - <?= date("M d, Y") ?> powered by DRIMS'
                },
                {
                    'extend': 'print',
                    'title': 'Team Utilization Report - <?= date("M d, Y") ?> powered by DRIMS'
                }
            ]
            // ,
            // "aoColumnDefs": [
            //     { "aTargets":[3], "bSortable":false }
            // ]
        });
    });
</script>
