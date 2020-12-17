<?php
$_core = new core();
if (!UserAccess::ManageLevel1()) {
    $_core->redir('directory');
}
?>
<div class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">EDIT EVENTS CALENDAR</h3>
        </div>
        <div class="panel-body">
            <table id="calendarList" class="table table-bordered table-striped" width="100%">
                <thead>
                <tr>
                    <th>TITLE</th>
                    <th>EVENT DATE</th>
                    <th><?= strtoupper($_SESSION['Level1_Label']) ?></th>
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
<script type="text/javascript" src="../js/calendarList.js"></script>