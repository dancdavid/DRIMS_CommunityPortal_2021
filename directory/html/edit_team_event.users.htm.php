<?php
$_core = new core();
$teamName = 'Team';
$_team = new Teams($_core->decode($_core->gpGet('tid')));
$teamName = $_team->GetTeamName();
?>
<div class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">EDIT EVENTS CALENDAR - <?= $teamName ?></h3>
        </div>
        <div class="panel-body">
            <table id="calendarList" class="table table-bordered table-striped" width="100%">
                <thead>
                <tr>
                    <th>TITLE</th>
                    <th>EVENT DATE</th>
                    <th>STATUS</th>
                    <th>SUBMITTED BY</th>
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
    $(function() {
        $("#calendarList").DataTable({
            "bProcessing": false,
            "sPaginationType": "full_numbers",
            "sDom": '<"top"f>rtp<"clear">',
            "bStateSave": false,
            "sAjaxSource": "../_lib/tmajax.php?action=getTeamCalendarList&tid=<?= $_GET['tid'] ?>",
            "oLanguage": {
                "sZeroRecords": "No records to display",
                "sSearch": "Search:"
            },
            "bDeferRender": true,
            "iDeferLoading": 200,
            "iDisplayLength": 25,
            "aaSorting": [[0, 'asc']],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],

            "aoColumns": [
                /* title*/ {
                    "sWidth": "20%"
                },
                /* event date*/ {
                    "sWidth": "5%"
                },
                /* status */ {
                    "sClass": "text-center",
                    "sWidth": "1%",
                    "bSortable": false
                },
                /* contact */ {
                    "sClass": "text-center",
                    "sWidth": "5%"
                },
                /* edit  */ {
                    "sClass": "text-center",
                    "sWidth": "1%",
                    "bSortable": false
                }
            ]
        });
    });
</script>